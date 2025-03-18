<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $table = 'rute';

    protected $fillable = [
        'id_lokasi',
        'nama_rute',
        'map',
        'wilayah',
    ];

    protected $casts = [
        'map' => 'array', // Karena kolom `map` menggunakan tipe JSON
    ];

    /**
     * Relasi ke Lokasi.
     * Satu rute memiliki satu lokasi.
     */
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi');
    }
}
