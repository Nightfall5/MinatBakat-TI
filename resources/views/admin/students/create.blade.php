@extends('layouts.admin')

@section('title', 'Tambah Mahasiswa Manual')
@section('page_title', 'Pendaftaran Mahasiswa Manual')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-6">
        <div class="border-b border-brand-border/60 pb-4">
            <h2 class="text-lg font-bold text-white">Form Input Mahasiswa Baru</h2>
            <p class="text-xs text-slate-400 mt-1">Gunakan form ini untuk mendaftarkan mahasiswa secara manual ke dalam sistem.</p>
        </div>

        <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="sm:col-span-2">
                    <label class="block font-mono uppercase text-slate-400 mb-1">Nama Lengkap *</label>
                    <input type="text" name="full_name" required value="{{ old('full_name') }}" placeholder="Contoh: Muhammad Rizky" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">NIM (Nomor Induk Mahasiswa)</label>
                    <input type="text" name="nim" value="{{ old('nim') }}" placeholder="240601..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Angkatan *</label>
                    <select name="angkatan" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="">Pilih Angkatan...</option>
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                    </select>
                </div>
                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Email Mahasiswa *</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="nama@student.ac.id" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">No. WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="081234..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Akun Instagram</label>
                    <input type="text" name="instagram_handle" value="{{ old('instagram_handle') }}" placeholder="@username" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">GitHub URL</label>
                    <input type="url" name="github_url" value="{{ old('github_url') }}" placeholder="https://github.com/..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-mono uppercase text-slate-400 mb-1">Bio / Keterangan Singkat</label>
                    <textarea name="bio" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">{{ old('bio') }}</textarea>
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300">
                    <input type="checkbox" name="is_verified" value="1" checked class="rounded bg-slate-900 text-brand-cyan">
                    <span>Langsung Tandai Terverifikasi</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-brand-border/60">
                <a href="{{ route('admin.students.index') }}" class="px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:bg-slate-800 text-xs">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300">
                    Simpan Mahasiswa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
