<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\sekolahController;
use App\Http\Controllers\Api\ApiSekolahController;
use App\Http\Controllers\Api\ApiPanoramaController;
use App\Http\Controllers\Api\ApiNamaSekolahController;
use App\Http\Controllers\Api\ApiUsersController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\Api\ApiCctvOfflineController;

Route::apiResource('cctv-offline', ApiCctvOfflineController::class);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rekap PDF export (optional public)
Route::get('/export-cctv-offline', [sekolahController::class, 'exportPdf'])->name('export.cctv.offline');
Route::get('/dashboard', [sekolahController::class, 'index4'])->name('cctv.offline.index');
Route::get('/rekap-cctv-offline', [sekolahController::class, 'index4'])->name('cctv.offline.index');
Route::get('/export/cctv-offline', [sekolahController::class, 'exportPdf'])->name('cctv.offline.index');
Route::get('/export/cctv-offline', [ApiCctvOfflineController::class, 'exportPdf']);



Route::get('/jumlah-wilayah', [WilayahController::class, 'jumlahWilayah']);
Route::get('/ajax/cctv-offline', [sekolahController::class, 'fetchOfflineData'])->name('ajax.cctv.offline');


// -----------------------------
// 🔐 AUTHENTICATION ROUTES
// -----------------------------

// Login endpoint (no auth needed)
Route::post('/login', [ApiAuthController::class, 'login']);

// -----------------------------
// 🔐 PROTECTED ROUTES (need token Sanctum)
// -----------------------------

Route::middleware('auth:sanctum')->group(function () {

    // CCTV Sekolah (Read only)
    Route::get('/cctv-sekolah', [ApiSekolahController::class, 'index']);
    Route::get('/cctv-sekolah/{id}', [ApiSekolahController::class, 'show']);

    // CCTV Panorama (Read only)
    Route::get('/cctv-panorama', [ApiPanoramaController::class, 'index']);
    Route::get('/cctv-panorama/{id}', [ApiPanoramaController::class, 'show']);

    // 🔐 User Info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 🔓 Logout
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // 📡 Admin Dashboard View
    Route::get('/admin/sekolah', [sekolahController::class, 'index4'])->name('admin.sekolah');
    Route::get('/admin/rekapsekolah', [sekolahController::class, 'index5'])->name('admin.rekapsekolah');

    // 📡 CCTV Sekolah (Create, Update, Delete)
    Route::post('/cctv-sekolah', [ApiSekolahController::class, 'store']);
    Route::put('/cctv-sekolah/{id}', [ApiSekolahController::class, 'update']);
    Route::get('/nama-sekolah', [ApiSekolahController::class, 'getNamaSekolah']);
    Route::delete('/cctv-sekolah/{id}', [ApiSekolahController::class, 'destroy']);

    // 📷 CCTV Panorama (Create, Update, Delete)
    Route::post('/cctv-panorama', [ApiPanoramaController::class, 'store']);
    Route::put('/cctv-panorama/{id}', [ApiPanoramaController::class, 'update']);
    Route::delete('/cctv-panorama/{id}', [ApiPanoramaController::class, 'destroy']);

    // Route untuk Nama Sekolah (tanpa resource)
    Route::post('/nama-sekolah', [ApiNamaSekolahController::class, 'store']);
    Route::put('/nama-sekolah/{id}', [ApiNamaSekolahController::class, 'update']);
    Route::delete('/nama-sekolah/{id}', [ApiNamaSekolahController::class, 'destroy']);
    Route::get('/nama-sekolah', [ApiNamaSekolahController::class, 'index']);
    Route::get('/nama-sekolah/wilayah/{id}', [ApiNamaSekolahController::class, 'getByWilayah']);

    Route::get('/nama-sekolah/{id}', [ApiSekolahController::class, 'show']);

    Route::apiResource('/users', ApiUsersController::class); // ← optionally protect this with auth:sanctum too
});

// -----------------------------
// 🌍 Optional: Users Management (make this protected if needed)
// -----------------------------

