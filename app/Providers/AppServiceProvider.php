<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Rate Limiter for Login Attempts (Max 5 per minute per IP + email)
        RateLimiter::for('login', function (Request $request) {
            $key = (string) $request->input('email') . '|' . $request->ip();
            return Limit::perMinute(5)->by($key)->response(function () {
                return back()->withErrors([
                    'email' => 'Terlalu banyak percobaan masuk gagal. Mohon tunggu 1 menit sebelum mencoba kembali demi keamanan akun.',
                ]);
            });
        });

        // 2. Rate Limiter for Public Survey / Assessment Submissions (Anti-Spam)
        RateLimiter::for('assessment_submission', function (Request $request) {
            return Limit::perMinute(6)->by($request->ip())->response(function () {
                return back()->with('error', 'Terlalu banyak permintaan pengiriman formulir dalam waktu singkat. Mohon tunggu sejenak.');
            });
        });

        // 3. Rate Limiter for Competition Self-Registration
        RateLimiter::for('competition_register', function (Request $request) {
            $key = optional($request->user())->id ?: $request->ip();
            return Limit::perMinute(10)->by($key);
        });
    }
}
