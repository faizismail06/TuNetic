<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $table = 'rute';

    protected $fillable = [
        'nama_rute',
        'map',
        'wilayah'
        // 'latitude',
        // 'longitude'
    ];

    /**
     * Relasi ke tabel rute_tps (ke primary key 'id')
     */
    public function ruteTps()
    {
        return $this->hasMany(RuteTps::class, 'id_rute');
    }

    public function tps()
    {
        return $this->belongsToMany(LokasiTps::class, 'rute_tps', 'id_rute', 'id_lokasi_tps');
    }

    public function tpst()
    {
        return $this->belongsTo(LokasiTps::class, 'tpst_id');
    }

    public function tpa()
    {
        return $this->belongsTo(LokasiTps::class, 'tpa_id');
    }

    /**
     * Relasi ke Lokasi.
     * Satu rute memiliki satu lokasi.
     */
    // public function lokasi()
    // {
    //     return $this->belongsTo(LokasiTps::class, 'id_lokasi');
    // }
}
