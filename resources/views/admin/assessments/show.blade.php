@extends('layouts.admin')

@section('title', $campaign->title . ' — Detail Kampanye Asesmen')

@section('content')
<div class="space-y-6" x-data="{ qrFullscreen: false, copiedUrl: false }">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-brand-muted mb-1">
                <a href="{{ route('admin.assessments.index') }}" class="hover:text-brand-cyan transition-colors">ASESMEN</a>
                <span>/</span>
                <span class="text-brand-cyan">DETAIL KAMPANYE</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-3">
                {{ $campaign->title }}
                <span class="text-xs px-2.5 py-1 rounded-full font-mono font-medium border
                    {{ $campaign->is_active ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-slate-500/10 text-slate-400 border-slate-500/30' }}">
                    {{ $campaign->is_active ? 'AKTIF (DIBUKA)' : 'NONAKTIF' }}
                </span>
            </h1>
            <p class="text-sm text-slate-400 mt-1">Tahun Akademik: <strong class="text-slate-200">{{ $campaign->academic_year }}</strong> &bull; Total {{ $campaign->responses->count() }} Responden</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('assessment.show', $campaign->slug) }}" target="_blank" class="px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-brand-border text-sm font-medium transition-colors flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Halaman Asesmen Publik</span>
            </a>

            <form method="POST" action="{{ route('admin.assessments.toggle_active', $campaign->id) }}">
                @csrf
                <button type="submit" class="px-3.5 py-2 rounded-lg border text-sm font-medium transition-colors {{ $campaign->is_active ? 'border-amber-500/40 text-amber-400 hover:bg-amber-950/20' : 'border-emerald-500/40 text-emerald-400 hover:bg-emerald-950/20' }}">
                    {{ $campaign->is_active ? 'Tutup Pendaftaran' : 'Aktifkan Kembali' }}
                </button>
            </form>
        </div>
    </div>

    <!-- QR Code & Live Projector Banner -->
    <div class="rounded-2xl bg-gradient-to-r from-cyan-950/40 via-brand-surface to-purple-950/40 border border-brand-cyan/30 p-6 shadow-glow-cyan">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-2 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-brand-cyan animate-pulse"></span>
                    <span class="text-xs font-mono uppercase tracking-wider text-brand-cyan font-bold">QR Code Siap Presentasi & Proyektor</span>
                </div>
                <h3 class="text-lg font-bold text-white">Tampilkan QR Code Saat Acara Mahasiswa Berlangsung</h3>
                <p class="text-xs text-slate-300 max-w-xl">
                    Mahasiswa dapat memindai langsung menggunakan kamera smartphone mereka untuk masuk ke formulir asesmen interaktif tanpa perlu mengetikkan URL.
                </p>
                <div class="pt-2 flex flex-wrap items-center justify-center md:justify-start gap-3">
                    <button @click="qrFullscreen = true" class="px-4 py-2 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0 0l-5-5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <span>Tampilkan Layar Penuh (Proyektor)</span>
                    </button>
                    <button @click="
                        navigator.clipboard.writeText('{{ route('assessment.show', $campaign->slug) }}');
                        copiedUrl = true;
                        setTimeout(() => copiedUrl = false, 2500);
                    " class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-mono text-slate-200 border border-brand-border flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        <span x-text="copiedUrl ? 'Link Tersalin! ✓' : 'Salin Tautan Langsung'">Salin Tautan Langsung</span>
                    </button>
                </div>
            </div>

            <!-- QR Visual Box -->
            <div class="p-4 rounded-2xl bg-white text-slate-900 shadow-2xl flex flex-col items-center flex-shrink-0">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode(route('assessment.show', $campaign->slug)) }}" alt="QR Code Kampanye" class="w-36 h-36">
                <span class="text-[10px] font-mono uppercase tracking-widest text-slate-500 mt-2 font-bold">{{ $campaign->qr_code_token }}</span>
            </div>
        </div>
    </div>

    <!-- Fullscreen QR Modal for Projectors -->
    <div x-show="qrFullscreen" 
         x-transition
         @keydown.escape.window="qrFullscreen = false"
         class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex flex-col items-center justify-center p-6"
         style="display: none;">
        <div class="max-w-lg w-full bg-brand-surface border border-brand-cyan/50 rounded-3xl p-8 text-center space-y-6 shadow-glow-cyan relative">
            <button @click="qrFullscreen = false" class="absolute top-4 right-4 text-slate-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="space-y-2">
                <span class="text-xs font-mono uppercase tracking-widest text-brand-cyan font-bold">PEMETAAN MINAT & BAKAT TI</span>
                <h2 class="text-2xl font-extrabold text-white">{{ $campaign->title }}</h2>
                <p class="text-xs text-slate-300">Arahkan kamera smartphone ke kode QR berikut untuk memulai pengisian.</p>
            </div>
            <div class="p-6 bg-white rounded-2xl inline-block shadow-2xl">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode(route('assessment.show', $campaign->slug)) }}" alt="QR Code Fullscreen" class="w-64 h-64 mx-auto">
                <p class="text-xs font-mono text-slate-700 mt-3 font-semibold">{{ route('assessment.show', $campaign->slug) }}</p>
            </div>
            <p class="text-xs text-slate-400">Tekan <kbd class="px-2 py-0.5 rounded bg-slate-800 text-slate-200 font-mono">ESC</kbd> untuk kembali ke dashboard admin.</p>
        </div>
    </div>

    <!-- Question Bank Summary -->
    <div class="rounded-2xl bg-brand-surface border border-brand-border p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-brand-border/60 pb-3">
            <div>
                <h3 class="text-base font-bold text-white">Bank Pertanyaan Terpasang</h3>
                <p class="text-xs text-slate-400">Terdiri dari multi-tahap dengan logika percabangan kategori</p>
            </div>
            <span class="text-xs font-mono text-brand-cyan">{{ $campaign->questions->count() }} Pertanyaan Aktif</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="p-3.5 rounded-xl bg-slate-900/60 border border-brand-border">
                <span class="font-mono text-brand-purple uppercase font-semibold">Tahap 1 — Profil Dasar</span>
                <p class="text-slate-300 mt-1 font-medium">Angkatan, Pengalaman Organisasi & Proyek Sebelumnya</p>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-900/60 border border-brand-border">
                <span class="font-mono text-brand-cyan uppercase font-semibold">Tahap 2 — Minat & Preferensi</span>
                <p class="text-slate-300 mt-1 font-medium">Preferensi Kerja, Ketertarikan Aktivitas, dan Minat Lomba</p>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-900/60 border border-brand-border">
                <span class="font-mono text-emerald-400 uppercase font-semibold">Tahap 3 — Pertanyaan Spesifik</span>
                <p class="text-slate-300 mt-1 font-medium">Branching: Software Eng, UI/UX, AI/Data, Cyber, dll.</p>
            </div>
        </div>
    </div>

    <!-- Responses Table -->
    <div class="rounded-2xl bg-brand-surface border border-brand-border overflow-hidden">
        <div class="p-5 border-b border-brand-border flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-white">Respons Masuk Responden</h3>
                <p class="text-xs text-slate-400 mt-0.5">Daftar mahasiswa yang telah menyelesaikan kuesioner dan menerima rekomendasi</p>
            </div>
            <span class="text-xs font-mono text-slate-400">{{ $responses->total() }} Total Respons</span>
        </div>

        @if($responses->isEmpty())
            <div class="p-16 text-center">
                <p class="text-xs text-slate-400">Belum ada mahasiswa yang mengirimkan jawaban pada kampanye ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/80 text-xs font-mono uppercase text-brand-muted border-b border-brand-border">
                        <tr>
                            <th class="px-5 py-3">Nama Responden</th>
                            <th class="px-4 py-3">NIM / Angkatan</th>
                            <th class="px-4 py-3">Rekomendasi Utama</th>
                            <th class="px-4 py-3">Skor Relatif</th>
                            <th class="px-4 py-3">Waktu Kirim</th>
                            <th class="px-5 py-3 text-right">Detail Hasil</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-border/60">
                        @foreach($responses as $resp)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-white">
                                        @if($resp->student_profile_id)
                                            <a href="{{ route('admin.students.show', $resp->student_profile_id) }}" class="hover:text-brand-cyan transition-colors">
                                                {{ $resp->respondent_name }}
                                            </a>
                                        @else
                                            {{ $resp->respondent_name }}
                                        @endif
                                    </div>
                                    <span class="text-xs text-slate-400">{{ $resp->respondent_email }}</span>
                                </td>
                                <td class="px-4 py-4 text-xs font-mono text-slate-300">
                                    {{ $resp->respondent_nim ?? '-' }} / Angk. {{ $resp->respondent_angkatan ?? '-' }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="font-semibold text-brand-cyan">
                                        {{ $resp->result?->primaryCategory?->name ?? 'Belum terhitung' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 font-mono text-xs text-slate-300">
                                    {{ number_format($resp->result?->primary_score ?? 0, 1) }}%
                                </td>
                                <td class="px-4 py-4 text-xs font-mono text-slate-400">
                                    {{ $resp->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if($resp->result)
                                        <a href="{{ route('assessment.result', $resp->result->access_token) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-brand-purple/20 text-brand-purple border border-brand-purple/40 hover:bg-brand-purple/30 text-xs font-mono transition-colors">
                                            Buka Hasil &rarr;
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-500 font-mono">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-brand-border">
                {{ $responses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
