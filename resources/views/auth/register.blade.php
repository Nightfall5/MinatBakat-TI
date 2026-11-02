@extends('layouts.app')

@section('title', 'Daftar Akun Mahasiswa — MINBAK TI')

@section('content')
<div class="py-12 md:py-20 flex items-center justify-center">
    <div class="max-w-lg w-full px-4 sm:px-6">
        <div class="glass-panel rounded-3xl p-8 sm:p-10 shadow-glow-combined border-brand-cyan/40 space-y-6">
            <!-- Header -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-purple to-brand-cyan flex items-center justify-center mx-auto shadow-glow-combined">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <h1 class="text-2xl font-extrabold text-white">Daftar Akun Mahasiswa</h1>
                <p class="text-xs text-slate-400">Bergabung dengan ekosistem bakat dan kompetisi TI</p>
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
            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Dimas Bagas Satria" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Email Mahasiswa *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@student.ac.id" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Angkatan Kuliah *</label>
                        <select name="angkatan" required class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                            <option value="">Pilih...</option>
                            <option value="2026" {{ old('angkatan') === '2026' ? 'selected' : '' }}>2026</option>
                            <option value="2025" {{ old('angkatan') === '2025' ? 'selected' : '' }}>2025</option>
                            <option value="2024" {{ old('angkatan') === '2024' ? 'selected' : '' }}>2024</option>
                            <option value="2023" {{ old('angkatan') === '2023' ? 'selected' : '' }}>2023</option>
                            <option value="2022" {{ old('angkatan') === '2022' ? 'selected' : '' }}>2022</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">NIM (Opsional)</label>
                        <input type="text" name="nim" value="{{ old('nim') }}" placeholder="240601..." class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">No. WhatsApp (Opsional)</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0812..." class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Kata Sandi (Min 8) *</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Konfirmasi Sandi *</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="consent_terms" value="1" required class="mt-0.5 rounded bg-slate-900 border-brand-border text-brand-cyan focus:ring-brand-cyan">
                        <span class="text-xs text-slate-400 leading-relaxed">
                            Saya menyetujui pemrosesan data identitas dan bakat saya oleh pengurus divisi sesuai <a href="{{ route('privacy') }}" target="_blank" class="text-brand-cyan underline">Kebijakan Privasi</a>.
                        </span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl font-bold bg-gradient-to-r from-brand-purple to-brand-cyan text-white shadow-glow-combined hover:opacity-95 transition-all text-sm">
                    Buat Akun Mahasiswa
                </button>
            </form>

            <div class="text-center text-xs text-slate-400 pt-2">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="text-brand-cyan font-bold hover:underline">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection
