<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah'; // Sesuaikan dengan nama tabel di database

    protected $fillable = [
        'wilayah_id',
        'namaSekolah',
        'namaTitik',
        'link',
        'status',
        'last_seen',
        'lokasi',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
    ];

    /**
     * Cek apakah CCTV masih dianggap online berdasarkan last_seen
     *
     * @param int $thresholdMinutes
     * @return bool
     */
    public function isOnline(int $thresholdMinutes = 10): bool
    {
        if (!$this->last_seen) {
            return false;
        }

        return $this->last_seen->diffInMinutes(now()) <= $thresholdMinutes;
    }

    public function namaSekolahRef()
    {
        return $this->belongsTo(NamaSekolah::class, 'nama_sekolah_id');
    }
}
