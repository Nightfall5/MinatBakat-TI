<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\AuditLog;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->with('error', 'Akun Anda dinonaktifkan oleh Administrator.');
            }

            $request->session()->regenerate();
            AuditLog::log('auth_login', "Pengguna {$user->name} ({$user->role}) berhasil masuk.", 'users', $user->id);

            return $this->redirectBasedOnRole($user);
        }

        AuditLog::log('auth_failed_login', "Percobaan login gagal untuk email {$request->email}.", 'users', null);

        return back()->withErrors([
            'email' => 'Kombinasi email dan kata sandi tidak cocok.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nim' => ['nullable', 'string', 'max:30'],
            'angkatan' => ['required', 'string', 'max:10'],
            'phone' => ['nullable', 'string', 'max:25'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'consent_terms' => ['required', 'accepted'],
        ], [
            'consent_terms.accepted' => 'Anda wajib menyetujui kebijakan privasi dan persetujuan pengolahan data mahasiswa.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'email.unique' => 'Email ini telah terdaftar dalam sistem.',
        ]);

        $user = User::create([
            'name' => strip_tags($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ? strip_tags($validated['phone']) : null,
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        // Automatically create student profile linked to this user
        StudentProfile::create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'nim' => $validated['nim'] ? strip_tags($validated['nim']) : null,
            'angkatan' => $validated['angkatan'],
            'phone' => $user->phone,
            'consent_data_processing' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        AuditLog::log('student_registered', "Mahasiswa baru {$user->name} mendaftar akun portal.", 'users', $user->id);

        return redirect()->route('portal.dashboard')->with('success', 'Akun berhasil dibuat! Selamat datang di MINBAK TI.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            AuditLog::log('auth_logout', "Pengguna {$user->name} keluar dari sistem.", 'users', $user->id);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    private function redirectBasedOnRole(User $user)
    {
        if ($user->isStaffOrAbove()) {
            return redirect()->intended(route('admin.dashboard'));
        }
        return redirect()->intended(route('portal.dashboard'));
    }
}
