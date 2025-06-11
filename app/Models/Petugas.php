<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Petugas extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'petugas';

    protected $fillable = [
        'user_id',
        'email',
        'name',
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

    public function user()
    {
        return $this->belongsTo(User::class)->where('level', 3); // Relasi petugas ke user dengan level 3
    }

}
