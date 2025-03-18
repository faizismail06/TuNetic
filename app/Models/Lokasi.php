<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';

    protected $fillable = [
        'nama_lokasi',
        'latitude',
        'longitude',
    ];

    /**
     * Relasi ke Rute.
     * Satu lokasi bisa dimiliki oleh banyak rute.
     */
    public function rute()
    {
        return $this->hasMany(Rute::class, 'id_lokasi');
    }
}
