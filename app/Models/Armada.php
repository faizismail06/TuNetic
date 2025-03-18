<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Table;

class Armada extends Model
{
    use HasFactory;

    protected $table = 'armada';

    protected $fillable = [
        'id_user',
        'jenis_kendaraan',
        'kapasitas',
        'status',
        'jam_aktif',
    ];

    protected $casts = [
        'jam_aktif' => 'datetime:H:i', // Mengubah format jam ke format waktu
    ];

    /**
     * Relasi ke User (Petugas).
     * Satu armada dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
