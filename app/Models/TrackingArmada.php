<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingArmada extends Model
{
    use HasFactory;

    protected $table = 'tracking_armada';

    protected $fillable = [
        'id_armada',
        'id_jadwal',
        'timestamp',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    /**
     * Relasi ke Armada.
     * Satu tracking hanya terkait dengan satu armada.
     */
    public function armada()
    {
        return $this->belongsTo(Armada::class, 'id_armada');
    }

    /**
     * Relasi ke Jadwal.
     * Satu tracking hanya terkait dengan satu jadwal.
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal');
    }
}
