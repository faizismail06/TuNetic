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
            return '/admin/home';
        } elseif ($role === 'admin_tpst') {
            return '/admin-tpst/home';
        } elseif ($role === 'petugas') {
            return '/petugas';
        } elseif ($role === 'user') {
            return '/masyarakat';
        } else {
            return '/home'; // Default fallback jika role tidak dikenali
        }
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
