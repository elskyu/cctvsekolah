<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NamaSekolah;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\GlobalResource;

class ApiNamaSekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Controller Laravel
    public function index()
    {
        $data = NamaSekolah::withCount([
            'sekolah as jumlah_namaTitik' => function ($query) {
                $query->whereNotNull('namaTitik')->where('namaTitik', '!=', '');
            }
        ])->get();

        return response()->json([
            'success' => true,
            'message' => 'List Nama Sekolah',
            'data' => $data
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'wilayah_id' => 'required|string|max:255',
                'nama' => 'required|string|max:255',
                'lokasi' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return new GlobalResource(false, 'Validasi gagal', $validator->errors());
            }

            $data = NamaSekolah::create($request->all());

            return new GlobalResource(true, 'Data nama sekolah berhasil ditambahkan', $data);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan: ' . $e->getMessage(), null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = NamaSekolah::find($id);

            if (!$data) {
                return new GlobalResource(false, 'Data nama sekolah tidak ditemukan', null);
            }

            return new GlobalResource(true, 'Detail Nama Sekolah', $data);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = NamaSekolah::find($id);

            if (!$data) {
                return new GlobalResource(false, 'Data nama sekolah tidak ditemukan', null);
            }

            $validator = Validator::make($request->all(), [
                'wilayah_id' => 'required|string|max:255',
                'nama' => 'required|string|max:255',
                'lokasi' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return new GlobalResource(false, 'Validasi gagal', $validator->errors());
            }

            $data->update($request->all());

            return new GlobalResource(true, 'Data nama sekolah berhasil diupdate', $data);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan: ' . $e->getMessage(), null);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = NamaSekolah::find($id);

            if (!$data) {
                return new GlobalResource(false, 'Data nama sekolah tidak ditemukan', null);
            }

            $data->delete();

            return new GlobalResource(true, 'Data nama sekolah berhasil dihapus', null);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan: ' . $e->getMessage(), null);
        }
    }

    public function getByWilayah($wilayah_id)
    {
        try {
            $sekolah = \App\Models\NamaSekolah::where('wilayah_id', $wilayah_id)
                ->select('id', 'nama', 'lokasi')
                ->get();

            if ($sekolah->isEmpty()) {
                return new GlobalResource(false, 'Data sekolah tidak ditemukan', null);
            }

            return new GlobalResource(true, 'List sekolah berdasarkan wilayah', $sekolah);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan: ' . $e->getMessage(), null);
        }
    }

}
