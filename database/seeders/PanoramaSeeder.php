<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PanoramaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [1, 'KOMINFO SLEMAN 1', 'Bundaran UGM 10', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=BundaranUGM1', '2024-11-21 07:17:36', '2025-02-10 18:28:10'],
            [2, 'KOMINFO SLEMAN 1', 'Bundaran UGM 2', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=BundaranUGM2', '2024-11-21 07:18:18', '2024-11-21 07:18:18'],
            [3, 'KOMINFO SLEMAN 1', 'Bundaran UGM 3', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=BundaranUGM3', '2024-11-21 07:18:52', '2024-11-21 07:18:52'],
        ];

        foreach ($data as $item) {
            DB::table('panorama')->insert([
                'id' => $item[0],
                'namaWilayah' => $item[1],
                'namaTitik' => $item[2],
                'link' => $item[3],
                'created_at' => Carbon::parse($item[4]),
                'updated_at' => Carbon::parse($item[5]),
            ]);
        }
    }
}
