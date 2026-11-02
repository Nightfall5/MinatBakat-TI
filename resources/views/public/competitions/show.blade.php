@extends('layouts.app')

@section('title', $competition->title . ' — Detail Kompetisi')

@section('content')
<div class="py-12 md:py-16" x-data="{ regModalOpen: false, copyToast: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs font-mono text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan">Beranda</a>
            <span>/</span>
            <a href="{{ route('competitions.index') }}" class="hover:text-brand-cyan">Kompetisi</a>
            <span>/</span>
            <span class="text-white truncate max-w-xs">{{ $competition->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Main Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Header Card -->
                <div class="glass-panel rounded-3xl p-8 sm:p-10">
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="text-[11px] font-mono uppercase px-3 py-1 rounded-full {{ $competition->level_badge_class }}">
                            {{ strtoupper($competition->level) }}
                        </span>
                        <span class="text-[11px] font-mono uppercase px-3 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                            {{ $competition->format }}
                        </span>
                        <span class="text-[11px] font-mono uppercase px-3 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                            {{ $competition->team_type === 'team' ? "Tim ({$competition->min_team_members}-{$competition->max_team_members} Orang)" : 'Individu' }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl font-extrabold text-white mb-3">
                        {{ $competition->title }}
                    </h1>
                    <p class="text-sm font-medium text-brand-cyan mb-6">
                        Penyelenggara Resmi: {{ $competition->organizer }}
                    </p>

                    <div class="prose prose-invert max-w-none text-slate-300 text-sm leading-relaxed whitespace-pre-line border-t border-brand-border/60 pt-6">
                        {{ $competition->description }}
                    </div>
                </div>

                <!-- Prize & Benefits -->
                @if($competition->prize_summary)
                <div class="glass-panel rounded-2xl p-6 sm:p-8 border-amber-500/30">
                    <h3 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                        <span>Hadiah & Penghargaan</span>
                    </h3>
                    <p class="text-sm text-amber-200/90 leading-relaxed font-medium">
                        {{ $competition->prize_summary }}
                    </p>
                </div>
                @endif

                <!-- Official Documents & Links -->
                <div class="glass-panel rounded-2xl p-6 flex flex-wrap items-center justify-between gap-4">
                    <div class="space-y-1">
                        <h4 class="text-sm font-bold text-white">Sumber Informasi Resmi</h4>
                        <p class="text-xs text-slate-400">Pastikan membaca pedoman resmi sebelum mendaftar.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @if($competition->guidebook_url)
                            <a href="{{ $competition->guidebook_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700">
                                <svg class="w-4 h-4 text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Unduh Panduan (Guidebook)</span>
                            </a>
                        @endif

                        @if($competition->official_url)
                            <a href="{{ $competition->official_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700">
                                <span>Website Penyelenggara &rarr;</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Col: Registration & Status Sidebar -->
            <div class="space-y-6">
                <!-- Registration Action Box -->
                <div class="glass-panel rounded-2xl p-6 sm:p-8 space-y-6">
                    <div>
                        <span class="text-xs font-mono uppercase text-slate-400 block mb-1">Status Pendaftaran</span>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $competition->isOpen() ? 'bg-emerald-400 animate-pulse' : 'bg-slate-500' }}"></span>
                            <span class="text-lg font-bold text-white uppercase font-mono">
                                {{ $competition->status === 'open' ? 'PENDAFTARAN DIBUKA' : ucfirst($competition->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-brand-border/60 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Batas Pendaftaran:</span>
                            <span class="font-mono text-white font-semibold">{{ $competition->registration_deadline?->format('d M Y') ?? 'TBA' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Jadwal Acara:</span>
                            <span class="font-mono text-white">{{ $competition->event_start?->format('d M Y') ?? 'TBA' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Biaya Pendaftaran:</span>
                            <span class="font-mono text-white font-semibold">
                                {{ $competition->fee_amount > 0 ? 'Rp ' . number_format($competition->fee_amount, 0, ',', '.') : 'Gratis (Free)' }}
                            </span>
                        </div>
                        @if($competition->location)
                        <div class="flex justify-between">
                            <span class="text-slate-400">Lokasi:</span>
                            <span class="text-white text-right">{{ $competition->location }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Action Button: Register or Status Badge -->
                    <div class="pt-4 border-t border-brand-border/60">
                        @if($userRegistration)
                            <div class="p-4 rounded-xl bg-slate-900 border border-brand-cyan/40 text-center">
                                <span class="text-xs text-brand-muted block mb-1">Status Pengajuan Anda:</span>
                                <span class="text-sm font-bold uppercase font-mono text-brand-cyan">
                                    {{ strtoupper($userRegistration->status) }}
                                </span>
                                @if($userRegistration->admin_notes)
                                    <p class="text-[11px] text-slate-400 mt-2 bg-slate-950 p-2 rounded">
                                        {{ $userRegistration->admin_notes }}
                                    </p>
                                @endif
                            </div>
                        @elseif($competition->isOpen())
                            @auth
                                <button type="button" 
                                        @click="regModalOpen = true"
                                        class="w-full py-3.5 rounded-xl font-bold bg-gradient-to-r from-brand-purple to-brand-cyan text-white shadow-glow-combined hover:opacity-95 transition-all">
                                    Daftar / Ikuti Kompetisi Ini
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center py-3.5 rounded-xl font-bold bg-gradient-to-r from-brand-purple to-brand-cyan text-white shadow-glow-combined hover:opacity-95 transition-all text-sm">
                                    Masuk untuk Mendaftar
                                </a>
                                <p class="text-[11px] text-center text-slate-400 mt-2">
                                    Daftar mandiri membutuhkan akun mahasiswa TI.
                                </p>
                            @endauth
                        @else
                            <button disabled class="w-full py-3.5 rounded-xl font-bold bg-slate-800 text-slate-500 cursor-not-allowed text-sm">
                                Pendaftaran Telah Ditutup
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Community Social Sharing Helper -->
                <div class="glass-panel rounded-2xl p-6 text-xs space-y-3">
                    <span class="font-bold text-white block">Promosikan ke Teman / Tim</span>
                    <p class="text-slate-400">Salin caption atau tautan untuk dibagikan di grup WhatsApp atau Instagram Story:</p>
                    <button type="button" 
                            @click="navigator.clipboard.writeText(window.location.href); copyToast = true; setTimeout(() => copyToast = false, 2500);"
                            class="w-full py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-medium transition-colors">
                        <span x-show="!copyToast">🔗 Salin Tautan Lomba</span>
                        <span x-show="copyToast" class="text-emerald-400" style="display:none;">✔ Tautan Berhasil Disalin!</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Modal -->
    <div x-show="regModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         style="display:none;">
        <div class="glass-panel rounded-2xl max-w-lg w-full p-6 sm:p-8 space-y-6" @click.outside="regModalOpen = false">
            <div class="flex items-center justify-between pb-4 border-b border-brand-border">
                <h3 class="text-lg font-bold text-white">Konfirmasi Pengajuan Pendaftaran</h3>
                <button type="button" @click="regModalOpen = false" class="text-slate-400 hover:text-white">&times;</button>
            </div>

            <p class="text-xs text-slate-300 leading-relaxed">
                Anda akan mengajukan keikutsertaan pada <strong class="text-white">{{ $competition->title }}</strong>. Tim Divisi Minat dan Bakat akan meninjau dan mengonfirmasi ketersediaan delegasi.
            </p>

            <form method="POST" action="{{ route('competitions.register', $competition->id) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-400 mb-1">Catatan Tambahan / Kesiapan Tim (Opsional)</label>
                    <textarea name="notes" rows="3" placeholder="Tuliskan jika Anda sudah memiliki rekan tim atau membutuhkan pencarian tandem dari divisi..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none"></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" @click="regModalOpen = false" class="px-4 py-2 rounded-lg border border-brand-border text-slate-300 hover:bg-slate-800 text-xs font-medium">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 rounded-lg bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 shadow-glow-cyan">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
