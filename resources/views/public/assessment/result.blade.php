@extends('layouts.app')

@section('title', 'Hasil Rekomendasi Minat & Talenta — MINBAK TI')

@section('content')
<div class="py-12 md:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Result Header -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-950/80 border border-emerald-500/40 text-xs font-mono text-emerald-400 mb-3 shadow-glow-cyan">
                <span>✔ ASESMEN SELESAI DIPROSES</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white">Rekomendasi Jalur Minat & Bakat</h1>
            <p class="text-slate-300 text-sm mt-2">
                Halo <strong class="text-white">{{ $response->respondent_name }}</strong> (Angkatan {{ $response->respondent_angkatan }}), berdasarkan analisis preferensi aktivitas dan ketertarikanmu, berikut adalah bidang yang layak kamu eksplorasi:
            </p>
            <div class="mt-4 p-3 rounded-xl bg-slate-900/80 border border-brand-border text-xs text-slate-400">
                <span class="text-brand-cyan font-bold">Catatan & Batasan:</span> Rekomendasi ini adalah pemetaan preferensi eksplorasi minat aktivitas mahasiswa Teknik Informatika untuk keperluan delegasi dan pengembangan diri, bukan merupakan diagnosis psikologis klinis atau bukti mutlak bakat ilmiah.
            </div>
        </div>

        <!-- Top 3 Recommendations Cards -->
        <div class="space-y-6 mb-12">
            @foreach($recommendations as $rec)
                <div class="glass-panel rounded-3xl p-6 sm:p-8 border-{{ $rec['rank'] === 1 ? 'brand-cyan/60 shadow-glow-cyan' : ($rec['rank'] === 2 ? 'brand-purple/40 shadow-glow-purple' : 'brand-border') }}">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl {{ $rec['rank'] === 1 ? 'bg-brand-cyan text-brand-navy' : ($rec['rank'] === 2 ? 'bg-brand-purple text-white' : 'bg-slate-800 text-slate-300') }} font-extrabold font-mono text-base flex items-center justify-center shadow-md">
                                #{{ $rec['rank'] }}
                            </div>
                            <div>
                                <span class="text-[11px] font-mono text-slate-400 uppercase tracking-wider">
                                    {{ $rec['rank'] === 1 ? 'Rekomendasi Utama' : ($rec['rank'] === 2 ? 'Rekomendasi Sekunder' : 'Pilihan Alternatif') }}
                                </span>
                                <h2 class="text-2xl font-bold text-white">{{ $rec['category_name'] }}</h2>
                            </div>
                        </div>

                        <!-- Normalized Relative Score -->
                        <div class="text-right">
                            <span class="text-2xl font-mono font-extrabold {{ $rec['rank'] === 1 ? 'text-brand-cyan' : ($rec['rank'] === 2 ? 'text-brand-purpleLight' : 'text-slate-300') }}">
                                {{ $rec['score'] }}%
                            </span>
                            <span class="block text-[10px] font-mono text-slate-400 uppercase">Kesesuaian Relatif</span>
                        </div>
                    </div>

                    <!-- Reason narrative -->
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-6 bg-slate-900/60 p-4 rounded-xl border border-brand-border/60">
                        {{ $rec['reason'] }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-brand-border/60 text-xs">
                        <!-- Suggested Skills -->
                        <div>
                            <h4 class="font-bold text-white mb-2 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>Keahlian yang Disarankan:</span>
                            </h4>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($rec['suggested_skills'] as $skill)
                                    <span class="font-mono text-[11px] px-2 py-0.5 rounded bg-slate-900 text-slate-300 border border-slate-700">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Starter Projects -->
                        <div>
                            <h4 class="font-bold text-white mb-2 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-brand-purpleLight" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <span>Contoh Proyek Pemula:</span>
                            </h4>
                            <ul class="space-y-1 text-slate-300 list-disc list-inside">
                                @foreach(array_slice($rec['starter_projects'], 0, 2) as $proj)
                                    <li>{{ $proj }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Matched Open Competitions -->
                    @if(!empty($rec['matched_competitions']))
                        <div class="mt-6 pt-4 border-t border-brand-border/60">
                            <span class="text-xs font-bold text-brand-cyan block mb-2">Kompetisi Terkait yang Sedang Membuka Pendaftaran:</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($rec['matched_competitions'] as $mComp)
                                    <a href="{{ route('competitions.show', $mComp['slug']) }}" class="p-3 rounded-xl bg-slate-900 border border-brand-border hover:border-brand-cyan/50 flex items-center justify-between text-xs transition-colors">
                                        <div class="truncate mr-2">
                                            <span class="font-bold text-white block truncate">{{ $mComp['title'] }}</span>
                                            <span class="text-slate-400 font-mono text-[10px]">Deadline: {{ $mComp['deadline'] }}</span>
                                        </div>
                                        <span class="text-brand-cyan">&rarr;</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Sharing & Save Result Token Notice -->
        <div class="glass-panel rounded-2xl p-6 mb-8 text-xs text-slate-300 space-y-3">
            <h4 class="font-bold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Akses Aman Hasil Asesmen</span>
            </h4>
            <p>
                Hasil ini dapat kamu buka kembali kapan saja melalui tautan rahasiamu tanpa perlu login:
            </p>
            <div class="flex items-center gap-2">
                <input type="text" readonly value="{{ url()->current() }}" class="flex-1 px-3 py-2 rounded-lg bg-slate-950 border border-brand-border font-mono text-[11px] text-slate-300">
                <button type="button" onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('Tautan hasil berhasil disalin!');" class="px-4 py-2 rounded-lg bg-brand-surface border border-brand-cyan text-brand-cyan font-bold hover:bg-slate-800">
                    Salin
                </button>
            </div>
        </div>

        <!-- Next Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('competitions.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold bg-gradient-to-r from-brand-purple to-brand-cyan text-white shadow-glow-combined text-sm">
                <span>Jelajahi Semua Lomba</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl font-medium border border-brand-border text-slate-300 hover:text-white hover:bg-slate-800 text-sm">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
