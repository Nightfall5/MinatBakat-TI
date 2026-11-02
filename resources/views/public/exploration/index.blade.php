@extends('layouts.app')

@section('title', 'Eksplorasi Bidang Minat & Bakat — MINBAK TI')

@section('content')
<div class="py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-mono uppercase tracking-wider text-brand-cyan">Pusat Peminatan Mahasiswa</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-1">Eksplorasi Bidang Minat</h1>
            <p class="text-slate-400 text-sm mt-2">
                Temukan keahlian yang ingin kamu kuasai, pelajari tools esensial, dan temukan kompetisi yang relevan untuk setiap bidang.
            </p>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('exploration.index') }}" class="max-w-xl mx-auto mb-12">
            <div class="relative">
                <input type="text" 
                       name="q" 
                       value="{{ $search }}" 
                       placeholder="Cari bidang, keahlian, atau tools (contoh: frontend, figma, python)..."
                       class="w-full px-5 py-3.5 pl-12 rounded-xl bg-brand-surface border border-brand-border text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition-all">
                <svg class="w-5 h-5 text-slate-500 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </form>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($categories as $category)
                <div class="glass-panel glass-panel-hover rounded-2xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-brand-surface border border-brand-border flex items-center justify-center text-brand-cyan">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                            </div>
                            <span class="text-[11px] font-mono text-slate-400">Jalur #{{ $category->order_index }}</span>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2">{{ $category->name }}</h3>
                        <p class="text-xs text-slate-400 leading-relaxed mb-4">
                            {{ $category->description }}
                        </p>
                    </div>

                    <div>
                        <div class="pt-4 border-t border-brand-border/60">
                            <span class="text-[11px] font-mono text-slate-400 block mb-2">Keahlian Utama:</span>
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                @foreach($category->skills_array as $skill)
                                    <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-slate-900 text-slate-300 border border-slate-700/60">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <a href="{{ route('exploration.show', $category->slug) }}" class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-semibold bg-brand-surface hover:bg-slate-800 text-brand-cyan border border-brand-cyan/40 hover:border-brand-cyan transition-all">
                            <span>Detail Jalur & Rekomendasi</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 text-slate-400">
                    Tidak ditemukan bidang yang sesuai dengan pencarian "{{ $search }}".
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
