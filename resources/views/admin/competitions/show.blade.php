@extends('layouts.admin')

@section('title', $competition->title . ' — Detail Kompetisi')

@section('content')
<div class="space-y-6" x-data="{ copySuccess: false, captionCopied: false }">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-brand-muted mb-1">
                <a href="{{ route('admin.competitions.index') }}" class="hover:text-brand-cyan transition-colors">KOMPETISI</a>
                <span>/</span>
                <span class="text-brand-cyan">DETAIL & PESERTA</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-3">
                {{ $competition->title }}
                <span class="text-xs px-2.5 py-1 rounded-full font-mono font-medium border
                    {{ $competition->status === 'open' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' :
                       ($competition->status === 'scheduled' ? 'bg-blue-500/10 text-blue-400 border-blue-500/30' :
                       ($competition->status === 'ongoing' ? 'bg-amber-500/10 text-amber-400 border-amber-500/30' :
                       ($competition->status === 'completed' ? 'bg-purple-500/10 text-purple-400 border-purple-500/30' : 'bg-slate-500/10 text-slate-400 border-slate-500/30'))) }}">
                    {{ strtoupper($competition->status) }}
                </span>
            </h1>
            <p class="text-sm text-slate-400 mt-1">Diselenggarakan oleh <strong class="text-slate-200">{{ $competition->organizer }}</strong> &bull; Dibuat {{ $competition->created_at->format('d M Y') }}</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('competitions.show', $competition->slug) }}" target="_blank" class="px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-brand-border text-sm font-medium transition-colors flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Halaman Publik</span>
            </a>
            <a href="{{ route('admin.registrations.create', ['competition_id' => $competition->id]) }}" class="px-3.5 py-2 rounded-lg bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-600/30 text-sm font-medium transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span>+ Daftarkan Peserta</span>
            </a>
            <a href="{{ route('admin.competitions.edit', $competition->id) }}" class="px-3.5 py-2 rounded-lg bg-brand-purple/20 text-brand-purple border border-brand-purple/40 hover:bg-brand-purple/30 text-sm font-medium transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit</span>
            </a>
        </div>
    </div>

    <!-- Instagram Support & Quick Share Card -->
    <div class="rounded-2xl bg-gradient-to-r from-purple-950/40 via-brand-surface to-cyan-950/30 border border-brand-purple/30 p-5 shadow-glow-purple">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 text-white text-xs">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </span>
                    <h3 class="text-sm font-semibold text-white">Dukungan Publikasi & Media Instagram Divisi</h3>
                </div>
                <p class="text-xs text-slate-300">Gunakan caption template siap pakai ini untuk feed/story organisasi atau membagikan link ke grup mahasiswa.</p>
            </div>
            <div class="flex items-center gap-2">
                <button @click="
                    const caption = `🏆 [INFO KOMPETISI: {{ addslashes($competition->title) }}]\n\nHalo Mahasiswa Teknik Informatika!\nAda kesempatan berkompetisi menarik nih:\n\n📌 Kategori: {{ $competition->category?->name ?? 'Teknologi' }}\n🏢 Penyelenggara: {{ addslashes($competition->organizer) }}\n🎯 Tingkat: {{ strtoupper(str_replace('_', ' ', $competition->level)) }}\n⏳ Deadline Pendaftaran: {{ $competition->registration_deadline ? $competition->registration_deadline->format('d M Y') : 'Segera' }}\n👥 Format: {{ strtoupper($competition->team_type) }} ({{ $competition->min_team_members }}-{{ $competition->max_team_members }} orang)\n\nYuk daftarkan dirimu melalui Divisi Minat & Bakat!\nLink info lengkap: {{ route('competitions.show', $competition->slug) }}\n\n#MinbakTI #InformatikaJuara #KompetisiMahasiswa`;
                    navigator.clipboard.writeText(caption);
                    captionCopied = true;
                    setTimeout(() => captionCopied = false, 2500);
                " class="px-3.5 py-2 rounded-lg bg-slate-900/80 hover:bg-slate-800 text-xs font-mono font-medium text-purple-300 border border-purple-500/30 flex items-center gap-2 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    <span x-text="captionCopied ? 'Caption Tersalin! ✓' : 'Salin Caption Instagram'">Salin Caption Instagram</span>
                </button>

                <button @click="
                    navigator.clipboard.writeText('{{ route('competitions.show', $competition->slug) }}');
                    copySuccess = true;
                    setTimeout(() => copySuccess = false, 2500);
                " class="px-3 py-2 rounded-lg bg-slate-900/80 hover:bg-slate-800 text-xs font-mono font-medium text-cyan-300 border border-cyan-500/30 flex items-center gap-1.5 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <span x-text="copySuccess ? 'Link Tersalin! ✓' : 'Salin Link'">Salin Link</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Metadata Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-xl bg-brand-surface border border-brand-border p-4">
            <span class="text-xs font-mono uppercase text-brand-muted">Kategori & Tingkat</span>
            <p class="text-base font-bold text-white mt-1">{{ $competition->category?->name ?? 'Umum' }}</p>
            <span class="text-xs font-mono text-brand-cyan">{{ strtoupper(str_replace('_', ' ', $competition->level)) }} &bull; {{ strtoupper($competition->format) }}</span>
        </div>
        <div class="rounded-xl bg-brand-surface border border-brand-border p-4">
            <span class="text-xs font-mono uppercase text-brand-muted">Timeline Pendaftaran</span>
            <p class="text-base font-bold text-white mt-1">
                {{ $competition->registration_deadline ? $competition->registration_deadline->format('d M Y') : 'Tidak ditentukan' }}
            </p>
            <span class="text-xs text-slate-400">
                @if($competition->registration_deadline)
                    @if(now()->gt($competition->registration_deadline))
                        <span class="text-rose-400 font-mono">Tenggat terlewati</span>
                    @else
                        <span class="text-emerald-400 font-mono">{{ now()->diffInDays($competition->registration_deadline) }} hari tersisa</span>
                    @endif
                @endif
            </span>
        </div>
        <div class="rounded-xl bg-brand-surface border border-brand-border p-4">
            <span class="text-xs font-mono uppercase text-brand-muted">Format Tim & Biaya</span>
            <p class="text-base font-bold text-white mt-1">
                {{ $competition->team_type === 'team' ? "Tim ({$competition->min_team_members}–{$competition->max_team_members} org)" : 'Individu' }}
            </p>
            <span class="text-xs font-mono text-slate-400">
                {{ $competition->fee_amount > 0 ? 'Rp ' . number_format($competition->fee_amount, 0, ',', '.') : 'Gratis / Free' }}
            </span>
        </div>
        <div class="rounded-xl bg-brand-surface border border-brand-border p-4">
            <span class="text-xs font-mono uppercase text-brand-muted">Total Pendaftar Terdata</span>
            <p class="text-2xl font-black text-brand-cyan mt-1">{{ $competition->registrations->count() }}</p>
            <span class="text-xs text-slate-400 font-mono">{{ $competition->registrations->where('status', 'confirmed')->count() }} Terkonfirmasi</span>
        </div>
    </div>

    <!-- Description & Guidebook links -->
    <div class="rounded-2xl bg-brand-surface border border-brand-border p-6 space-y-4">
        <h3 class="text-base font-bold text-white">Deskripsi & Petunjuk Teknis</h3>
        <p class="text-sm text-slate-300 leading-relaxed whitespace-pre-line">{{ $competition->description }}</p>

        @if($competition->prize_summary)
            <div class="p-3.5 rounded-xl bg-slate-900/60 border border-brand-border flex items-start gap-3">
                <span class="text-amber-400 text-lg">🎁</span>
                <div>
                    <span class="text-xs font-mono uppercase text-amber-400">Hadiah / Apresiasi:</span>
                    <p class="text-sm text-slate-200 mt-0.5">{{ $competition->prize_summary }}</p>
                </div>
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-3 pt-2">
            @if($competition->official_url)
                <a href="{{ $competition->official_url }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-mono text-brand-cyan border border-brand-border flex items-center gap-1.5">
                    <span>Website Resmi Penyelenggara &rarr;</span>
                </a>
            @endif
            @if($competition->guidebook_url)
                <a href="{{ $competition->guidebook_url }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-mono text-emerald-400 border border-brand-border flex items-center gap-1.5">
                    <span>Dokumen Guidebook / Juknis &rarr;</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Participant Management Table -->
    <div class="rounded-2xl bg-brand-surface border border-brand-border overflow-hidden">
        <div class="p-5 border-b border-brand-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-white">Daftar Mahasiswa & Status Registrasi</h3>
                <p class="text-xs text-slate-400 mt-0.5">Kelola partisipasi, verifikasi konfirmasi, dan catatan staf untuk setiap pendaftar</p>
            </div>
            <a href="{{ route('admin.registrations.create', ['competition_id' => $competition->id]) }}" class="px-3 py-2 rounded-lg bg-brand-purple/20 text-brand-purple border border-brand-purple/40 hover:bg-brand-purple/30 text-xs font-medium transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Peserta</span>
            </a>
        </div>

        @if($competition->registrations->isEmpty())
            <div class="p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-800 border border-brand-border flex items-center justify-center text-slate-500 mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h4 class="text-sm font-semibold text-white">Belum Ada Peserta Terdaftar</h4>
                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Mahasiswa dapat mendaftar mandiri via portal atau staf dapat mencatat pendaftaran dari Instagram/WhatsApp.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/80 text-xs font-mono uppercase text-brand-muted border-b border-brand-border">
                        <tr>
                            <th class="px-5 py-3">Nama Mahasiswa</th>
                            <th class="px-4 py-3">Angkatan / Kontak</th>
                            <th class="px-4 py-3">Sumber Pendaftaran</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Catatan Staf</th>
                            <th class="px-5 py-3 text-right">Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-border/60">
                        @foreach($competition->registrations as $reg)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-white">
                                        <a href="{{ route('admin.students.show', $reg->student_profile_id) }}" class="hover:text-brand-cyan transition-colors">
                                            {{ $reg->profile?->full_name ?? 'Peserta #' . $reg->id }}
                                        </a>
                                    </div>
                                    <span class="text-xs font-mono text-slate-400">NIM: {{ $reg->profile?->nim ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-4 text-xs">
                                    <span class="font-mono text-slate-300">Angkatan {{ $reg->profile?->batch_year ?? '-' }}</span>
                                    <p class="text-slate-400">{{ $reg->profile?->phone_whatsapp ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-[11px] font-mono px-2 py-0.5 rounded border
                                        {{ $reg->registration_source === 'instagram_dm' ? 'bg-pink-500/10 text-pink-400 border-pink-500/30' :
                                           ($reg->registration_source === 'whatsapp' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' :
                                           ($reg->registration_source === 'web_self' ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30' : 'bg-slate-500/10 text-slate-400 border-slate-500/30')) }}">
                                        {{ strtoupper(str_replace('_', ' ', $reg->registration_source)) }}
                                    </span>
                                    @if($reg->staffRegistrar)
                                        <p class="text-[10px] text-slate-500 mt-1">Oleh: {{ $reg->staffRegistrar->name }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-xs font-mono px-2.5 py-1 rounded-full border
                                        {{ $reg->status === 'confirmed' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' :
                                           ($reg->status === 'under_review' ? 'bg-amber-500/10 text-amber-400 border-amber-500/30' :
                                           ($reg->status === 'competing' ? 'bg-purple-500/10 text-purple-400 border-purple-500/30' :
                                           ($reg->status === 'completed' ? 'bg-blue-500/10 text-blue-400 border-blue-500/30' :
                                           ($reg->status === 'rejected' ? 'bg-rose-500/10 text-rose-400 border-rose-500/30' : 'bg-slate-500/10 text-slate-400 border-slate-500/30')))) }}">
                                        {{ strtoupper($reg->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-xs text-slate-400 max-w-xs truncate">
                                    {{ $reg->admin_notes ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <form method="POST" action="{{ route('admin.registrations.update_status', $reg->id) }}" class="inline-flex items-center gap-2">
                                        @csrf
                                        <select name="status" class="px-2 py-1 bg-slate-900 border border-brand-border rounded text-xs text-slate-200 focus:outline-none focus:border-brand-cyan">
                                            <option value="submitted" {{ $reg->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                            <option value="under_review" {{ $reg->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                            <option value="confirmed" {{ $reg->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="competing" {{ $reg->status === 'competing' ? 'selected' : '' }}>Competing</option>
                                            <option value="completed" {{ $reg->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="rejected" {{ $reg->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        <button type="submit" class="px-2.5 py-1 rounded bg-brand-purple/20 text-brand-purple border border-brand-purple/40 hover:bg-brand-purple/30 text-xs font-mono transition-colors">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
