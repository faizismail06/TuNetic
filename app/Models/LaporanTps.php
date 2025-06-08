<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanTps extends Model
{
    use HasFactory;

    protected $table = 'laporan_tps';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_jadwal_operasional',
        'total_sampah',
        'deskripsi',
        'tanggal_pengangkutan',
        // 'status',
    ];

    protected $casts = [
        'total_sampah' => 'float',
        'tanggal_pengangkutan' => 'date',
        'status' => 'integer',
    ];

    /**
     * Relasi ke tabel jadwalOperasional.
     */
    public function jadwalOperasional()
    {
        return $this->belongsTo(JadwalOperasional::class, 'id_jadwal_operasional');
    }
}
