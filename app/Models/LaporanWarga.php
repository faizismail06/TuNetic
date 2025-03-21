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
        // 'province_id',
        // 'regency_id',
        // 'district_id',
        // 'village_id',
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

    /**
     * Relasi ke tabel reg_villages.
     */
    // public function province()
    // {
    //     return $this->belongsTo(Province::class, 'province_id');
    // }

    // public function regency()
    // {
    //     return $this->belongsTo(Regency::class, 'regency_id');
    // }
    // public function district()
    // {
    //     return $this->belongsTo(District::class, 'district_id');
    // }
    // public function village()
    // {
    //     return $this->belongsTo(Village::class, 'village_id');
    // }

}
