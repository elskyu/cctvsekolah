<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GlobalResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Panorama;
use Illuminate\Support\Facades\Validator;

class ApiPanoramaController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        try {
            $panorama = Panorama::all();

            if ($panorama->isEmpty()) {
                return new GlobalResource(false, 'Data Panorama tidak ditemukan', null);
            }

            return new GlobalResource(true, 'List Data Panorama', $panorama);
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
                'namaTitik'    => 'required|string|max:255',
                'link'         => 'required|url',
            ]);

            if ($validator->fails()) {
                return new GlobalResource(false, 'Validasi gagal', $validator->errors());
            }

            $panorama = Panorama::create($request->all());

            return new GlobalResource(true, 'Data Panorama berhasil ditambahkan', $panorama);
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
            $panorama = Panorama::find($id);

            if (!$panorama) {
                return new GlobalResource(false, 'Data Panorama tidak ditemukan', null);
            }

            return new GlobalResource(true, 'Detail Data Panorama', $panorama);
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
            $panorama = Panorama::find($id);

            if (!$panorama) {
                return new GlobalResource(false, 'Data Panorama tidak ditemukan', null);
            }

            $validator = Validator::make($request->all(), [
                'namaWilayah'  => 'required|string|max:255',
                'namaTitik'    => 'required|string|max:255',
                'link'         => 'required|url',
            ]);

            if ($validator->fails()) {
                return new GlobalResource(false, 'Validasi gagal', $validator->errors());
            }

            $panorama->update($request->all());

            return new GlobalResource(true, 'Data Panorama berhasil diupdate', $panorama);
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
            $panorama = Panorama::find($id);

            if (!$panorama) {
                return new GlobalResource(false, 'Data Panorama tidak ditemukan', null);
            }

            $panorama->delete();

            return new GlobalResource(true, 'Data Panorama berhasil dihapus', null);
        } catch (\Exception $e) {
            return new GlobalResource(false, 'Terjadi kesalahan: ' . $e->getMessage(), null);
        }
    }
}
