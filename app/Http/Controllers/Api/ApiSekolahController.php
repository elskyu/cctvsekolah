<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GlobalResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\sekolah;
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
                'namaWilayah'  => 'required|string|max:255',
                'namaSekolah'  => 'required|string|max:255',
                'namaTitik'    => 'required|string|max:255',
                'link'         => 'required|url',
            ]);

            if ($validator->fails()) {
                return new GlobalResource(false, 'Validasi gagal', $validator->errors());
            }

            $sekolah = sekolah::create($request->all());

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
                'namaWilayah'  => 'required|string|max:255',
                'namaSekolah'  => 'required|string|max:255',
                'namaTitik'    => 'required|string|max:255',
                'link'         => 'required|url',
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
}
