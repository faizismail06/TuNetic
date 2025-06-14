<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Cek level user, jika level 4, arahkan ke halaman masyarakat
                if (Auth::user()->level == 1) {
                    return redirect('/pusat/home');  // Arahkan ke halaman masyarakat
                } elseif (Auth::user()->level == 2) {
                    return redirect('/tpst/home');
                } elseif (Auth::user()->level == 3) {
                    return redirect('/petugas');
                } elseif (Auth::user()->level == 4) {
                    return redirect('/masyarakat');
                }
            }
        }
        return $next($request);
    }
}
