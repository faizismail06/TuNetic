<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        $user = Auth::user();
        $level = $user->level;

        // Level 1: Admin Pusat
        if ($level == 1) {
            return '/pusat/home';
        }

        // Level 2: Admin TPST
        if ($level == 2) {
            return '/tpst/home';
        }

        // Level 3: Petugas - cek apakah user memiliki data petugas
        if ($level == 3) {
            if ($user->petugas) {
                return '/petugas';
            } else {
                // Jika level 3 tapi tidak ada data petugas, redirect ke user biasa
                return '/masyarakat';
            }
        }

        // Level 4: User/Masyarakat (default)
        if ($level == 4) {
            return '/masyarakat';
        }

        // Default fallback untuk level yang tidak terdefinisi
        return '/masyarakat';
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // ===== Tambahan untuk Google Auth =====
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(uniqid()),
                    'level' => 4,
                ]);
                $user->assignRole('user'); // uncomment jika pakai package role
            }

            Auth::login($user, true);

            return redirect($this->redirectTo());
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }
}
