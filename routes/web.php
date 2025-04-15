<?php

use App\Http\Controllers\Api\ApiPanoramaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\sekolahController;


Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

// Route sekolah
Route::get('/', [sekolahController::class, 'dashboard'])->name('sekolah.sekolah');
// Route Login
Route::get('/login', [sekolahController::class, 'login'])->name('login');

Route::get('/cctv-sekolah', [sekolahController::class, 'index'])->name('sekolah.index');

Route::get('/users', [sekolahController::class, 'index3'])->name('kelola.users');

Route::get('/cctv-panorama', [sekolahController::class, 'index2'])->name('panorama.index');

Route::get('/panorama', [sekolahController::class, 'panorama'])->name('panorama.panorama');