@extends('layouts.admin')

@section('title', 'Buat Kampanye Asesmen Baru')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-2 text-xs font-mono text-brand-muted">
        <a href="{{ route('admin.assessments.index') }}" class="hover:text-brand-cyan transition-colors">ASESMEN</a>
        <span>/</span>
        <span class="text-brand-cyan">BUAT KAMPANYE</span>
    </div>

    <div class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-6">
        <div class="border-b border-brand-border/60 pb-4">
            <h2 class="text-lg font-bold text-white">Buat Kampanye Asesmen Massal Baru</h2>
            <p class="text-xs text-slate-400 mt-1">Sistem akan secara otomatis menghasilkan URL unik dan token QR Code untuk dipindai mahasiswa.</p>
        </div>

        <form method="POST" action="{{ route('admin.assessments.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Nama / Judul Kampanye *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Pemetaan Minat & Bakat Mahasiswa Baru TI 2026" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                @error('title')
                    <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Tahun Akademik *</label>
                <input type="text" name="academic_year" value="{{ old('academic_year', date('Y') . '/' . (date('Y') + 1)) }}" required placeholder="Contoh: 2026/2027" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                @error('academic_year')
                    <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Deskripsi Kampanye</label>
                <textarea name="description" rows="3" placeholder="Jelaskan tujuan pengumpulan minat bakat ini kepada mahasiswa..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <label class="flex items-center gap-3 p-3.5 rounded-xl bg-slate-900/60 border border-brand-border cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="rounded bg-slate-800 border-brand-border text-brand-cyan focus:ring-brand-cyan">
                    <div>
                        <span class="text-sm font-semibold text-white">Aktifkan Langsung Kampanye Ini</span>
                        <p class="text-xs text-slate-400 mt-0.5">Jika dicentang, tautan dan QR code langsung dapat diakses mahasiswa.</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-3.5 rounded-xl bg-slate-900/60 border border-brand-border cursor-pointer">
                    <input type="checkbox" name="require_login" value="1" {{ old('require_login') ? 'checked' : '' }} class="rounded bg-slate-800 border-brand-border text-brand-cyan focus:ring-brand-cyan">
                    <div>
                        <span class="text-sm font-semibold text-white">Wajib Login Akun Mahasiswa</span>
                        <p class="text-xs text-slate-400 mt-0.5">Jika tidak dicentang (mode massal tanpa login), mahasiswa cukup mengisi nama, NIM, dan email kampus.</p>
                    </div>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-brand-border/60">
                <a href="{{ route('admin.assessments.index') }}" class="px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:bg-slate-800 text-xs font-medium">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 transition-colors shadow-glow-cyan">
                    Buat Kampanye & Generate QR
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
