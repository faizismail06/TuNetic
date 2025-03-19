<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingArmada extends Model
{
    use HasFactory;

    protected $table = 'tracking_armada';

    protected $fillable = [
        'id_jadwal_operasional',
        'timestamp',
        'latitude',
        'longitude',
    ];

    /**
     * Relasi ke Jadwal Operasional
     */
    public function jadwalOperasional()
    {
        return $this->belongsTo(JadwalOperasional::class, 'id_jadwal_operasional');
    }
}
