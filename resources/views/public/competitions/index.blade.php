@extends('layouts.app')

@section('title', 'Katalog Kompetisi — MINBAK TI')

@section('content')
<div class="py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-mono uppercase tracking-wider text-brand-purpleLight">Delegasi & Tantangan</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-1">Katalog Kompetisi</h1>
            <p class="text-slate-400 text-sm mt-2">
                Temukan peluang kompetisi akademik teknologi, riset ilmiah, dan olahraga yang dikurasi oleh Divisi Minat dan Bakat.
            </p>
        </div>

        <!-- Filter Form -->
        <div class="glass-panel rounded-2xl p-6 mb-10">
            <form method="GET" action="{{ route('competitions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-mono uppercase text-slate-400 mb-1">Cari Lomba</label>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Nama lomba atau penyelenggara..." class="w-full px-3.5 py-2.5 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-400 mb-1">Kategori</label>
                    <select name="kategori" class="w-full px-3 py-2.5 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ $categorySlug === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tingkat -->
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-400 mb-1">Tingkat</label>
                    <select name="tingkat" class="w-full px-3 py-2.5 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                        <option value="all">Semua Tingkat</option>
                        <option value="internal_kampus" {{ $level === 'internal_kampus' ? 'selected' : '' }}>Internal Kampus</option>
                        <option value="regional" {{ $level === 'regional' ? 'selected' : '' }}>Regional</option>
                        <option value="nasional" {{ $level === 'nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="internasional" {{ $level === 'internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                </div>

                <!-- Format & Actions -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2.5 rounded-lg bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 transition-colors shadow-glow-cyan">
                        Filter
                    </button>
                    <a href="{{ route('competitions.index') }}" class="p-2.5 rounded-lg border border-brand-border text-slate-400 hover:text-white transition-colors" title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                </div>
            </form>
        </div>

        <!-- Competitions Listing -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($competitions as $comp)
                <div class="glass-panel glass-panel-purple-hover rounded-2xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="text-[10px] font-mono uppercase px-2.5 py-1 rounded-full {{ $comp->level_badge_class }}">
                                {{ strtoupper($comp->level) }}
                            </span>
                            <span class="text-[11px] font-mono text-slate-400">
                                {{ $comp->status === 'open' ? '🟢 Pendaftaran Buka' : '⚪ ' . ucfirst($comp->status) }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-white mb-1 hover:text-brand-cyan transition-colors">
                            <a href="{{ route('competitions.show', $comp->slug) }}">{{ $comp->title }}</a>
                        </h3>
                        <p class="text-xs text-brand-cyan mb-3">Oleh: {{ $comp->organizer }}</p>
                        <p class="text-xs text-slate-400 leading-relaxed mb-4 line-clamp-2">
                            {{ $comp->description }}
                        </p>
                    </div>

                    <div>
                        <div class="py-3 border-y border-brand-border/60 text-xs space-y-1 mb-4">
                            <div class="flex justify-between text-slate-400">
                                <span>Tenggat Pendaftaran:</span>
                                <span class="text-white font-mono font-medium">{{ $comp->registration_deadline?->format('d M Y') ?? 'TBA' }}</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Format:</span>
                                <span class="capitalize text-slate-200">{{ $comp->format }} ({{ $comp->team_type === 'team' ? 'Tim' : 'Individu' }})</span>
                            </div>
                        </div>

                        <a href="{{ route('competitions.show', $comp->slug) }}" class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-semibold bg-brand-surface hover:bg-slate-800 text-brand-purpleLight border border-brand-purple/40 hover:border-brand-purple transition-all">
                            <span>Lihat Informasi & Daftar</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 text-slate-400 text-sm">
                    Tidak ditemukan kompetisi yang cocok dengan kriteria filter saat ini.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $competitions->links() }}
        </div>
    </div>
</div>
@endsection
