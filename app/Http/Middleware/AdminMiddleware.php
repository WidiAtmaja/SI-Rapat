<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->peran === 'admin') {
            return $next($request);
        }

        return redirect()->route('absensi.index')->with('error', 'Akses ditolak! Hanya admin yang dapat mengakses halaman ini.');
    }
}
