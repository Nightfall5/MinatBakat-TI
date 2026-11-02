@extends('layouts.app')

@section('title', 'Masuk Akun — MINBAK TI')

@section('content')
<div class="py-12 md:py-20 flex items-center justify-center">
    <div class="max-w-md w-full px-4 sm:px-6">
        <div class="glass-panel rounded-3xl p-8 sm:p-10 shadow-glow-combined border-brand-purple/40 space-y-6">
            <!-- Header -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-purple to-brand-cyan flex items-center justify-center mx-auto shadow-glow-combined">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h1 class="text-2xl font-extrabold text-white">Masuk ke Sistem</h1>
                <p class="text-xs text-slate-400">Portal Pengurus & Mahasiswa Teknik Informatika</p>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-200 text-xs">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="contoh@minbak.ti atau student.ac.id" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-400">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-brand-border text-brand-cyan focus:ring-brand-cyan">
                        <span>Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl font-bold bg-gradient-to-r from-brand-purple to-brand-cyan text-white shadow-glow-combined hover:opacity-95 transition-all text-sm">
                    Masuk Sekarang
                </button>
            </form>

            <div class="text-center text-xs text-slate-400 pt-2 border-t border-brand-border/60">
                Belum memiliki akun mahasiswa? 
                <a href="{{ route('register') }}" class="text-brand-cyan font-bold hover:underline">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection
