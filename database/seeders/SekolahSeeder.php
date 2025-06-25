<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sekolah')->insert([
            [
                'wilayah_id' => 'Bantul',
                'namaSekolah' => 'SMKN 1 Yogyakarta',
                'namaTitik' => 'CCTV_Sekolah11111 11111 11111 111111',
                'link' => 'http://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula',
                'created_at' => '2025-02-12 20:10:54',
                'updated_at' => '2025-02-14 21:27:10',
            ],
            [
                'wilayah_id' => 'Bantul',
                'namaSekolah' => 'SMKN 1 Yogyakarta',
                'namaTitik' => 'CCTV_Sekolah12',
                'link' => 'http://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula2',
                'created_at' => '2025-02-12 20:14:56',
                'updated_at' => '2025-02-12 20:14:56',
            ],
            [
                'wilayah_id' => 'Sleman',
                'namaSekolah' => 'SMKN 1 Yogyakarta',
                'namaTitik' => 'CCTV_Sekolah13',
                'link' => 'http://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula3',
                'created_at' => '2025-02-12 21:31:27',
                'updated_at' => '2025-02-16 20:45:24',
            ],
            [
                'wilayah_id' => 'Bantul',
                'namaSekolah' => 'SMKN 1 Jogjakarta',
                'namaTitik' => 'SMK2 2222222222 222222222222 22222222222 2222222222 22222',
                'link' => 'rtmp://103.255.15.222:1935/cctv-sekolah/cctv_smk2yk_a106.stream',
                'created_at' => '2025-02-12 21:35:17',
                'updated_at' => '2025-02-13 02:04:08',
            ],
            [
                'wilayah_id' => 'Imogiri',
                'namaSekolah' => 'SMA 1 Yogyakarta',
                'namaTitik' => 'SMK23',
                'link' => 'http://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula34',
                'created_at' => '2025-02-13 21:14:20',
                'updated_at' => '2025-02-16 18:41:21',
            ],
            [
                'wilayah_id' => 'Bantul',
                'namaSekolah' => 'SMA 7 Bantul',
                'namaTitik' => 'CCTV Lapangan',
                'link' => 'ttp://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula3',
                'created_at' => '2025-02-16 19:39:33',
                'updated_at' => '2025-02-16 19:39:33',
            ],
            [
                'wilayah_id' => 'Bantul',
                'namaSekolah' => 'SMA 7 Bantul',
                'namaTitik' => 'CCTV AULA',
                'link' => 'ttp://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aulaaula',
                'created_at' => '2025-02-16 19:40:02',
                'updated_at' => '2025-02-16 19:40:02',
            ],
            [
                'wilayah_id' => 'Bantul',
                'namaSekolah' => 'SMA 7 Bantul',
                'namaTitik' => 'CCTV Kantin',
                'link' => 'ttp://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aulakantin',
                'created_at' => '2025-02-16 19:40:26',
                'updated_at' => '2025-02-16 19:40:26',
            ],
            [
                'wilayah_id' => 'Bantul',
                'namaSekolah' => 'SMA 7 Bantul',
                'namaTitik' => 'CCTV Parkiran',
                'link' => 'ttp://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aulaparkiran',
                'created_at' => '2025-02-16 19:40:54',
                'updated_at' => '2025-02-16 19:40:54',
            ],
            [
                'wilayah_id' => 'Imogiri',
                'namaSekolah' => 'SMP 1 Yogyakarta',
                'namaTitik' => 'Kelas 7F',
                'link' => 'ttp://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula45',
                'created_at' => '2025-02-19 23:46:13',
                'updated_at' => '2025-02-19 23:46:13',
            ],
            [
                'wilayah_id' => 'KOTA JOGJA',
                'namaSekolah' => 'SMA 7 Bantul',
                'namaTitik' => 'CCTV_Sekolah100',
                'link' => 'http://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula5',
                'created_at' => '2025-02-21 00:11:23',
                'updated_at' => '2025-02-21 00:11:23',
            ],
            [
                'wilayah_id' => 'KABUPATEN SLEMAN',
                'namaSekolah' => 'SMKN 1 Sleman',
                'namaTitik' => 'CCTV_Sekolah17',
                'link' => 'http://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula209',
                'created_at' => '2025-02-21 00:12:10',
                'updated_at' => '2025-02-21 00:12:10',
            ],
            [
                'wilayah_id' => 'KABUPATEN GK',
                'namaSekolah' => 'SMKN 2 Yogyakarta',
                'namaTitik' => 'CCTV_Sekolah4',
                'link' => 'http://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula38',
                'created_at' => '2025-02-21 00:12:59',
                'updated_at' => '2025-02-21 00:12:59',
            ],
            [
                'wilayah_id' => 'KOTA JOGJA',
                'namaSekolah' => 'SMKN 1 Yogyakarta',
                'namaTitik' => 'CCTV_Sekolah1',
                'link' => 'tp://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula',
                'created_at' => '2025-02-21 02:25:06',
                'updated_at' => '2025-02-21 02:25:06',
            ],
            [
                'wilayah_id' => 'KOTA JOGJA',
                'namaSekolah' => 'SMP 7 Bantul',
                'namaTitik' => 'CCTV_Sekolah1',
                'link' => 'gf',
                'created_at' => '2025-02-21 02:33:33',
                'updated_at' => '2025-02-21 02:33:33',
            ],
        ]);
    }
}
