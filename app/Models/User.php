<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'foto',
        'no_telepon',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'detail_alamat',
        'status_petugas',
        'email_verified_at',
        'level'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke wilayah
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

    // Relasi ke petugas
    public function petugas()
    {
        return $this->hasOne(Petugas::class);
    }

    // Accessor untuk foto profil
    public function getFotoProfilAttribute()
    {
        if ($this->foto) {
            return asset('storage/profil/' . $this->foto);
        }
        return asset('assets/img/default-profile.jpg');
    }

    // Cek apakah user adalah petugas
    public function isPetugas()
    {
        return $this->hasRole('petugas') || $this->petugas !== null;
    }
}