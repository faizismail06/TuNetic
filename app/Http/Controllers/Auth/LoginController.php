<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // JANGAN TULIS PROPERTY INI
    // protected $redirectTo = '/home';

    protected function redirectTo()
    {
        $role = Auth::user()->roles->first()->name; // Ambil nama role user

        // Redirection berdasarkan role setelah verifikasi email
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
}
