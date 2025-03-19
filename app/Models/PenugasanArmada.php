<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanArmada extends Model
{
    use HasFactory;

    protected $table = 'penugasan_armada';

    protected $fillable = [
        'id_driver',
        'id_armada',
    ];

    /**
     * Relasi ke tabel Driver
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'id_driver');
    }

    /**
     * Relasi ke tabel Armada
     */
    public function armada()
    {
        return $this->belongsTo(Armada::class, 'id_armada');
    }
}
