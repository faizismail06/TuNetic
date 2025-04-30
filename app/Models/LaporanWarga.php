<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanWarga extends Model
{
    use HasFactory;

    protected $table = 'laporan_warga';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_user',
        'judul',
        'gambar',
        'deskripsi',
        'status',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'status' => 'integer', // Pastikan status disimpan sebagai integer
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    /**
     * Relasi ke tabel Users (Drivers).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
