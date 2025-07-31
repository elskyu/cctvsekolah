<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GlobalResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah;
use App\Models\NamaSekolah;
use Illuminate\Support\Facades\Validator;

class ApiSekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sekolah = Sekolah::all();

            if ($sekolah->isEmpty()) {
                return new GlobalResource(false, 'Data Sekolah tidak ditemukan', null);
            }

            return new GlobalResource(true, 'List Data Sekolah', $sekolah);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan: ' . $e->getMessage(), null);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'wilayah_id' => 'required|string|max:255',
                'namaTitik' => 'required|string|max:255',
                'lokasi' => 'required|string|max:255',
                'link' => 'required|url',
                'nama_sekolah_id' => 'required|integer|exists:nama_sekolah,id',
            ]);

            if ($validator->fails()) {
                return new GlobalResource(false, 'Validasi gagal', $validator->errors());
            }

            // Ambil data sekolah dari relasi nama_sekolah
            $namaSekolahModel = NamaSekolah::find($request->nama_sekolah_id);
            if (!$namaSekolahModel) {
                return new GlobalResource(false, 'Nama sekolah tidak ditemukan', null);
            }

            // Siapkan data dengan namaSekolah diisi dari relasi
            $data = [
                'wilayah_id' => $request->wilayah_id,
                'nama_sekolah_id' => $request->nama_sekolah_id,
                'namaSekolah' => $namaSekolahModel->nama,
                'namaTitik' => $request->namaTitik,
                'lokasi' => $request->lokasi,
                'link' => $request->link,
            ];

            $sekolah = Sekolah::create($data);

            return new GlobalResource(true, 'Data sekolah berhasil ditambahkan', $sekolah);
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
            $data = sekolah::find($id);

            if (!$data) {
                return new GlobalResource(false, 'Data sekolah tidak ditemukan', null);
            }

            return new GlobalResource(true, 'Detail Data Sekolah', $data);
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
            $data = sekolah::find($id);

            if (!$data) {
                return new GlobalResource(false, 'Data sekolah tidak ditemukan', null);
            }

            $validator = Validator::make($request->all(), [
                'wilayah_id' => 'required|string|max:255',
                'namaSekolah' => 'string|max:255',
                'namaTitik' => 'required|string|max:255',
                'lokasi' => 'required|string|max:255',
                'link' => 'required|url',
                'nama_sekolah_id' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return new GlobalResource(false, 'Validasi gagal', $validator->errors());
            }

            $data->update($request->all());

            return new GlobalResource(true, 'Data sekolah berhasil diupdate', $data);
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
            $data = sekolah::find($id);

            if (!$data) {
                return new GlobalResource(false, 'Data sekolah tidak ditemukan', null);
            }

            $data->delete();

            return new GlobalResource(true, 'Data sekolah berhasil dihapus', null);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan: ' . $e->getMessage(), null);
        }
    }

    public function getNamaSekolah()
    {
        $sekolah = \App\Models\NamaSekolah::select('id', 'nama', 'lokasi', 'wilayah_id')->get();

        return response()->json([
            'success' => true,
            'data' => $sekolah,
        ]);
    }

}
