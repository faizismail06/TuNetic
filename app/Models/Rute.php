<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $table = 'rute';

    protected $fillable = [
        'nama_rute',
        'wilayah'
        // 'latitude',
        // 'longitude'
    ];

    /**
     * Semua lokasi yang berelasi (tanpa filter tipe)
     */
    public function lokasiTps()
    {
        return $this->belongsToMany(LokasiTps::class, 'rute_tps', 'id_rute', 'id_lokasi_tps');
    }

    /**
     * Relasi ke tabel rute_tps (ke primary key 'id')
     */
    public function ruteTps()
    {
        return $this->hasMany(RuteTps::class, 'id_rute');
    }

    /**
     * Hanya lokasi yang bertipe 'TPS'
     */
    public function tps()
    {
        return $this->belongsToMany(LokasiTps::class, 'rute_tps', 'id_rute', 'id_lokasi_tps')
            ->withPivot('urutan')
            ->orderBy('rute_tps.urutan');
    }

    /**
     * Hanya lokasi yang bertipe 'TPS'
     */
    public function tpst()
    {
        return $this->belongsToMany(LokasiTps::class, 'rute_tps', 'id_rute', 'id_lokasi_tps')
            ->where('lokasi_tps.tipe', 'TPST');
    }

    /**
     * Hanya lokasi yang bertipe 'TPS'
     */
    public function tpa()
    {
        return $this->belongsToMany(LokasiTps::class, 'rute_tps', 'id_rute', 'id_lokasi_tps')
            ->where('lokasi_tps.tipe', 'TPA');
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
