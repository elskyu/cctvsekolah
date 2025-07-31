<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CctvStatusDaemon extends Command
{
    protected $signature = 'cctv:status-daemon';
    protected $description = 'Daemon looping cek status CCTV setiap 5 menit';

    public function handle()
    {
        while (true) {
            $this->info('Mulai pengecekan CCTV: ' . now());

            // Panggil command pengecekan utama Anda (atau refactor logic jadi method)
            $this->call('cctv:poll-status');

            $this->info('Selesai pengecekan, tunggu 5 menit...');
        }
    }
}
