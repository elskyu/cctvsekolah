<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $fillable = ['nama']; // atau sesuai field

    public function namaSekolah()
    {
        return $this->hasMany(NamaSekolah::class);
    }
}
