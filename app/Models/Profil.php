<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Profil extends Authenticatable
{
    use HasFactory;
    
    // Mengganti tabel ke tabel users
    protected $table = 'users';
    
    // Menyesuaikan fillable dengan kolom yang ada di tabel users
    protected $fillable = [
        'name',
        'email',
        'password',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'level',
    ];
    
    // Menambahkan atribut yang disembunyikan
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke region (provinsi)
    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'province_id');
    }

    // Relasi ke region (kabupaten/kota)
    public function regency()
    {
        return $this->belongsTo('App\Models\Regency', 'regency_id');
    }

    // Relasi ke region (kecamatan)
    public function district()
    {
        return $this->belongsTo('App\Models\District', 'district_id');
    }

    // Relasi ke region (desa/kelurahan)
    public function village()
    {
        return $this->belongsTo('App\Models\Village', 'village_id');
    }
}