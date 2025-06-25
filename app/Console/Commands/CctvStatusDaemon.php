<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CctvStatusDaemon extends Command
{
    protected $signature = 'cctv:status-daemon';
    protected $description = 'Daemon looping cek status CCTV setiap 15 menit';

    public function handle()
    {
        while (true) {
            $this->info('Mulai pengecekan CCTV: ' . now());

            // Panggil command pengecekan utama Anda (atau refactor logic jadi method)
            $this->call('cctv:poll-status');

            $this->info('Selesai pengecekan, tunggu 15 menit...');

            // Delay 5 menit (300 detik)
            sleep(60);
        }
    }
}
