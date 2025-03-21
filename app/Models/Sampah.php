<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sampah extends Model
{
    use HasFactory;

    protected $table = 'sampah';

    protected $fillable = [
        'id_laporan_tps',
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
    public function laporantps()
    {
        return $this->belongsTo(LaporanTps::class, 'id_laporan_tps');
    }
}
