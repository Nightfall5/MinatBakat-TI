@extends('layouts.app')

@section('title', $category->name . ' — Eksplorasi Bidang Minat')

@section('content')
<div class="py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs font-mono text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan">Beranda</a>
            <span>/</span>
            <a href="{{ route('exploration.index') }}" class="hover:text-brand-cyan">Eksplorasi</a>
            <span>/</span>
            <span class="text-white">{{ $category->name }}</span>
        </nav>

        <!-- Category Header -->
        <div class="glass-panel rounded-3xl p-8 sm:p-10 mb-10 relative overflow-hidden">
            <div class="max-w-3xl space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-cyan/10 border border-brand-cyan/30 text-xs font-mono text-brand-cyan">
                    <span>KATALOG MINAT MAHASISWA TI</span>
                </div>
                <h1 class="text-3xl sm:text-5xl font-extrabold text-white">{{ $category->name }}</h1>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    {{ $category->description }}
                </p>
                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="{{ route('assessment.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-brand-purple to-brand-cyan text-white shadow-glow-combined">
                        <span>Tes Kecocokan di Bidang Ini</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Skills & Starter Projects -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Skill Stack -->
                <div class="glass-panel rounded-2xl p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        <span>Keahlian & Tools yang Diperlukan</span>
                    </h2>
                    <p class="text-xs text-slate-400 mb-6 leading-relaxed">
                        Kombinasi keahlian teknis dan perkakas yang sering digunakan oleh praktisi dan delegasi lomba pada kategori ini:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($category->skills_array as $skill)
                            <div class="p-3.5 rounded-xl bg-slate-900/80 border border-brand-border flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-brand-cyan"></span>
                                <span class="text-sm font-medium text-slate-200">{{ $skill }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Starter Projects -->
                @php
                    $starterProjects = json_decode($category->starter_projects, true) ?: [];
                @endphp
                @if(!empty($starterProjects))
                <div class="glass-panel rounded-2xl p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-purpleLight" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Rekomendasi Proyek Pemula</span>
                    </h2>
                    <p class="text-xs text-slate-400 mb-6 leading-relaxed">
                        Cara terbaik membangun portofolio adalah memulai dari karya nyata yang dapat diuji coba:
                    </p>
                    <div class="space-y-3">
                        @foreach($starterProjects as $idx => $project)
                            <div class="p-4 rounded-xl bg-slate-900/60 border border-brand-border flex items-start gap-4">
                                <div class="w-7 h-7 rounded-lg bg-brand-purple/20 border border-brand-purple/40 text-brand-purple font-mono font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ $idx + 1 }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">{{ $project }}</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Mulai dengan mendesain arsitektur dasar, membuat prototipe, lalu mengunggahnya ke GitHub / portofolio pribadimu.</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Col: Related Competitions & Cross Links -->
            <div class="space-y-6">
                <!-- Related Competitions -->
                <div class="glass-panel rounded-2xl p-6">
                    <h3 class="text-base font-bold text-white mb-4 flex items-center justify-between">
                        <span>Kompetisi Terkait</span>
                        <span class="text-xs font-mono text-brand-cyan">{{ $competitions->count() }} Terbuka</span>
                    </h3>

                    <div class="space-y-3">
                        @forelse($competitions as $comp)
                            <div class="p-3.5 rounded-xl bg-slate-900/80 border border-brand-border hover:border-brand-cyan/40 transition-colors">
                                <span class="text-[10px] font-mono uppercase px-2 py-0.5 rounded {{ $comp->level_badge_class }}">
                                    {{ $comp->level }}
                                </span>
                                <h4 class="text-sm font-bold text-white mt-2 hover:text-brand-cyan transition-colors">
                                    <a href="{{ route('competitions.show', $comp->slug) }}">{{ $comp->title }}</a>
                                </h4>
                                <p class="text-xs text-slate-400 mt-1">Deadline: {{ $comp->registration_deadline?->format('d M Y') }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-4 text-center">
                                Belum ada kompetisi aktif saat ini pada kategori ini.
                            </p>
                        @endforelse
                    </div>

                    <a href="{{ route('competitions.index', ['kategori' => $category->slug]) }}" class="mt-4 block text-center py-2 text-xs font-semibold text-brand-cyan hover:underline">
                        Lihat Katalog Lengkap &rarr;
                    </a>
                </div>

                <!-- Related Categories -->
                <div class="glass-panel rounded-2xl p-6">
                    <h3 class="text-base font-bold text-white mb-4">Bidang Pilihan Lainnya</h3>
                    <div class="space-y-2">
                        @foreach($relatedCategories as $rel)
                            <a href="{{ route('exploration.show', $rel->slug) }}" class="p-3 rounded-xl bg-slate-900/60 border border-brand-border hover:border-slate-600 block text-xs font-semibold text-slate-300 hover:text-white transition-colors">
                                {{ $rel->name }} &rarr;
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
