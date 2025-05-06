<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Override method redirectTo untuk menentukan redirect berdasarkan role.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $role = (Auth::user()->roles->first())->name;

        if ($role === 'admin_pusat') {
            return '/admin/home';
        } elseif ($role === 'admin_tpst') {
            return '/admin-tpst/home';
        } elseif ($role === 'petugas') {
            return '/petugas/home';
        } elseif ($role === 'warga') {
            return '/warga/beranda';
        } else {
            return '/admin/home'; // default fallback
        }
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
