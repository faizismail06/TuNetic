<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikel'; // Nama tabel

    protected $fillable = [
        'judul_artikel',
        'tanggal_publikasi',
        'deskripsi_singkat',
        'gambar',
        'link_artikel',
        'status',
    ];
}
