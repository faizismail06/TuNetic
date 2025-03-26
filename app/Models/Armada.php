<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Table;
class Armada extends Model
{
    use HasFactory;

    protected $table = 'armada'; // Nama tabel di database

    protected $fillable = [
        'jenis_kendaraan',
        'merk_kendaraan',
        'no_polisi',
        'kapasitas',
    ];

    /**
     * Cast atribut ke tipe data tertentu
     */
    // protected $casts = [
    //     'no_polisi' => 'string', // Jika nomor polisi mengandung huruf, ubah ke string
    // ];
}