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
        $user = Auth::user();

        if ($user && $user->level == 4) {
            return '/masyarakat'; // Arahkan ke halaman masyarakat
        }

        // Jika level tidak sesuai, kembalikan ke halaman home atau halaman default lainnya
        return '/home';  // default jika bukan masyarakat
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
