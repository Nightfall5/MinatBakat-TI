@extends('layouts.admin')

@section('title', 'Verifikasi & Rekam Prestasi Mahasiswa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-brand-muted mb-1">
                <span class="text-brand-cyan">PRESTASI & REKOGNISI</span>
                <span>/</span>
                <span>DAFTAR CAPAIAN</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Verifikasi & Pengelolaan Prestasi</h1>
            <p class="text-xs text-slate-400 mt-1">Hanya prestasi dengan status <strong>VERIFIED</strong> dan memiliki persetujuan publikasi mahasiswa yang tampil di katalog publik.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.achievements.create') }}" class="px-4 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 transition-colors flex items-center gap-2 shadow-glow-cyan">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Catat Prestasi Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-xl bg-brand-surface border border-brand-border">
        <form method="GET" action="{{ route('admin.achievements.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            <div class="lg:col-span-2">
                <label class="block font-mono text-slate-400 mb-1">PENCARIAN</label>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari judul prestasi, mahasiswa, atau lomba..." class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
            </div>

            <div>
                <label class="block font-mono text-slate-400 mb-1">STATUS VERIFIKASI</label>
                <select name="status" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    <option value="all">Semua Status</option>
                    <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Terverifikasi (Verified)</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi (Pending)</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block font-mono text-slate-400 mb-1">TINGKAT</label>
                    <select name="level" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="all">Semua Tingkat</option>
                        <option value="internal_kampus" {{ $level === 'internal_kampus' ? 'selected' : '' }}>Internal</option>
                        <option value="regional" {{ $level === 'regional' ? 'selected' : '' }}>Regional</option>
                        <option value="nasional" {{ $level === 'nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="internasional" {{ $level === 'internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-white font-mono border border-brand-border">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Achievements Table -->
    <div class="rounded-2xl bg-brand-surface border border-brand-border overflow-hidden">
        @if($achievements->isEmpty())
            <div class="p-16 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-800 border border-brand-border flex items-center justify-center text-slate-500 mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <h4 class="text-sm font-semibold text-white">Tidak Ada Data Prestasi Ditemukan</h4>
                <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Catat Prestasi Baru" untuk menambahkan rekam jejak kemenangan mahasiswa.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/80 text-xs font-mono uppercase text-brand-muted border-b border-brand-border">
                        <tr>
                            <th class="px-5 py-3">Prestasi & Kompetisi</th>
                            <th class="px-4 py-3">Mahasiswa</th>
                            <th class="px-4 py-3">Tingkat & Peringkat</th>
                            <th class="px-4 py-3">Publikasi Publik</th>
                            <th class="px-4 py-3">Status Verifikasi</th>
                            <th class="px-5 py-3 text-right">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-border/60">
                        @foreach($achievements as $ach)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-white flex items-center gap-2">
                                        <span class="text-amber-400">🏆</span>
                                        <span>{{ $ach->achievement_title }}</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $ach->competition?->title ?? '-' }}</p>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        @if($ach->official_publication_url)
                                            <a href="{{ $ach->official_publication_url }}" target="_blank" class="text-[11px] font-mono text-brand-cyan hover:underline inline-flex items-center gap-1">
                                                <span>Link Publikasi Resmi &rarr;</span>
                                            </a>
                                        @endif
                                        @if($ach->proof_document_path)
                                            <a href="{{ route('admin.achievements.download_proof', $ach->id) }}" class="text-[11px] font-mono text-emerald-400 hover:underline inline-flex items-center gap-1">
                                                <span>📄 Unduh Bukti Sertifikat</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="font-semibold text-slate-200">
                                        <a href="{{ route('admin.students.show', $ach->student_profile_id) }}" class="hover:text-brand-cyan transition-colors">
                                            {{ $ach->profile?->full_name ?? 'Mahasiswa #' . $ach->student_profile_id }}
                                        </a>
                                    </div>
                                    <span class="text-xs font-mono text-slate-500">NIM: {{ $ach->profile?->nim ?? '-' }} (Angk. {{ $ach->profile?->batch_year ?? '-' }})</span>
                                </td>
                                <td class="px-4 py-4 text-xs font-mono">
                                    <span class="text-brand-cyan">{{ strtoupper(str_replace('_', ' ', $ach->level)) }}</span>
                                    <p class="text-slate-400 font-semibold mt-0.5">Juara / Posisi {{ $ach->rank_position }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    @if($ach->is_public_published)
                                        <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center gap-1 w-fit">
                                            <span>Tayang di Web Publik</span>
                                        </span>
                                    @else
                                        <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-slate-500/10 text-slate-400 border border-slate-500/30 flex items-center gap-1 w-fit">
                                            <span>Internal Saja</span>
                                        </span>
                                    @endif

                                    @if(!$ach->profile?->consent_achievement_public)
                                        <p class="text-[10px] text-amber-400 mt-1">⚠️ Tanpa izin publikasi mahasiswa</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-xs font-mono px-2.5 py-1 rounded-full border inline-block
                                        {{ $ach->verification_status === 'verified' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' :
                                           ($ach->verification_status === 'pending' ? 'bg-amber-500/10 text-amber-400 border-amber-500/30' : 'bg-rose-500/10 text-rose-400 border-rose-500/30') }}">
                                        {{ strtoupper($ach->verification_status) }}
                                    </span>
                                    @if($ach->verifiedBy)
                                        <p class="text-[10px] text-slate-500 mt-1">Oleh: {{ $ach->verifiedBy->name }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.achievements.update_status', $ach->id) }}" class="inline-flex items-center gap-1.5">
                                            @csrf
                                            <select name="verification_status" class="px-2 py-1 bg-slate-900 border border-brand-border rounded text-xs text-slate-200 focus:outline-none focus:border-brand-cyan">
                                                <option value="pending" {{ $ach->verification_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="verified" {{ $ach->verification_status === 'verified' ? 'selected' : '' }}>Verified</option>
                                                <option value="rejected" {{ $ach->verification_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>

                                            <label class="text-[11px] text-slate-400 flex items-center gap-1" title="Tayangkan di website publik jika mahasiswa telah menyetujui">
                                                <input type="checkbox" name="is_public_published" value="1" {{ $ach->is_public_published ? 'checked' : '' }} class="rounded bg-slate-900 border-brand-border text-brand-cyan">
                                                <span>Publik</span>
                                            </label>

                                            <button type="submit" class="px-2.5 py-1 rounded bg-brand-purple/20 text-brand-purple border border-brand-purple/40 hover:bg-brand-purple/30 text-xs font-mono transition-colors">
                                                Update
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.achievements.destroy', $ach->id) }}" onsubmit="return confirm('Hapus rekam jejak prestasi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-500 hover:text-rose-400 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-brand-border">
                {{ $achievements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
