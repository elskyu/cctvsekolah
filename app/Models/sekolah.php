<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah'; // Sesuaikan dengan nama tabel di database

    protected $fillable = [
        'namaWilayah',
        'namaSekolah',
        'namaTitik',
        'link',
    ];
}
