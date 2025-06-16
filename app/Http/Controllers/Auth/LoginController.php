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
        $role = Auth::user()->roles->first()->name;

        if ($role === 'admin_pusat') {
            return '/pusat/home';
        } elseif ($role === 'admin_tpst') {
            return '/tpst/home';
        } elseif ($role === 'petugas') {
            return '/petugas';
        } elseif ($role === 'user') {
            return '/masyarakat';
        }
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
