@extends('layouts.app')

@section('title', 'Tentang Kami — ' . $appName)

@section('content')
<div class="py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-mono uppercase tracking-wider text-brand-cyan">Profil & Amanah Organisasi</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-1">Tentang Divisi Minat dan Bakat</h1>
            <p class="text-slate-400 text-sm mt-2">
                {{ $orgName }} — {{ $univName }}
            </p>
        </div>

        <!-- Vision & Mission Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <!-- Visi -->
            <div class="glass-panel rounded-3xl p-8 sm:p-10 border-brand-purple/40 space-y-4">
                <div class="w-12 h-12 rounded-xl bg-brand-purple/20 border border-brand-purple/50 flex items-center justify-center text-brand-purpleLight">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-white">Visi Divisi</h2>
                <p class="text-slate-300 text-sm leading-relaxed">
                    {{ $vision }}
                </p>
            </div>

            <!-- Misi -->
            <div class="glass-panel rounded-3xl p-8 sm:p-10 border-brand-cyan/40 space-y-4">
                <div class="w-12 h-12 rounded-xl bg-brand-cyan/20 border border-brand-cyan/50 flex items-center justify-center text-brand-cyan">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-white">Misi Divisi</h2>
                <div class="text-slate-300 text-sm leading-relaxed whitespace-pre-line">
                    {{ $mission }}
                </div>
            </div>
        </div>

        <!-- Program Kerja Unggulan -->
        <div class="glass-panel rounded-3xl p-8 sm:p-10 mb-16">
            <h2 class="text-2xl font-bold text-white mb-6">Program Kerja & Layanan Divisi</h2>
            <div class="text-slate-300 text-sm leading-relaxed whitespace-pre-line bg-slate-900/60 p-6 rounded-2xl border border-brand-border/60">
                {{ $programs }}
            </div>
        </div>

        <!-- Pengurus Divisi -->
        @if($officers->isNotEmpty())
        <div>
            <h2 class="text-2xl font-bold text-white mb-8 text-center">Tim Pengurus Divisi</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-4xl mx-auto">
                @foreach($officers as $officer)
                    <div class="glass-panel rounded-2xl p-6 text-center space-y-3">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-brand-purple to-brand-cyan flex items-center justify-center text-white text-xl font-bold mx-auto shadow-glow-combined">
                            {{ substr($officer->name, 0, 1) }}
                        </div>
                        <h3 class="text-base font-bold text-white">{{ $officer->name }}</h3>
                        <p class="text-xs font-mono uppercase text-brand-cyan">{{ strtoupper(str_replace('_', ' ', $officer->role)) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
