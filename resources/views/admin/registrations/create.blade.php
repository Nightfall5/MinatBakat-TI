@extends('layouts.admin')

@section('title', 'Pendaftaran Manual Peserta')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-2 text-xs font-mono text-brand-muted">
        <a href="{{ route('admin.registrations.index') }}" class="hover:text-brand-cyan transition-colors">PENDAFTARAN</a>
        <span>/</span>
        <span class="text-brand-cyan">ENTRI MANUAL STAF</span>
    </div>

    <!-- Info banner regarding Instagram DM / External Inquiries -->
    <div class="p-4 rounded-xl bg-purple-950/30 border border-brand-purple/40 flex items-start gap-3">
        <span class="text-brand-purple text-xl">📲</span>
        <div class="space-y-1">
            <h3 class="text-sm font-semibold text-white">Alur Pendaftaran Manual Staf (Instagram DM / WhatsApp)</h3>
            <p class="text-xs text-slate-300 leading-relaxed">
                Gunakan modul ini jika mahasiswa menghubungi akun Instagram Divisi Minat & Bakat atau pesan WhatsApp organisasi untuk meminta didaftarkan ke suatu kompetisi. Pastikan konfirmasi kesediaan mahasiswa telah diterima sebelum menetapkan status sebagai <strong>CONFIRMED</strong>.
            </p>
        </div>
    </div>

    <div class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-6">
        <div class="border-b border-brand-border/60 pb-4">
            <h2 class="text-lg font-bold text-white">Formulir Entri Pendaftaran Peserta</h2>
            <p class="text-xs text-slate-400 mt-1">Sistem akan secara otomatis mencatat nama staf pencatat ({{ auth()->user()->name }}) dan mencegah pendaftaran ganda pada kompetisi yang sama.</p>
        </div>

        <form method="POST" action="{{ route('admin.registrations.store') }}" class="space-y-4">
            @csrf

            <!-- Select Competition -->
            <div>
                <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Pilih Kompetisi *</label>
                <select name="competition_id" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    <option value="">-- Pilih Kompetisi Terdaftar --</option>
                    @foreach($competitions as $comp)
                        <option value="{{ $comp->id }}" {{ (request('competition_id') == $comp->id || old('competition_id') == $comp->id) ? 'selected' : '' }}>
                            {{ $comp->title }} ({{ strtoupper($comp->level) }} &bull; Deadline: {{ $comp->registration_deadline ? $comp->registration_deadline->format('d/m/Y') : 'Tanpa Tenggat' }})
                        </option>
                    @endforeach
                </select>
                @error('competition_id')
                    <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Select Student -->
            <div>
                <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Pilih Mahasiswa *</label>
                <select name="student_profile_id" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    <option value="">-- Pilih Mahasiswa Dari Database Anggota --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ old('student_profile_id') == $st->id ? 'selected' : '' }}>
                            {{ $st->full_name }} — NIM: {{ $st->nim ?? 'N/A' }} (Angkatan {{ $st->batch_year }})
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Jika profil mahasiswa belum ada, <a href="{{ route('admin.students.create') }}" target="_blank" class="text-brand-cyan underline">tambahkan mahasiswa ke database terlebih dahulu</a>.</p>
                @error('student_profile_id')
                    <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Registration Source -->
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Saluran Permintaan (Source) *</label>
                    <select name="registration_source" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="instagram_dm" {{ old('registration_source') === 'instagram_dm' ? 'selected' : '' }}>Instagram Direct Message (DM)</option>
                        <option value="whatsapp" {{ old('registration_source') === 'whatsapp' ? 'selected' : '' }}>WhatsApp Resmi Divisi</option>
                        <option value="offline" {{ old('registration_source') === 'offline' ? 'selected' : '' }}>Tatap Muka / Sekretariat</option>
                        <option value="web_self" {{ old('registration_source') === 'web_self' ? 'selected' : '' }}>Portal Website</option>
                    </select>
                </div>

                <!-- Initial Status -->
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Status Pendaftaran Awal *</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="submitted" {{ old('status') === 'submitted' ? 'selected' : '' }}>Submitted (Baru Diajukan)</option>
                        <option value="under_review" {{ old('status') === 'under_review' ? 'selected' : '' }}>Under Review (Sedang Diperiksa Staf)</option>
                        <option value="confirmed" {{ old('status', 'confirmed') === 'confirmed' ? 'selected' : '' }}>Confirmed (Terkonfirmasi & Siap Ikut)</option>
                        <option value="competing" {{ old('status') === 'competing' ? 'selected' : '' }}>Competing (Sedang Berkompetisi)</option>
                    </select>
                </div>
            </div>

            <!-- Admin Notes -->
            <div>
                <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Catatan Staf / Bukti Komunikasi</label>
                <textarea name="admin_notes" rows="4" placeholder="Contoh: Mahasiswa menghubungi via IG @mahasiswa_ti pada 10 Okt 2026. Persyaratan KTM dan form tim telah lengkap." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">{{ old('admin_notes') }}</textarea>
                <span class="text-[11px] text-slate-400">Catatan ini bersifat internal untuk pengurus dan tercatat di audit log.</span>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-brand-border/60">
                <a href="{{ route('admin.registrations.index') }}" class="px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:bg-slate-800 text-xs font-medium">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 transition-colors shadow-glow-cyan">
                    Simpan Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
