<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiTps extends Model
{
    use HasFactory;

    protected $table = 'lokasi_tps';

    protected $fillable = [
        'nama_lokasi',
        'tipe',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function rutes()
    {
        return $this->belongsToMany(Rute::class, 'rute_tps');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }
}
