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
        'id_jadwal',
        'total_sampah',
        'deskripsi',
        'status',
        'tanggal_pengangkutan',
    ];

    /**
     * Relasi ke User (Petugas).
     * Satu laporan dibuat oleh satu user.
     */
    protected $casts = [
        'status' => 'integer', // Pastikan status disimpan sebagai integer
    ];

    public function user()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    /**
     * Relasi ke Jadwal.
     * Satu laporan terkait dengan satu jadwal.
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal');
    }
}
