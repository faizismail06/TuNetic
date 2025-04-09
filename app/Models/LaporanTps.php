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
        'id_petugas',
        'total_sampah',
        'deskripsi',
        'tanggal_pengangkutan',
        'status',
    ];

    protected $casts = [
        'total_sampah' => 'float',
        'tanggal_pengangkutan' => 'date',
        'status' => 'integer',
    ];

    /**
     * Relasi ke tabel petugas.
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }
}
