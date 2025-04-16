<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Tambahkan ini

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // Pastikan HasRoles digunakan

    protected $fillable = [
        'name',
        'email',
        'password',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'email_verified_at',
        'alamat',
        'level',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


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
