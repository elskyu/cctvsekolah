<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiSekolahController;
use App\Http\Controllers\Api\ApiUsersController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiPanoramaController;

// ROUTE LOGIN
Route::post('/login', [ApiAuthController::class, 'login']);

// API CCTV SEKOLAH (GUEST)
Route::get('/cctv-sekolah', [ApiSekolahController::class, 'index']);
Route::get('/cctv-sekolah/{id}', [ApiSekolahController::class, 'show']);

// API CCTV PANORAMA (GUEST)
Route::get('/cctv-panorama', [ApiPanoramaController::class, 'index']);
Route::get('/cctv-panorama/{id}', [ApiPanoramaController::class, 'show']);

// MIDDLEWARE
Route::middleware('token.auth')->group(function () {


});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

// USER INFO
Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('token.auth')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    // Add other authenticated routes here
});


// LOGOUT


// API CCTV SEKOLAH
Route::post('/cctv-sekolah', [ApiSekolahController::class, 'store']);
Route::put('/cctv-sekolah/{id}', [ApiSekolahController::class, 'update']);
Route::delete('/cctv-sekolah/{id}', [ApiSekolahController::class, 'destroy']);
Route::post('/cctv-sekolah', [ApiSekolahController::class, 'store']);
Route::put('/cctv-sekolah/{id}', [ApiSekolahController::class, 'update']);
Route::delete('/cctv-sekolah/{id}', [ApiSekolahController::class, 'destroy']);

// API CCTV PANORAMA
Route::post('/cctv-panorama', [ApiPanoramaController::class, 'store']);
Route::put('/cctv-panorama/{id}', [ApiPanoramaController::class, 'update']);
Route::delete('/cctv-panorama/{id}', [ApiPanoramaController::class, 'destroy']);
Route::post('/cctv-panorama', [ApiPanoramaController::class, 'store']);
Route::put('/cctv-panorama/{id}', [ApiPanoramaController::class, 'update']);
Route::delete('/cctv-panorama/{id}', [ApiPanoramaController::class, 'destroy']);

// API USERS
Route::apiResource('/users', ApiUsersController::class);
