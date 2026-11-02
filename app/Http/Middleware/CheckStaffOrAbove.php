<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStaffOrAbove
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu untuk mengakses area pengurus.');
        }

        $user = auth()->user();
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan oleh Administrator.');
        }

        if (!$user->isStaffOrAbove()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses area pengurus.');
        }

        return $next($request);
    }
}
