<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalOperasional extends Model
{
    use HasFactory;

    protected $table = 'jadwal_operasional';

    protected $fillable = [
        'id_penugasan',
        'id_jadwal',
        'id_rute',
        'tanggal',
        'jam_aktif',
        'status',
    ];

    /**
     * Relasi ke tabel PenugasanArmada
     */
    public function penugasan()
    {
        return $this->belongsTo(PenugasanArmada::class, 'id_penugasan');
    }

    /**
     * Relasi ke tabel Jadwal
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal');
    }

    /**
     * Relasi ke tabel Rute
     */
    public function rute()
    {
        return $this->belongsTo(Rute::class, 'id_rute');
    }
}
