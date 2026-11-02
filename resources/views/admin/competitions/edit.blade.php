@extends('layouts.admin')

@section('title', 'Edit Kompetisi: ' . $competition->title)

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs font-mono text-brand-muted">
            <a href="{{ route('admin.competitions.index') }}" class="hover:text-brand-cyan transition-colors">KOMPETISI</a>
            <span>/</span>
            <a href="{{ route('admin.competitions.show', $competition->id) }}" class="hover:text-brand-cyan transition-colors">{{ Str::limit($competition->title, 25) }}</a>
            <span>/</span>
            <span class="text-brand-cyan">EDIT</span>
        </div>
        <form method="POST" action="{{ route('admin.competitions.destroy', $competition->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kompetisi ini? Data pendaftaran terkait juga dapat terpengaruh.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-3 py-1.5 rounded-lg border border-rose-500/30 text-rose-400 hover:bg-rose-950/20 text-xs font-mono transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Hapus Kompetisi</span>
            </button>
        </form>
    </div>

    <div class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-6">
        <div class="border-b border-brand-border/60 pb-4">
            <h2 class="text-lg font-bold text-white">Perbarui Informasi Kompetisi</h2>
            <p class="text-xs text-slate-400 mt-1">Perubahan akan langsung terrefleksi di katalog publik dan data pendaftaran.</p>
        </div>

        <form method="POST" action="{{ route('admin.competitions.update', $competition->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="sm:col-span-2">
                    <label class="block font-mono uppercase text-slate-400 mb-1">Nama Kompetisi *</label>
                    <input type="text" name="title" value="{{ old('title', $competition->title) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Penyelenggara Resmi *</label>
                    <input type="text" name="organizer" value="{{ old('organizer', $competition->organizer) }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Kategori Bidang</label>
                    <select name="interest_category_id" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="">Pilih Bidang...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('interest_category_id', $competition->interest_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Tingkat Kompetisi *</label>
                    <select name="level" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="internal_kampus" {{ old('level', $competition->level) === 'internal_kampus' ? 'selected' : '' }}>Internal Kampus</option>
                        <option value="regional" {{ old('level', $competition->level) === 'regional' ? 'selected' : '' }}>Regional</option>
                        <option value="nasional" {{ old('level', $competition->level) === 'nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="internasional" {{ old('level', $competition->level) === 'internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Format Acara *</label>
                    <select name="format" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="online" {{ old('format', $competition->format) === 'online' ? 'selected' : '' }}>Online / Daring</option>
                        <option value="offline" {{ old('format', $competition->format) === 'offline' ? 'selected' : '' }}>Offline / Luring</option>
                        <option value="hybrid" {{ old('format', $competition->format) === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Tipe Peserta *</label>
                    <select name="team_type" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="individual" {{ old('team_type', $competition->team_type) === 'individual' ? 'selected' : '' }}>Individu (Tunggal)</option>
                        <option value="team" {{ old('team_type', $competition->team_type) === 'team' ? 'selected' : '' }}>Tim / Kelompok</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-mono uppercase text-slate-400 mb-1">Min. Anggota</label>
                        <input type="number" name="min_team_members" value="{{ old('min_team_members', $competition->min_team_members) }}" min="1" max="20" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    </div>
                    <div>
                        <label class="block font-mono uppercase text-slate-400 mb-1">Maks. Anggota</label>
                        <input type="number" name="max_team_members" value="{{ old('max_team_members', $competition->max_team_members) }}" min="1" max="20" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    </div>
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Biaya Pendaftaran (Rp)</label>
                    <input type="number" name="fee_amount" value="{{ old('fee_amount', $competition->fee_amount) }}" min="0" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Status Publikasi *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="open" {{ old('status', $competition->status) === 'open' ? 'selected' : '' }}>Pendaftaran Dibuka</option>
                        <option value="scheduled" {{ old('status', $competition->status) === 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                        <option value="closed" {{ old('status', $competition->status) === 'closed' ? 'selected' : '' }}>Pendaftaran Ditutup</option>
                        <option value="ongoing" {{ old('status', $competition->status) === 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                        <option value="completed" {{ old('status', $competition->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="draft" {{ old('status', $competition->status) === 'draft' ? 'selected' : '' }}>Draft (Simpan Internal)</option>
                        <option value="archived" {{ old('status', $competition->status) === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                    </select>
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Tenggat Pendaftaran</label>
                    <input type="datetime-local" name="registration_deadline" value="{{ old('registration_deadline', $competition->registration_deadline ? $competition->registration_deadline->format('Y-m-d\TH:i') : '') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Tanggal Mulai Acara</label>
                    <input type="datetime-local" name="event_start" value="{{ old('event_start', $competition->event_start ? $competition->event_start->format('Y-m-d\TH:i') : '') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-mono uppercase text-slate-400 mb-1">Hadiah & Penghargaan (Ringkasan)</label>
                    <input type="text" name="prize_summary" value="{{ old('prize_summary', $competition->prize_summary) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">URL Website Resmi</label>
                    <input type="url" name="official_url" value="{{ old('official_url', $competition->official_url) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">URL Buku Panduan (Guidebook)</label>
                    <input type="url" name="guidebook_url" value="{{ old('guidebook_url', $competition->guidebook_url) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-mono uppercase text-slate-400 mb-1">Deskripsi Lengkap & Persyaratan *</label>
                    <textarea name="description" rows="5" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">{{ old('description', $competition->description) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-brand-border/60">
                <a href="{{ route('admin.competitions.show', $competition->id) }}" class="px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:bg-slate-800 text-xs font-medium">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 transition-colors shadow-glow-cyan">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
