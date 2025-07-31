<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NamaSekolah extends Model
{
    protected $table = 'nama_sekolah'; // karena bukan bentuk jamak
    protected $fillable = [
        'nama',
        'lokasi',
        'wilayah_id',
    ];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function sekolah()
    {
        return $this->hasMany(Sekolah::class, 'nama_sekolah_id');
    }
}
