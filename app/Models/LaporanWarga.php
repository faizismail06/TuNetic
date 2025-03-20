<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanWarga extends Model
{
    use HasFactory;

    protected $table = 'laporan_warga';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_user',
        'village_id',
        'gambar',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'status' => 'integer', // Pastikan status disimpan sebagai integer
    ];

    /**
     * Relasi ke tabel Users (Drivers).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relasi ke tabel reg_villages.
     */
    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }
}
