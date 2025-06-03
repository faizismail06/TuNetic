<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuteTps extends Model
{
    use HasFactory;
    protected $table = 'rute_tps';

    protected $fillable = [
        'id_rute',
        'id_lokasi_tps',
        'urutan'
    ];

    public function jadwalOperasional()
    {
        return $this->hasMany(JadwalOperasional::class, 'id_rute_tps', 'id');
    }

    /**
     * Relasi ke tabel petugas
     */
    public function rute()
    {
        return $this->belongsTo(Rute::class, 'id_rute');
    }

    /**
     * Relasi ke tabel Armada
     */
    public function lokasi_tps()
    {
        return $this->belongsTo(LokasiTps::class, 'id_lokasi_tps');
    }
}
