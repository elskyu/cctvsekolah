<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\sekolahController;

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

// Route sekolah
Route::get('/', [sekolahController::class, 'dashboard'])->name('sekolah.sekolah');

Route::get('/cctv-sekolah', [sekolahController::class, 'index'])->name('sekolah.index');