@extends('layouts.admin')

@section('title', 'Buat Kompetisi Baru')
@section('page_title', 'Publikasi Kompetisi Baru')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-6">
        <div class="border-b border-brand-border/60 pb-4">
            <h2 class="text-lg font-bold text-white">Formulir Informasi Kompetisi</h2>
            <p class="text-xs text-slate-400 mt-1">Lengkapi rincian lomba, syarat peserta, tenggat, serta dokumen panduan.</p>
        </div>

        <form method="POST" action="{{ route('admin.competitions.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="sm:col-span-2">
                    <label class="block font-mono uppercase text-slate-400 mb-1">Nama Kompetisi *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: GEMASTIK 2026 — Desain Pengalaman Pengguna" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Penyelenggara Resmi *</label>
                    <input type="text" name="organizer" value="{{ old('organizer') }}" required placeholder="Contoh: Puspresnas Kemendikbudristek" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Kategori Bidang</label>
                    <select name="interest_category_id" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="">Pilih Bidang...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('interest_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Tingkat Kompetisi *</label>
                    <select name="level" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="internal_kampus">Internal Kampus</option>
                        <option value="regional">Regional</option>
                        <option value="nasional" selected>Nasional</option>
                        <option value="internasional">Internasional</option>
                    </select>
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Format Acara *</label>
                    <select name="format" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="online">Online / Daring</option>
                        <option value="offline">Offline / Luring</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Tipe Peserta *</label>
                    <select name="team_type" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="individual">Individu (Tunggal)</option>
                        <option value="team" selected>Tim / Kelompok</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-mono uppercase text-slate-400 mb-1">Min. Anggota</label>
                        <input type="number" name="min_team_members" value="{{ old('min_team_members', 1) }}" min="1" max="20" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    </div>
                    <div>
                        <label class="block font-mono uppercase text-slate-400 mb-1">Maks. Anggota</label>
                        <input type="number" name="max_team_members" value="{{ old('max_team_members', 3) }}" min="1" max="20" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    </div>
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Biaya Pendaftaran (Rp)</label>
                    <input type="number" name="fee_amount" value="{{ old('fee_amount', 0) }}" min="0" placeholder="0 jika gratis" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Status Publikasi *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="open" selected>Pendaftaran Dibuka</option>
                        <option value="scheduled">Terjadwal</option>
                        <option value="closed">Pendaftaran Ditutup</option>
                        <option value="ongoing">Sedang Berlangsung</option>
                        <option value="completed">Selesai</option>
                        <option value="draft">Draft (Simpan Internal)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Tenggat Pendaftaran</label>
                    <input type="datetime-local" name="registration_deadline" value="{{ old('registration_deadline') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Tanggal Mulai Acara</label>
                    <input type="datetime-local" name="event_start" value="{{ old('event_start') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-mono uppercase text-slate-400 mb-1">Hadiah & Penghargaan (Ringkasan)</label>
                    <input type="text" name="prize_summary" value="{{ old('prize_summary') }}" placeholder="Contoh: Trofi Juara 1, Sertifikat, dan Uang Tunai Rp 20.000.000" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">URL Website Resmi</label>
                    <input type="url" name="official_url" value="{{ old('official_url') }}" placeholder="https://..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">URL Buku Panduan (Guidebook)</label>
                    <input type="url" name="guidebook_url" value="{{ old('guidebook_url') }}" placeholder="https://..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-mono uppercase text-slate-400 mb-1">Deskripsi Lengkap & Persyaratan *</label>
                    <textarea name="description" rows="5" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-brand-border/60">
                <a href="{{ route('admin.competitions.index') }}" class="px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:bg-slate-800 text-xs">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300">
                    Simpan Kompetisi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
