<?php

namespace App\Http\Controllers;

use App\Models\Panorama;
use App\Models\CctvOffline;
use Illuminate\Http\Request;
use App\Models\sekolah;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class sekolahController extends Controller
{

    public function dashboard(Request $request)
    {
        // Count dashboard data
        $sekolahCount = sekolah::count();
        $panoramaCount = Panorama::count();
        $userCount = User::count();

        // Filter rekap offline
        $filter = $request->input('range', 'daily');
        $query = CctvOffline::query();

        if ($filter === 'weekly') {
            $startDate = now()->subWeek()->startOfDay();
        } elseif ($filter === 'monthly') {
            $startDate = now()->subMonth()->startOfDay();
        } else {
            $startDate = today(); // default: daily
        }

        $rekapOffline = CctvOffline::whereDate('date', '>=', $startDate)
            ->orderBy('offline_since', 'desc')
            ->get();

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
            'offlineCCTVs',
            'rekapOffline'
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

    public function login()
    {
        return view('auth.login');
    }
    public function index2()
    {
        return view('admin.panorama');
    }

    public function index3()
    {
        return view('admin.users');
    }

    public function index5()
    {
        return view('admin.rekapsekolah');
    }

    public function panorama()
    {
        $panorama = Panorama::all();
        return view('panorama.panorama', compact('panorama'));
    }

    public function exportPdf(Request $request)
    {
        $range = $request->input('range', 'daily');
        $query = CctvOffline::query();

        if ($range === 'weekly') {
            $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($range === 'monthly') {
            $query->whereMonth('date', now()->month);
        } else {
            $query->whereDate('date', today());
        }

        $rekapOffline = $query->orderBy('offline_since', 'desc')->get();

        // Kirim $range ke view juga
        $pdf = Pdf::loadView('admin.exports.cctv_offline_pdf', [
            'rekapOffline' => $rekapOffline,
            'range' => $range
        ]);

        return $pdf->download('rekap_cctv_offline.pdf');
    }

    public function index4(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'range' => 'sometimes|in:daily,weekly,monthly',
            'wilayah' => 'nullable|string',
            'kategori' => 'nullable|in:SMA,SMK',
            'search' => 'nullable|string|max:255'
        ]);

        $filterRange = $validated['range'] ?? 'daily';
        $wilayah = $validated['wilayah'] ?? null;
        $kategori = strtolower($validated['kategori'] ?? '');
        $search = $validated['search'] ?? null;

        // Query dasar
        $query = CctvOffline::query();

        // Filter range waktu
        $this->applyDateFilter($query, $filterRange);

        // Filter wilayah
        if ($wilayah) {
            $query->where('wilayah', $wilayah);
        }

        // Filter kategori
        if ($kategori === 'sma') {
            $query->where('namaSekolah', 'like', 'SMA%');
        } elseif ($kategori === 'smk') {
            $query->where('namaSekolah', 'like', 'SMK%');
        }

        // Search sekolah
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('namaSekolah', 'like', '%' . $search . '%')
                    ->orWhere('namaTitik', 'like', '%' . $search . '%');
            });
        }

        // Pagination dengan query string
        $rekapOffline = $query->orderBy('last_seen', 'desc')
            ->paginate(10)
            ->appends($request->query());

        // Data statistik
        $stats = $this->getDashboardStats();

        return view('admin.dashboard', array_merge(
            [
                'rekapOffline' => $rekapOffline,
                'filterRange' => $filterRange,
                'wilayah' => $wilayah,
                'kategori' => $kategori,
                'search' => $search
            ],
            $stats
        ));
    }

    /**
     * Apply date range filter to query
     */
    protected function applyDateFilter($query, $range)
    {
        switch ($range) {
            case 'weekly':
                $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'monthly':
                $query->whereYear('date', now()->year)
                    ->whereMonth('date', now()->month);
                break;
            default: // daily
                $query->whereDate('date', today());
        }
    }

    /**
     * Get dashboard statistics
     */
    protected function getDashboardStats()
    {
        return [
            'sekolahCount' => \App\Models\Sekolah::count(),
            'panoramaCount' => \App\Models\Panorama::count(),
            'userCount' => \App\Models\User::count(),

            'sekolahMessage' => \App\Models\Sekolah::whereDate('updated_at', today())->exists()
                ? 'Ada perubahan hari ini'
                : 'Tidak ada perubahan hari ini',

            'panoramaMessage' => \App\Models\Panorama::whereDate('updated_at', today())->exists()
                ? 'Ada perubahan hari ini'
                : 'Tidak ada perubahan hari ini',

            'userMessage' => \App\Models\User::whereDate('updated_at', today())->exists()
                ? 'Ada perubahan hari ini'
                : 'Tidak ada perubahan hari ini',

            'totalOffline' => \App\Models\Sekolah::where('status', 'offline')->count() +
                \App\Models\Panorama::where('status_panorama', 'offline')->count(),

            'offlineMessage' => (\App\Models\Sekolah::where('status', 'offline')->exists() ||
                \App\Models\Panorama::where('status_panorama', 'offline')->exists())
                ? 'Ada perangkat offline'
                : 'Semua perangkat online',

            'offlineCCTVs' => collect()
                ->merge(\App\Models\Sekolah::where('status', 'offline')
                    ->select('namaSekolah', 'last_seen as last_seen_panorama')
                    ->get())
                ->merge(\App\Models\Panorama::where('status_panorama', 'offline')
                    ->select('namaTitik', 'last_seen_panorama')
                    ->get())
        ];
    }
}
