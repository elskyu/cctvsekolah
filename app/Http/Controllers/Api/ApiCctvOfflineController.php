<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CctvOffline;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ApiCctvOfflineController extends Controller
{
    // GET /api/cctv-offline
    public function index()
    {
        $data = CctvOffline::orderBy('offline_since', 'desc')
            ->orderBy('date', 'desc') // urutan kedua jika waktu offline sama
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List data CCTV offline',
            'data' => $data
        ]);
    }

    // POST /api/cctv-offline
    public function store(Request $request)
    {
        $validated = $request->validate([
            'namaSekolah' => 'required|string',
            'namaTitik' => 'required|string',
            'link' => 'required|url',
            'last_seen' => 'required|date',
            'offline_since' => 'nullable|date',
            'date' => 'required|date',
            'wilayah_id' => 'sometimes|integer', // Asumsi wilayah_id adalah ID wilayah
        ]);

        $data = CctvOffline::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data CCTV offline berhasil ditambahkan',
            'data' => $data
        ]);
    }

    // GET /api/cctv-offline/{id}
    public function show($id)
    {
        $data = CctvOffline::find($id);

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $data]);
    }

    // PUT /api/cctv-offline/{id}
    public function update(Request $request, $id)
    {
        $data = CctvOffline::find($id);

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'namaSekolah' => 'sometimes|string',
            'namaTitik' => 'sometimes|string',
            'link' => 'sometimes|url',
            'last_seen' => 'sometimes|date',
            'offline_since' => 'nullable|date',
            'date' => 'sometimes|date',
            'wilayah_id' => 'sometimes|integer', // Asumsi wilayah_id adalah ID wilayah
        ]);

        $data->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data CCTV offline berhasil diperbarui',
            'data' => $data
        ]);
    }

    // DELETE /api/cctv-offline/{id}
    public function destroy($id)
    {
        $data = CctvOffline::find($id);

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data CCTV offline berhasil dihapus'
        ]);
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

        $pdf = Pdf::loadView('admin.exports.cctv_offline_pdf', [
            'rekapOffline' => $rekapOffline,
            'range' => $range
        ]);

        return $pdf->download('rekap_cctv_offline.pdf');
    }

}
