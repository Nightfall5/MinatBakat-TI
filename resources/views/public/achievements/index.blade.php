@extends('layouts.app')

@section('title', 'Prestasi Mahasiswa — MINBAK TI')

@section('content')
<div class="py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-mono uppercase tracking-wider text-amber-400">Hall of Fame & Rekognisi</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-1">Prestasi Mahasiswa</h1>
            <p class="text-slate-400 text-sm mt-2">
                Dokumentasi capaian juara dan penghargaan mahasiswa Teknik Informatika yang telah diverifikasi secara resmi dan memperoleh izin publikasi.
            </p>
        </div>

        <!-- Filter Bar -->
        <div class="glass-panel rounded-2xl p-6 mb-10">
            <form method="GET" action="{{ route('achievements.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-400 mb-1">Cari Prestasi / Mahasiswa</label>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Nama mahasiswa / juara..." class="w-full px-3.5 py-2.5 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-400 mb-1">Bidang Minat</label>
                    <select name="kategori" class="w-full px-3 py-2.5 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                        <option value="">Semua Bidang</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ $categorySlug === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tingkat -->
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-400 mb-1">Tingkat Capaian</label>
                    <select name="tingkat" class="w-full px-3 py-2.5 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                        <option value="all">Semua Tingkat</option>
                        <option value="internal_kampus" {{ $level === 'internal_kampus' ? 'selected' : '' }}>Internal Kampus</option>
                        <option value="regional" {{ $level === 'regional' ? 'selected' : '' }}>Regional</option>
                        <option value="nasional" {{ $level === 'nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="internasional" {{ $level === 'internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2.5 rounded-lg bg-amber-400 text-slate-950 font-bold text-xs hover:bg-amber-300 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('achievements.index') }}" class="p-2.5 rounded-lg border border-brand-border text-slate-400 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                </div>
            </form>
        </div>

        <!-- Achievements Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($achievements as $ach)
                <div class="glass-panel rounded-2xl p-6 border-amber-500/20 hover:border-amber-500/50 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-mono uppercase bg-amber-950/80 text-amber-300 border border-amber-500/30">
                                JUARA #{{ $ach->rank_position }}
                            </span>
                            <span class="text-xs font-mono text-slate-400 uppercase">
                                {{ $ach->level }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-white mb-1">
                            {{ $ach->achievement_title }}
                        </h3>
                        <p class="text-xs text-brand-cyan font-medium mb-3">
                            {{ $ach->competition?->title }}
                        </p>
                        @if($ach->notes)
                            <p class="text-xs text-slate-400 leading-relaxed mb-4">
                                {{ $ach->notes }}
                            </p>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-brand-border/60 text-xs">
                        <div class="flex justify-between items-center text-slate-300">
                            <div>
                                <span class="text-slate-400 block text-[10px]">Mahasiswa Penerima:</span>
                                <span class="font-semibold text-white">{{ $ach->profile?->full_name }}</span>
                                <span class="text-slate-400">({{ $ach->profile?->angkatan }})</span>
                            </div>
                            @if($ach->official_publication_url)
                                <a href="{{ $ach->official_publication_url }}" target="_blank" rel="noopener noreferrer" class="text-brand-cyan hover:underline text-[11px] font-mono" title="Tautan Publikasi Resmi">
                                    Sumber ↗
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 text-slate-400 text-sm">
                    Belum ada catatan prestasi yang terpublikasi untuk kriteria filter ini.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $achievements->links() }}
        </div>
    </div>
</div>
@endsection
