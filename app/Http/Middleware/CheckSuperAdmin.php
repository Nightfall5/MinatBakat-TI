<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $user = auth()->user();
        if (!$user->is_active || !$user->isSuperAdmin()) {
            abort(403, 'Akses Ditolak: Modul ini dikhususkan untuk Super Administrator.');
        }

        return $next($request);
    }
}
