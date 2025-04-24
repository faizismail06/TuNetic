<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalOperasional extends Model
{
    use HasFactory;

    protected $table = 'jadwal_operasional';

    public static function getStatusLabels(): array
    {
        return [
            0 => 'Belum Berjalan',
            1 => 'Sedang Berjalan',
            2 => 'Selesai',
        ];
    }

    protected $fillable = [
        'id_armada',
        'id_jadwal',
        'id_rute_tps',
        'jam_aktif',
        'status',
    ];

    protected $casts = [
        'jam_aktif' => 'string',
    ];

    /**
     * Relasi ke tabel Armada.
     */
    public function armada()
    {
        return $this->belongsTo(Armada::class, 'id_armada');
    }

    /**
     * Relasi ke tabel Jadwal.
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal');
    }

    /**
     * Relasi ke tabel Rute TPS.
     */
    public function ruteTps()
    {
        return $this->belongsTo(RuteTps::class, 'id_rute_tps');
    }

    // Relasi ke penugasan
    public function penugasanPetugas()
    {
        return $this->hasMany(PenugasanPetugas::class, 'id_jadwal_operasional');
    }
}
