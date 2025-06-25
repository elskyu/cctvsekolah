<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Panorama extends Model
{
    use HasFactory;

    protected $table = 'panorama';

    protected $fillable = [
        'wilayah_id',
        'namaTitik',
        'link',
        'status_panorama',
        'last_seen_panorama',
    ];

    protected $casts = [
        'last_seen_panorama' => 'datetime',
    ];

    /**
     * Cek apakah CCTV masih dianggap online berdasarkan last_seen
     *
     * @param int $thresholdMinutes
     * @return bool
     */
    public function isOnline(int $thresholdMinutes = 10): bool
    {
        if (!$this->last_seen_panorama) {
            return false;
        }

        return $this->last_seen_panorama->diffInMinutes(now()) <= $thresholdMinutes;
    }
}
