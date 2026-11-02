<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckKetuaOrAbove
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $user = auth()->user();
        if (!$user->is_active || !$user->isKetuaOrAbove()) {
            abort(403, 'Akses Ditolak: Fitur ini hanya diperuntukkan bagi Ketua Divisi atau Super Admin.');
        }

        return $next($request);
    }
}
