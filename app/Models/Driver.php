<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Driver extends Authenticatable
{
    use HasFactory;

    protected $table = 'drivers'; // Nama tabel di database

    protected $fillable = [
        'email',
        'nama',
        'password',
        'alamat',
        'role',
        'nomor',
        'email_verified_at',
        'sim_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
