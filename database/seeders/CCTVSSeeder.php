<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CCTVSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [14, 'KOMINFO SLEMAN 1', 'Bundaran UGM 10', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=BundaranUGM1', '2024-11-21 07:17:36', '2025-02-10 18:28:10'],
            [15, 'KOMINFO SLEMAN 1', 'Bundaran UGM 2', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=BundaranUGM2', '2024-11-21 07:18:18', '2024-11-21 07:18:18'],
            [16, 'KOMINFO SLEMAN 1', 'Bundaran UGM 3', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=BundaranUGM3', '2024-11-21 07:18:52', '2024-11-21 07:18:52'],
            [17, 'KOMINFO SLEMAN 1', 'Flyofer Jombor B', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=FlyOverJomborBarat', '2024-11-21 07:19:49', '2024-11-21 07:20:38'],
            [18, 'KOMINFO SLEMAN 1', 'Flyofer Jombor T', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=FlyOverJomborTimur', '2024-11-21 07:20:26', '2024-11-21 07:20:26'],
            [19, 'KOMINFO SLEMAN 1', 'Flyofer Jombor U', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=FlyOverJomborUtara', '2024-11-21 07:21:14', '2024-11-21 07:21:14'],
            [20, 'KOMINFO SLEMAN 1', 'Perempatan Beran 1', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanBeran1', '2024-11-21 07:21:49', '2024-11-21 07:21:49'],
            [21, 'KOMINFO SLEMAN 1', 'Perempetan CondongCatur', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanCondongcatur', '2024-11-21 07:22:57', '2024-11-21 07:22:57'],
            [22, 'KOMINFO SLEMAN 1', 'Perempatan Demak Ijo 1', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanDemakIjo1', '2024-11-21 07:23:31', '2024-11-21 07:23:31'],
            [23, 'KOMINFO SLEMAN 1', 'Perempatan Demak Ijo 2', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanDemakIjo2', '2024-11-21 07:24:47', '2024-11-21 07:24:47'],
            [24, 'KOMINFO SLEMAN 1', 'Perempatan Demak Ijo 3', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanDemakIjo3', '2024-11-21 07:25:25', '2024-11-21 07:25:25'],
            [25, 'KOMINFO SLEMAN 1', 'Perempatan Godean 1', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanGodean1', '2024-11-21 07:26:12', '2024-11-21 07:26:12'],
            [26, 'KOMINFO SLEMAN 1', 'Perempatan Godean 2', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanGodean2', '2024-11-21 07:26:52', '2024-11-21 07:26:52'],
            [27, 'KOMINFO SLEMAN 1', 'Perempatan Kronggahan', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanKronggahan', '2024-11-21 07:27:48', '2024-11-21 07:27:48'],
            [28, 'KOMINFO SLEMAN 1', 'Perempatan Mojolali', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanMonjali', '2024-11-21 07:28:45', '2024-11-21 07:28:45'],
            [29, 'KOMINFO SLEMAN 2', 'Perempatan Munggur 1', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanMunggur1', '2024-11-21 07:34:18', '2024-11-21 08:03:20'],
            [30, 'KOMINFO SLEMAN 2', 'Perempatan Munggur 2', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanMunggur2', '2024-11-21 07:35:40', '2024-11-21 07:35:40'],
            [31, 'KOMINFO SLEMAN 2', 'Perempatan Pelemgurih 1', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanPelemgurih1', '2024-11-21 08:03:08', '2024-11-21 08:03:08'],
            [32, 'KOMINFO SLEMAN 2', 'Perempatan Pelemgurih 2', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanPelemgurih2', '2024-11-21 08:04:17', '2024-11-21 08:04:17'],
            [33, 'KOMINFO SLEMAN 2', 'Perempatan Pelemgurih 3', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanPelemgurih3', '2024-11-21 08:05:12', '2024-11-21 08:05:12'],
            [34, 'KOMINFO SLEMAN 2', 'Perempatan Seyegan', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanSeyegan', '2024-11-21 08:06:03', '2024-11-21 08:06:03'],
            [35, 'KOMINFO SLEMAN 2', 'Perempatan Tempel 1', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanTempel1', '2024-11-21 08:06:48', '2024-11-21 08:06:48'],
            [36, 'KOMINFO SLEMAN 2', 'Perempatan Tempel 2', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanTempel2', '2024-11-21 08:07:37', '2024-11-21 08:07:37'],
            [37, 'KOMINFO SLEMAN 2', 'Perempatan UPN 1', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanUPN1', '2024-11-21 08:08:29', '2024-11-21 08:08:29'],
            [38, 'KOMINFO SLEMAN 2', 'Perempatan UPN 2', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PerempatanUPN2', '2024-11-21 08:09:13', '2024-11-21 08:09:25'],
            [39, 'KOMINFO SLEMAN 2', 'Pertigaan Bantulan 1', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PertigaanBantulan1', '2024-11-21 08:10:05', '2024-11-21 08:10:05'],
            [40, 'KOMINFO SLEMAN 2', 'Pertigaan Bantulan 2', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PertigaanBantulan2', '2024-11-21 08:10:38', '2024-11-21 08:10:38'],
            [41, 'KOMINFO SLEMAN 2', 'Pertigaan Maguwo 1', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PertigaanMaguwo1', '2024-11-21 08:11:26', '2024-11-21 08:11:26'],
            [42, 'KOMINFO SLEMAN 2', 'Pertigaan Maguwo 2', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PertigaanMaguwo2', '2024-11-21 08:12:01', '2024-11-21 08:12:01'],
            [43, 'KOMINFO SLEMAN 2', 'Pertigaan Maguwo 3', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PertigaanMaguwo3', '2024-11-21 08:12:42', '2024-11-21 08:12:42'],
            [44, 'KOMINFO SLEMAN 3', 'Pertigaan Minggir', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PertigaanMinggir', '2024-11-21 17:51:18', '2024-11-21 17:51:18'],
            [45, 'KOMINFO SLEMAN 3', 'Pertigaan Pasar Gamping', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PertigaanPasarGamping', '2024-11-21 17:51:46', '2024-11-21 17:51:46'],
            [46, 'KOMINFO SLEMAN 3', 'Pertigaan Pasar Prambanan', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PertigaanPasarPrambanan', '2024-11-21 17:52:29', '2024-11-21 17:52:29'],
            [47, 'KOMINFO SLEMAN 3', 'Pertigaan UIN', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PertigaanUIN', '2024-11-21 17:53:11', '2024-11-21 17:53:11'],
            [48, 'KOMINFO SLEMAN 3', 'Pos Polisi Jombor', 'http://103.255.15.227:5080/CCTVKOMINFOSLEMAN/play.html?id=PosPolisiJombor', '2024-11-21 17:53:53', '2024-11-21 17:53:53'],
            [49, 'Bantul 1', 'Makam Imogiri', 'http://103.255.15.227:5080/CCTVBANTUL/play.html?id=BarcodeBordessMakamImogiri', '2024-11-24 10:09:27', '2024-11-28 19:00:24'],
            [50, 'Sekolah', 'SMK2', 'rtmp://103.255.15.222:1935/cctv-sekolah/cctv_smk2yk_a106.stream', '2025-02-10 18:16:23', '2025-02-10 18:16:23'],
            [52, 'Sekolah22', 'CCTV_Sekolah', 'rtmp://103.255.15.222:1935/cctv-sekolah/cctv_smk2yk_tiangbendera.stream', '2025-02-10 18:34:02', '2025-02-10 18:34:02'],
            [53, 'SMK1Yogyakarta', 'CCTV_Aula1', 'http://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula', '2025-02-10 19:02:58', '2025-02-10 19:02:58'],
            [54, 'SMK1Yogyakarta', 'CCTV_Aula2', 'http://103.255.15.227:5080/CCTVSEKOLAH/play.html?id=cctv_smk1yk_aula2', '2025-02-10 19:03:14', '2025-02-10 19:03:14'],
        ];

        foreach ($data as $item) {
            DB::table('cctvs')->insert([
                'id' => $item[0],
                'wilayah_id' => $item[1],
                'namaTitik' => $item[2],
                'link' => $item[3],
                'created_at' => Carbon::parse($item[4]),
                'updated_at' => Carbon::parse($item[5]),
            ]);
        }
    }
}
