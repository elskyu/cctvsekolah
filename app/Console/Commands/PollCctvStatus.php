<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Sekolah;
use App\Models\Panorama;
use App\Models\CctvOffline;
use Illuminate\Support\Facades\Log;

class PollCctvStatus extends Command
{
    protected $signature = 'cctv:poll-status';
    protected $description = 'Cek status online/offline semua CCTV Sekolah dan Panorama dengan metode seragam';

    protected array $longTimeoutStreams = [
        'cctv_sman1kasihan_tengahsekolah',
        'cctv_smkn2depok_gerbangbelakang',
    ];

    protected array $panoramaStreams = [
        'ViewBaronBarat',
    ];

    protected function extractStreamId(string $url): ?string
    {
        $query = [];
        parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);
        return $query['id'] ?? null;
    }

    protected function buildStreamUrls(string $streamId): array
    {
        return [
            "http://103.255.15.227:5080/live/{$streamId}/playlist.m3u8",
            "http://103.255.15.227:5080/hls/{$streamId}.m3u8",
            "http://103.255.15.227:5080/CCTVSEKOLAH/streams/{$streamId}.m3u8",
            "http://103.255.15.227:5080/CCTVPUBLIC/streams/{$streamId}.m3u8",
        ];
    }

    protected function getDynamicTimeout(string $streamId): int
    {
        return in_array($streamId, $this->longTimeoutStreams) ? 20 : 15;
    }

    protected function isStreamPlayable(string $url, string $streamId): bool
    {
        try {
            $response = Http::timeout($this->getDynamicTimeout($streamId))
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0',
                    'Referer' => 'http://103.255.15.227:5080/',
                    'Accept' => '*/*',
                    'Connection' => 'keep-alive',
                ])
                ->get($url);

            if (!$response->successful()) {
                Log::warning("Stream check gagal", [
                    'url' => $url,
                    'status' => $response->status(),
                    'stream_id' => $streamId,
                ]);
                return false;
            }

            $content = $response->body();
            return (bool) preg_match('/#EXTM3U|#EXTINF:|\.ts(?:\?|$)/', $content);
        } catch (\Exception $e) {
            Log::error("Error cek stream", [
                'url' => $url,
                'error' => $e->getMessage(),
                'stream_id' => $streamId,
            ]);
            return false;
        }
    }

    protected function checkWithRetry(array $urls, string $streamId, int $maxRetries = 2): bool
    {
        for ($retryCount = 0; $retryCount <= $maxRetries; $retryCount++) {
            foreach ($urls as $url) {
                if ($this->isStreamPlayable($url, $streamId)) {
                    return true;
                }
            }

            if ($retryCount < $maxRetries) {
                $delay = pow(2, $retryCount);
                Log::info("Retry cek stream untuk {$streamId} setelah {$delay} detik");
                sleep($delay);
            }
        }

        return false;
    }

    protected function getStatusWithConfidence(string $streamId, array $urls): bool
    {
        $votes = 0;
        $totalChecks = 3;

        for ($i = 0; $i < $totalChecks; $i++) {
            if ($this->checkWithRetry($urls, $streamId)) {
                $votes++;
            }
            if ($i < $totalChecks - 1) {
                sleep(1);
            }
        }

        return $votes >= 2;
    }

    // ✅ Fungsi kirim notifikasi Telegram
    protected function sendTelegramNotification($message)
    {
        $token = '7674130624:AAFcKVxe3U6jHbWo7Z3frag7fjgYN-wa02A';
        $chatId = '1220753828';

        try {
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal kirim Telegram: " . $e->getMessage());
        }
    }

    public function handle(): int
    {
        // $this->info("▶️ Mulai pengecekan CCTV Panorama");
        // $this->checkPanoramaStreams();

        // $this->info("⏳ Delay 5 menit sebelum pengecekan sekolah...");
        // sleep(100); // 5 menit

        $this->info("▶️ Mulai pengecekan CCTV sekolah");
        $this->checkSekolahStreams();

        $this->info("✅ Semua pengecekan selesai.");
        return 0;
    }


    protected function checkSekolahStreams()
    {
        $totalCctvs = Sekolah::count();
        $batchSize = 50;
        $processed = 0;
        $batchNumber = 0;

        Sekolah::chunk($batchSize, function ($cctvs) use (&$processed, $totalCctvs, &$batchNumber) {
            $batchNumber++;
            $this->info("▶️ Mulai proses sekolah batch ke-{$batchNumber}");
            $batchStartTime = microtime(true);

            foreach ($cctvs as $cctv) {
                $processed++;
                $streamId = $this->extractStreamId($cctv->link);

                if (!$streamId) {
                    $this->error("❌ {$cctv->namaSekolah}: Format URL tidak valid");
                    continue;
                }

                $possibleUrls = $this->buildStreamUrls($streamId);
                $isOnline = $this->getStatusWithConfidence($streamId, $possibleUrls);
                $newStatus = $isOnline ? 'online' : 'offline';

                if ($newStatus === 'offline') {
                    $message = "🔴 <b>{$cctv->namaSekolah}</b> sedang <b>OFFLINE</b>\n🆔 {$streamId}\n⏰ " . now()->format('d M Y H:i');
                    $this->sendTelegramNotification($message);
                    sleep(1); // hindari rate-limit

                    // ✅ Simpan ke tabel cctv_offline jika belum ada di hari ini
                    $alreadyLogged = CctvOffline::where('link', $cctv->link)
                        ->whereDate('date', today())
                        ->exists();

                    if (!$alreadyLogged) {
                        CctvOffline::create([
                            'namaSekolah' => $cctv->namaSekolah,
                            'wilayah_id' => $cctv->wilayah_id,
                            'namaTitik' => $cctv->namaTitik,
                            'link' => $cctv->link,
                            'last_seen' => $cctv->last_seen,
                            'offline_since' => now(),
                            'date' => today(),
                        ]);
                    }
                }

                $cctv->status = $newStatus;
                $cctv->last_seen = $isOnline ? now() : null;
                $cctv->save();

                $this->info(($isOnline ? '✅' : '❌') . " {$cctv->namaSekolah} " . strtoupper($cctv->status) . " [{$processed}/{$totalCctvs}]");
            }

            $batchProcessingTime = microtime(true) - $batchStartTime;
            $targetDelay = 30; // detik

            $this->info("🕒 Batch sekolah ke-{$batchNumber} diproses dalam {$batchProcessingTime}s");
            $this->info("⏳ Delay {$targetDelay}s dimulai pada " . now()->format('H:i:s'));
            usleep($targetDelay * 1_000_000);
            $this->info("✅ Delay selesai pada " . now()->format('H:i:s'));

        });
    }

    // protected function checkPanoramaStreams()
    // {
    //     $this->info("▶️ Mulai proses pengecekan CCTV Panorama");

    //     $panoramas = Panorama::all();
    //     $totalPanoramas = $panoramas->count();
    //     $processed = 0;

    //     foreach ($panoramas as $panorama) {
    //         $processed++;
    //         $streamId = $this->extractStreamId($panorama->link);

    //         if (!$streamId) {
    //             $this->error("❌ {$panorama->namaTitik}: Format URL tidak valid");
    //             continue;
    //         }

    //         $possibleUrls = $this->buildStreamUrls($streamId);
    //         $isOnline = $this->getStatusWithConfidence($streamId, $possibleUrls);
    //         $newStatus = $isOnline ? 'online' : 'offline';

    //         // Jika offline, bisa tambahkan log atau kirim notifikasi (opsional)
    //         if ($newStatus === 'offline') {
    //             $message = "🔴 <b>{$panorama->namaTitik}</b> (PANORAMA) sedang <b>OFFLINE</b>\n🆔 {$streamId}\n⏰ " . now()->format('d M Y H:i');
    //             $this->sendTelegramNotification($message);
    //             sleep(1); // hindari rate-limit

    //             // Contoh: bisa juga simpan ke tabel panorama_offline jika diperlukan
    //             // PanoramaOffline::create([...]);
    //         }

    //         $panorama->status_panorama = $newStatus;
    //         $panorama->last_seen_panorama = $isOnline ? now() : null;
    //         $panorama->save();

    //         $this->info(($isOnline ? '✅' : '❌') . " {$panorama->namaTitik} " . strtoupper($newStatus) . " [{$processed}/{$totalPanoramas}]");
    //     }

    //     $this->info("✅ Pengecekan CCTV Panorama selesai.");
    // }
}
