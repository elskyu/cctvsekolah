<?php

namespace App\Http\Controllers;

use App\Models\Panorama;
use Illuminate\Http\Request;
use App\Models\sekolah;
use App\Models\User;

class sekolahController extends Controller
{
    public function dashboard()
    {
        // Count dashboard data
        $sekolahCount = sekolah::count();
        $panoramaCount = Panorama::count();
        $userCount = User::count();

        $sekolahTodayCount = sekolah::whereDate('created_at', today())->orWhereDate('updated_at', today())->count();

        $panoramaTodayCount = Panorama::whereDate('created_at', today())->orWhereDate('updated_at', today())->count();

        $userTodayCount = User::whereDate('created_at', today())->orWhereDate('updated_at', today())->count();

        // Tentukan pesan perubahan untuk setiap kategori
        $sekolahMessage = $sekolahTodayCount > 0 ? 'Ada perubahan hari ini' : 'Tidak ada perubahan hari ini';
        $panoramaMessage = $panoramaTodayCount > 0 ? 'Ada perubahan hari ini' : 'Tidak ada perubahan hari ini';
        $userMessage = $userTodayCount > 0 ? 'Ada perubahan hari ini' : 'Tidak ada perubahan hari ini';

        // Hitung total CCTV offline (Sekolah + Panorama)
        $totalOfflineSekolah = Sekolah::where('status', 'offline')->count();
        $totalOfflinePanorama = Panorama::where('status_panorama', 'offline')->count();
        $totalOffline = $totalOfflineSekolah + $totalOfflinePanorama;

        $offlineMessage = $totalOffline > 0 ? 'Ada perubahan hari ini' : 'Tidak ada perubahan hari ini';


        // Ambil detail CCTV offline Sekolah
        $offlineSekolah = Sekolah::where('status', 'offline')
            ->select('id', 'namaSekolah', 'last_seen')
            ->orderBy('namaSekolah')
            ->get();

        // Ambil detail CCTV offline Panorama
        $offlinePanorama = Panorama::where('status_panorama', 'offline')
            ->select('id', 'namaTitik', 'last_seen_panorama')
            ->orderBy('namaTitik')
            ->get();

        // Gabungkan detail offline Sekolah dan Panorama (jika ingin)
        $offlineCCTVs = collect()
            ->merge($offlineSekolah)
            ->merge($offlinePanorama);

        return view('admin.dashboard', compact(
            'sekolahCount',
            'panoramaCount',
            'userCount',
            'sekolahMessage',
            'panoramaMessage',
            'userMessage',
            'offlineMessage',
            'totalOffline',
            'offlineCCTVs'
        ));
    }

    public function cctvsekolah()
    {
        $sekolah = sekolah::all();
        return view('sekolah.sekolah', compact('sekolah'));
    }

    public function index()
    {
        return view('admin.sekolah');
    }
    public function index2()
    {
        return view('admin.panorama');
    }

    public function index3()
    {
        return view('admin.users');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function panorama()
    {
        $panorama = Panorama::all();
        return view('panorama.panorama', compact('panorama'));
    }
}
