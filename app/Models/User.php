<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Table yang digunakan oleh model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Atribut yang dapat diisi (fillable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'nama',
        'password',
        'alamat',
        'role',
        'nomor',
        'email_verified_at',
    ];

    /**
     * Atribut yang disembunyikan dalam JSON response.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data lain.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
