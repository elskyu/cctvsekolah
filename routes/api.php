<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiSekolahController;
use App\Http\Controllers\Api\ApiUsersController;
use App\Http\Controllers\Api\ApiAuthController;

// ROUTE LOGIN
Route::post('/login', [ApiAuthController::class, 'login']);

// API CCTV SEKOLAH (GUEST)
Route::get('/cctv-sekolah', [ApiSekolahController::class, 'index']);
Route::get('/cctv-sekolah/{id}', [ApiSekolahController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    // USER INFO
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // LOGOUT
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // API CCTV SEKOLAH
    Route::post('/cctv-sekolah', [ApiSekolahController::class, 'store']);
    Route::put('/cctv-sekolah/{id}', [ApiSekolahController::class, 'update']);
    Route::delete('/cctv-sekolah/{id}', [ApiSekolahController::class, 'destroy']);

    // API USERS
    Route::apiResource('/users', ApiUsersController::class);
});
