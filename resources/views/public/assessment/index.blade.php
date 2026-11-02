@extends('layouts.app')

@section('title', 'Asesmen & Pemetaan Minat Bakat Mahasiswa TI')

@section('content')
<div class="py-12 md:py-16" x-data="{ qrModalOpen: false, activeQrUrl: '', activeCampaignTitle: '' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-12">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-cyan/10 border border-brand-cyan/30 text-xs font-mono text-brand-cyan mb-3">
                <span>TALENT DISCOVERY ENGINE</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white">Pemetaan Minat & Bakat Mahasiswa</h1>
            <p class="text-slate-400 text-sm mt-2">
                Asesmen terstruktur untuk menemukan kecenderungan minat, kecocokan gaya belajar, dan jalur kompetisi teknologi yang paling potensial untukmu.
            </p>
        </div>

        <!-- Active Campaigns -->
        <div class="max-w-4xl mx-auto space-y-6">
            @forelse($campaigns as $campaign)
                <div class="glass-panel rounded-3xl p-8 sm:p-10 border-brand-cyan/30 hover:border-brand-cyan/60 transition-all shadow-glow-combined">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="space-y-3 max-w-2xl">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-mono uppercase bg-emerald-950/80 text-emerald-400 border border-emerald-500/40">
                                    {{ $campaign->isActiveNow() ? '🟢 KAMPANYE AKTIF' : '⚪ SELESAI' }}
                                </span>
                                <span class="text-xs font-mono text-slate-400">Tahun Akademik: {{ $campaign->academic_year }}</span>
                            </div>

                            <h2 class="text-2xl font-bold text-white">{{ $campaign->title }}</h2>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                {{ $campaign->description }}
                            </p>

                            <div class="text-[11px] font-mono text-slate-400 flex items-center gap-4 pt-1">
                                <span>Estimasi Waktu: ~3 Menit</span>
                                <span>&bull;</span>
                                <span>Multi-Step Branching</span>
                                <span>&bull;</span>
                                <span>Rekomendasi Instan</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row md:flex-col gap-3 flex-shrink-0">
                            @if($campaign->isActiveNow())
                                <a href="{{ route('assessment.show', $campaign->slug) }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold bg-gradient-to-r from-brand-purple to-brand-cyan text-white shadow-glow-combined hover:opacity-95 transition-all text-sm">
                                    <span>Mulai Asesmen</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>

                                <!-- Scan QR code button for mobile mass event -->
                                <button type="button" 
                                        @click="activeQrUrl = '{{ route('assessment.show', $campaign->slug) }}'; activeCampaignTitle = '{{ $campaign->title }}'; qrModalOpen = true;"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-slate-900 border border-brand-border text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                                    <svg class="w-4 h-4 text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                    <span>Pindai QR Ponsel</span>
                                </button>
                            @else
                                <span class="px-4 py-2 rounded-xl bg-slate-800 text-slate-500 text-xs text-center">Asesmen Ditutup</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-panel rounded-2xl p-12 text-center text-slate-400 text-sm">
                    Saat ini belum ada kampanye asesmen aktif. Pengurus divisi akan membuka sesi pemetaan berkala.
                </div>
            @endforelse
        </div>

        <!-- How the Scoring Model Works Notice -->
        <div class="max-w-4xl mx-auto mt-12 p-6 rounded-2xl bg-slate-900/40 border border-brand-border text-xs text-slate-400 space-y-2">
            <h4 class="font-bold text-slate-300 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Mekanisme Penilaian & Etika Asesmen</span>
            </h4>
            <p>
                Hasil asesmen ini merupakan sistem rekomendasi penemuan minat berbasis heuristik preferensi responden (Minat 45%, Keselarasan Aktivitas 20%, Pengalaman Saat Ini 20%, dan Orientasi Belajar 15%), bukan vonis diagnosis psikometrik mutlak. Tujuannya adalah membantu pengurus mengarahkan peluang eksplorasi dan delegasi lomba yang relevan.
            </p>
        </div>
    </div>

    <!-- QR Code Scan Modal -->
    <div x-show="qrModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm"
         style="display:none;">
        <div class="glass-panel rounded-3xl max-w-sm w-full p-8 text-center space-y-6" @click.outside="qrModalOpen = false">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono uppercase text-brand-cyan">QR Code Asesmen Massal</span>
                <button type="button" @click="qrModalOpen = false" class="text-slate-400 hover:text-white">&times;</button>
            </div>

            <div>
                <h3 class="text-base font-bold text-white mb-1" x-text="activeCampaignTitle"></h3>
                <p class="text-xs text-slate-400">Pindai kamera smartphone untuk langsung membuka formulir asesmen saat kegiatan berlangsung.</p>
            </div>

            <!-- QR Code Render Image -->
            <div class="p-4 bg-white rounded-2xl inline-block mx-auto shadow-glow-combined">
                <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' + encodeURIComponent(activeQrUrl)" 
                     alt="QR Code Asesmen" 
                     class="w-48 h-48 block">
            </div>

            <div class="space-y-2">
                <button type="button" 
                        @click="window.open('https://api.qrserver.com/v1/create-qr-code/?size=600x600&data=' + encodeURIComponent(activeQrUrl), '_blank')"
                        class="w-full py-2.5 rounded-xl bg-brand-surface hover:bg-slate-800 text-brand-cyan text-xs font-semibold border border-brand-cyan/40">
                    Unduh Gambar QR Resolusi Tinggi
                </button>
                <button type="button" @click="qrModalOpen = false" class="text-xs text-slate-400 hover:text-white">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
