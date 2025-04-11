<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiSekolahController;
use App\Http\Controllers\Api\ApiUsersController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/cctv-sekolah', ApiSekolahController::class);
Route::apiResource('/users', ApiUsersController::class);
