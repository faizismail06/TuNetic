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
        'judul',
        'gambar',
        'deskripsi',
        'status',
        'kategori',
        'latitude',
        'longitude',
        'id_petugas',
        'waktu_diambil',
        'waktu_selesai',
        'alasan_ditolak',
    ];

    protected $casts = [
        'status' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'waktu_diambil' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    // Relasi ke user (warga pelapor)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke petugas yang ditugaskan (jika ada)
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    // Helper: label status
    public static function getStatusLabels(): array
    {
        return [
            0 => 'Ditolak',
            1 => 'Diproses',
            2 => 'Selesai',
        ];
    }

    // Helper: ambil label dari nilai status
    public function getStatusLabel(): string
    {
        return self::getStatusLabels()[$this->status] ?? 'Tidak diketahui';
    }

    // Helper: pilihan kategori
    public static function getKategoriOptions(): array
    {
        return [
            'Tumpukan sampah',
            'TPS Penuh',
            'Bau Menyengat',
            'Pembuangan Liar',
            'Lainnya',
        ];
    }
}
