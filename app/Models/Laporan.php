<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $fillable = [
        'id_user',
        'id_jadwal',
        'total_sampah',
        'deskripsi',
    ];

    /**
     * Relasi ke User (Petugas).
     * Satu laporan dibuat oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
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
