<?php

namespace App\Models;

// Import Notifikasi Kustom
use App\Notifications\CustomVerifyEmail;
use App\Notifications\CustomResetPassword;

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
        'no_telepon',
        'gambar',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
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

    // ... (Relasi Anda: province, regency, district, village, petugas) ...
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

    // Accessor untuk gambar profil
    public function getGambarProfilAttribute()
    {
        if ($this->gambar) {
            return asset('storage/profile/' . $this->gambar);
        }
        return asset('assets/img/default-profile.jpg');
    }

    /**
     * Send the email verification notification.
     * Override default Laravel notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail); // Menggunakan Notifikasi Verifikasi Kustom
    }

    /**
     * Send the password reset notification.
     * Override default Laravel notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        // Membuat URL reset password
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false));

        // Mengirim notifikasi menggunakan kelas kustom Anda
        $this->notify(new CustomResetPassword($url));
    }
}
