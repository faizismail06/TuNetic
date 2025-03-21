<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanPetugas extends Model
{
    use HasFactory;

    protected $table = 'penugasan_petugas';

    protected $fillable = [
        'id_petugas',
        'id_armada',
    ];

    /**
     * Relasi ke tabel petugas
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    /**
     * Relasi ke tabel Armada
     */
    public function armada()
    {
        return $this->belongsTo(Armada::class, 'id_armada');
    }
}
