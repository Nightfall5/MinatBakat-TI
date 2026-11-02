@extends('layouts.app')

@section('title', 'Asesmen Sedang Ditutup — MINBAK TI')

@section('content')
<div class="py-20 text-center">
    <div class="max-w-md mx-auto px-4">
        <div class="glass-panel rounded-3xl p-8 space-y-4">
            <div class="w-16 h-16 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Sesi Asesmen Belum Dibuka / Telah Berakhir</h1>
            <p class="text-xs text-slate-400">
                Kampanye <strong>{{ $campaign->title }}</strong> saat ini sedang tidak menerima pengisian formulir. Silakan pantau pengumuman resmi divisi.
            </p>
            <div class="pt-4">
                <a href="{{ route('home') }}" class="px-6 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs inline-block">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
