<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\sekolahController;

Route::get('/', function () {
    return view('welcome');
});

// Route sekolah
Route::get('/', [sekolahController::class, 'dashboard'])->name('sekolah.sekolah');
Route::get('/index', [sekolahController::class, 'index'])->name('sekolah.index');
Route::get('/create', [sekolahController::class, 'create'])->name('sekolah.create');
Route::post('/sekolah', [sekolahController::class, 'store'])->name('sekolah.store');
Route::get('editSekolah/{sekolah}', [sekolahController::class, 'edit'])->name('sekolah.edit');
Route::post('/sekolah/{sekolah}', [sekolahController::class, 'update'])->name('sekolah.update');
Route::delete('/sekolah/{sekolah}', [sekolahController::class, 'delete'])->name('sekolah.delete');
Route::get('/sekolah/check-duplicate', [SekolahController::class, 'checkDuplicate'])->name('sekolah.checkDuplicate');
Route::get('/sekolah/getWilayah', [SekolahController::class, 'getWilayah'])->name('sekolah.getWilayah');
