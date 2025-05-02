<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    public static function getStatusLabels(): array
    {
        return [
            0 => 'Aktif',
            1 => 'Tidak Aktif',
        ];
    }


    protected $fillable = [
        'hari',
        'status',
    ];

    // protected $casts = [
    //     'tanggal' => 'date',
    // ];

    /**
     * Relasi ke Rute.
     * Satu jadwal memiliki satu rute.
     */
    // public function rute()
    // {
    //     return $this->belongsTo(Rute::class, 'id_rute');
    // }

    /**
     * Relasi ke Armada.
     * Satu jadwal memiliki satu armada.
     */
}
