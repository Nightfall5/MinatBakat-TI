@extends('layouts.app')

@section('title', 'Edit Profil Mahasiswa — MINBAK TI')

@section('content')
<div class="py-12 md:py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-panel rounded-3xl p-8 sm:p-10 space-y-6">
            <div class="border-b border-brand-border/60 pb-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Edit Profil Mahasiswa</h1>
                    <p class="text-xs text-slate-400 mt-1">Kelola data identitas, tautan portofolio, dan preferensi privasi Anda.</p>
                </div>
                <a href="{{ route('portal.dashboard') }}" class="text-xs text-brand-cyan hover:underline">&larr; Kembali ke Portal</a>
            </div>

            <form method="POST" action="{{ route('portal.profile.update') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Nama Lengkap *</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $profile->full_name) }}" required class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1">NIM (Nomor Induk Mahasiswa)</label>
                        <input type="text" name="nim" value="{{ old('nim', $profile->nim) }}" placeholder="240601..." class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Angkatan Kuliah *</label>
                        <select name="angkatan" required class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                            @foreach(['2026', '2025', '2024', '2023', '2022', 'alumni'] as $thn)
                                <option value="{{ $thn }}" {{ old('angkatan', $profile->angkatan) === $thn ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1">No. WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" placeholder="08123456789" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Akun Instagram</label>
                        <input type="text" name="instagram_handle" value="{{ old('instagram_handle', $profile->instagram_handle) }}" placeholder="@username" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Tautan GitHub</label>
                        <input type="url" name="github_url" value="{{ old('github_url', $profile->github_url) }}" placeholder="https://github.com/username" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Tautan Portofolio / Website</label>
                        <input type="url" name="portfolio_url" value="{{ old('portfolio_url', $profile->portfolio_url) }}" placeholder="https://portofolio.me" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Bio / Minat Singkat</label>
                        <textarea name="bio" rows="3" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-1 focus:ring-brand-cyan focus:outline-none">{{ old('bio', $profile->bio) }}</textarea>
                    </div>
                </div>

                <!-- Privacy & Consent Settings -->
                <div class="pt-4 border-t border-brand-border/60 space-y-3">
                    <h3 class="text-sm font-bold text-white">Preferensi Privasi</h3>
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="consent_achievement_public" value="1" {{ old('consent_achievement_public', $profile->consent_achievement_public) ? 'checked' : '' }} class="mt-0.5 rounded bg-slate-900 border-brand-border text-brand-cyan focus:ring-brand-cyan">
                        <span class="text-xs text-slate-300">
                            Saya mengizinkan prestasi resmi yang telah saya raih untuk ditampilkan pada halaman Hall of Fame Prestasi Publik MINBAK TI.
                        </span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-brand-border/60">
                    <a href="{{ route('portal.dashboard') }}" class="px-5 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:bg-slate-800 text-xs font-semibold">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 shadow-glow-cyan">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
