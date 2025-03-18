<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sampah extends Model
{
    use HasFactory;

    protected $table = 'sampah';

    protected $fillable = [
        'id_lokasi',
        'jenis_sampah',
        'berat',
        'tanggal_pengangkutan',
        'status',
    ];

    protected $casts = [
        'tanggal_pengangkutan' => 'date',
    ];

    /**
     * Relasi ke Lokasi.
     * Satu sampah berasal dari satu lokasi.
     */
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi');
    }
}
