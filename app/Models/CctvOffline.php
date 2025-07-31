<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CctvOffline extends Model
{
    protected $table = 'cctv_offline';

    protected $fillable = [
        'namaSekolah',
        'namaTitik',
        'link',
        'last_seen',
        'offline_since',
        'date',
        'wilayah_id'
    ];

    public $timestamps = false;
}
