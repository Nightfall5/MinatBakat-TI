@extends('layouts.admin')

@section('title', 'Catat Prestasi Mahasiswa')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-2 text-xs font-mono text-brand-muted">
        <a href="{{ route('admin.achievements.index') }}" class="hover:text-brand-cyan transition-colors">PRESTASI</a>
        <span>/</span>
        <span class="text-brand-cyan">CATAT CAPAIAN BARU</span>
    </div>

    <!-- Privacy & Verification Notice -->
    <div class="p-4 rounded-xl bg-cyan-950/30 border border-brand-cyan/40 flex items-start gap-3">
        <span class="text-brand-cyan text-xl">🛡️</span>
        <div class="space-y-1">
            <h3 class="text-sm font-semibold text-white">Prinsip Privasi & Rekam Prestasi Terverifikasi</h3>
            <p class="text-xs text-slate-300 leading-relaxed">
                Pencatatan prestasi memisahkan partisipasi biasa dari kemenangan kompetisi. Publikasi prestasi ke publik wajib memperhatikan persetujuan mahasiswa (Undang-Undang Perlindungan Data Pribadi). Mahasiswa yang belum menyetujui publikasi hanya akan tercatat di sistem administrasi internal.
            </p>
        </div>
    </div>

    <div class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-6">
        <div class="border-b border-brand-border/60 pb-4">
            <h2 class="text-lg font-bold text-white">Formulir Rekam Prestasi</h2>
            <p class="text-xs text-slate-400 mt-1">Lengkapi data kemenangan mahasiswa, bukti sertifikat, dan status verifikasi.</p>
        </div>

        <form method="POST" action="{{ route('admin.achievements.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Select Student -->
            <div>
                <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Mahasiswa Peraih Prestasi *</label>
                <select name="student_profile_id" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ old('student_profile_id') == $st->id ? 'selected' : '' }}>
                            {{ $st->full_name }} — NIM: {{ $st->nim ?? 'N/A' }} (Angk. {{ $st->batch_year }})
                            {{ $st->consent_achievement_public ? '✓ Izin Publik Aktif' : '✗ Belum Izin Publik' }}
                        </option>
                    @endforeach
                </select>
                @error('student_profile_id')
                    <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Select Competition -->
            <div>
                <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Kompetisi Terkait *</label>
                <select name="competition_id" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    <option value="">-- Pilih Kompetisi --</option>
                    @foreach($competitions as $comp)
                        <option value="{{ $comp->id }}" {{ old('competition_id') == $comp->id ? 'selected' : '' }}>
                            {{ $comp->title }} ({{ $comp->organizer }})
                        </option>
                    @endforeach
                </select>
                @error('competition_id')
                    <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Achievement Title -->
            <div>
                <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Judul / Gelar Prestasi *</label>
                <input type="text" name="achievement_title" value="{{ old('achievement_title') }}" required placeholder="Contoh: Juara 1 UI/UX Design Competition Nasional" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                @error('achievement_title')
                    <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Rank Position -->
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Peringkat / Posisi *</label>
                    <input type="number" name="rank_position" value="{{ old('rank_position', 1) }}" min="1" max="100" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    <span class="text-[10px] text-slate-400">1 = Juara 1, 2 = Juara 2, dst.</span>
                </div>

                <!-- Level -->
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Tingkat Prestasi *</label>
                    <select name="level" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="internal_kampus" {{ old('level') === 'internal_kampus' ? 'selected' : '' }}>Internal Kampus</option>
                        <option value="regional" {{ old('level') === 'regional' ? 'selected' : '' }}>Regional</option>
                        <option value="nasional" {{ old('level', 'nasional') === 'nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="internasional" {{ old('level') === 'internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                </div>

                <!-- Verification Status -->
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Status Verifikasi *</label>
                    <select name="verification_status" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="verified" {{ old('verification_status', 'verified') === 'verified' ? 'selected' : '' }}>Langsung Terverifikasi (Verified)</option>
                        <option value="pending" {{ old('verification_status') === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi (Pending)</option>
                        <option value="rejected" {{ old('verification_status') === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                    </select>
                </div>
            </div>

            <!-- Proof Document & Official Link -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Upload Bukti Sertifikat / Piagam (PDF / JPG / PNG)</label>
                    <input type="file" name="proof_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-brand-border text-white text-xs file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-mono file:bg-slate-800 file:text-brand-cyan">
                    <span class="text-[10px] text-slate-400">Maks. 5MB. Disimpan di storage aman.</span>
                </div>

                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">URL Publikasi Resmi Penyelenggara</label>
                    <input type="url" name="official_publication_url" value="{{ old('official_publication_url') }}" placeholder="https://..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
            </div>

            <!-- Public Publish Consent Checkbox -->
            <div class="p-3.5 rounded-xl bg-slate-900/60 border border-brand-border">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="is_public_published" value="1" {{ old('is_public_published', 1) ? 'checked' : '' }} class="mt-0.5 rounded bg-slate-800 border-brand-border text-brand-cyan focus:ring-brand-cyan">
                    <div>
                        <span class="text-sm font-semibold text-white">Tayangkan di Hall of Fame Prestasi Publik</span>
                        <p class="text-xs text-slate-400 mt-0.5">Sistem akan memvalidasi apakah mahasiswa telah memberikan persetujuan publikasi data (UU PDP). Jika belum, prestasi tetap tersimpan secara aman di database internal.</p>
                    </div>
                </label>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Catatan Tambahan Pengurus</label>
                <textarea name="notes" rows="3" placeholder="Informasi tambahan mengenai pembina lomba, anggota tim lain, atau penghargaan khusus." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-brand-border/60">
                <a href="{{ route('admin.achievements.index') }}" class="px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:bg-slate-800 text-xs font-medium">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 transition-colors shadow-glow-cyan">
                    Simpan & Verifikasi Prestasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
