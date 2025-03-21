<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Petugas extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'petugas';
    
    protected $fillable = [
        'email',
        'name',
        'username',
        'password',
        'nomor',
        'tanggal_lahir',
        'alamat',
        'sim_image',
        'alasan_bergabung',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
