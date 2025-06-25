<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Sekolah;
use App\Models\Panorama;
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
        // Tambahkan stream panorama lain di sini
    ];

    /**
     * Extract stream ID dari URL CCTV
     */
    protected function extractStreamId(string $url): ?string
    {
        $query = [];
        parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);
        return $query['id'] ?? null;
    }

    /**
     * Bangun daftar URL streaming - sekarang seragam untuk semua jenis CCTV
     */
    protected function buildStreamUrls(string $streamId): array
    {
        // Format URL yang sama untuk semua jenis CCTV
        return [
            "http://103.255.15.227:5080/live/{$streamId}/playlist.m3u8",
            "http://103.255.15.227:5080/hls/{$streamId}.m3u8",
            "http://103.255.15.227:5080/CCTVSEKOLAH/streams/{$streamId}.m3u8",
            "http://103.255.15.227:5080/CCTVPUBLIC/streams/{$streamId}.m3u8",
        ];
    }

    /**
     * Timeout dinamis - sekarang lebih sederhana
     */
    protected function getDynamicTimeout(string $streamId): int
    {
        return in_array($streamId, $this->longTimeoutStreams) ? 20 : 15;
    }

    /**
     * Metode pengecekan yang sekarang seragam untuk semua CCTV
     */
    protected function isStreamPlayable(string $url, string $streamId): bool
    {
        try {
            $response = Http::timeout($this->getDynamicTimeout($streamId))
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
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

    public function handle(): int
    {
        $this->info("Mulai pengecekan CCTV sekolah");
        $this->checkSekolahStreams();

        $this->info("Mulai pengecekan CCTV panorama");
        $this->checkPanoramaStreams();

        $this->info("Pengecekan CCTV selesai.");
        return 0;
    }

    protected function checkSekolahStreams()
    {
        $totalCctvs = Sekolah::count();
        $batchSize = 25;
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

                $cctv->status = $isOnline ? 'online' : 'offline';
                $cctv->last_seen = $isOnline ? now() : null;
                $cctv->save();

                $this->info(($isOnline ? '✅' : '❌') . " {$cctv->namaSekolah} " . strtoupper($cctv->status) . " [{$processed}/{$totalCctvs}]");
            }

            $batchProcessingTime = microtime(true) - $batchStartTime;
            $remainingDelay = max(0, 3 - $batchProcessingTime); // delay 3 detik
            if ($remainingDelay > 0) {
                $this->info("⏳ Batch sekolah ke-{$batchNumber} selesai, delay {$remainingDelay}s sebelum batch berikutnya.");
                usleep($remainingDelay * 1_000_000);
            } else {
                $this->info("✔️ Batch sekolah ke-{$batchNumber} selesai, lanjut tanpa delay.");
            }
        });
    }

    protected function checkPanoramaStreams()
    {
        $totalPanoramas = Panorama::count();
        $batchSize = 10;
        $processed = 0;
        $batchNumber = 0;

        Panorama::chunk($batchSize, function ($panoramas) use (&$processed, $totalPanoramas, &$batchNumber) {
            $batchNumber++;
            $this->info("▶️ Mulai proses panorama batch ke-{$batchNumber}");
            $batchStartTime = microtime(true);

            foreach ($panoramas as $panorama) {
                $processed++;
                $streamId = $this->extractStreamId($panorama->link);

                if (!$streamId) {
                    $this->error("❌ Panorama {$panorama->namaTitik}: Format URL tidak valid");
                    continue;
                }

                $possibleUrls = $this->buildStreamUrls($streamId);
                $isOnline = $this->getStatusWithConfidence($streamId, $possibleUrls);

                $panorama->status_panorama = $isOnline ? 'online' : 'offline';
                $panorama->last_seen_panorama = $isOnline ? now() : null;
                $panorama->save();

                $this->info(($isOnline ? '✅' : '❌') . " Panorama {$panorama->namaTitik} " . strtoupper($panorama->status_panorama) . " [{$processed}/{$totalPanoramas}]");
            }

            $batchProcessingTime = microtime(true) - $batchStartTime;
            $remainingDelay = max(0, 3 - $batchProcessingTime); // delay 3 detik
            if ($remainingDelay > 0) {
                $this->info("⏳ Batch panorama ke-{$batchNumber} selesai, delay {$remainingDelay}s sebelum batch berikutnya.");
                usleep($remainingDelay * 1_000_000);
            } else {
                $this->info("✔️ Batch panorama ke-{$batchNumber} selesai, lanjut tanpa delay.");
            }
        });
    }
}
