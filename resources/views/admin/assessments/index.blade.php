@extends('layouts.admin')

@section('title', 'Asesmen & Kampanye Pemetaan Minat')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-brand-muted mb-1">
                <span class="text-brand-cyan">ASESMEN MASSAL</span>
                <span>/</span>
                <span>KAMPANJE & ANALITIK</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Kampanye Asesmen Minat & Bakat</h1>
            <p class="text-xs text-slate-400 mt-1">Kelola kuesioner massal terintegrasi pengganti Google Forms, QR Code interaktif, dan distribusi minat mahasiswa.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.assessments.create') }}" class="px-4 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 transition-colors flex items-center gap-2 shadow-glow-cyan">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Buat Kampanye Baru</span>
            </a>
        </div>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-xl bg-brand-surface border border-brand-border p-5">
            <span class="text-xs font-mono uppercase text-brand-muted">Total Responden Terdata</span>
            <p class="text-3xl font-black text-white mt-1">{{ number_format($totalResponses) }}</p>
            <span class="text-xs font-mono text-brand-cyan">{{ $totalVerified }} Terverifikasi Anggota</span>
        </div>
        <div class="rounded-xl bg-brand-surface border border-brand-border p-5">
            <span class="text-xs font-mono uppercase text-brand-muted">Kampanye Aktif</span>
            <p class="text-3xl font-black text-emerald-400 mt-1">{{ $campaigns->where('is_active', true)->count() }}</p>
            <span class="text-xs text-slate-400 font-mono">dari {{ $campaigns->count() }} total kampanye</span>
        </div>
        <div class="rounded-xl bg-brand-surface border border-brand-border p-5">
            <span class="text-xs font-mono uppercase text-brand-muted">Bidang Paling Diminati</span>
            <p class="text-lg font-bold text-brand-purple mt-1 truncate">
                {{ $categoryDistribution->first()?->name ?? 'Belum ada data' }}
            </p>
            <span class="text-xs font-mono text-slate-400">{{ $categoryDistribution->first()?->count ?? 0 }} rekomendasi utama</span>
        </div>
        <div class="rounded-xl bg-brand-surface border border-brand-border p-5">
            <span class="text-xs font-mono uppercase text-brand-muted">Angkatan Teraktif</span>
            <p class="text-2xl font-bold text-brand-cyan mt-1">
                {{ $angkatanDistribution->first()?->respondent_angkatan ? 'Angkatan ' . $angkatanDistribution->first()->respondent_angkatan : 'N/A' }}
            </p>
            <span class="text-xs text-slate-400 font-mono">{{ $angkatanDistribution->first()?->count ?? 0 }} responden</span>
        </div>
    </div>

    <!-- Analytics Breakdown: Category & Angkatan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Interest Recommendations -->
        <div class="lg:col-span-2 rounded-2xl bg-brand-surface border border-brand-border p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-brand-border/60 pb-3">
                <h3 class="text-base font-bold text-white">Distribusi Hasil Rekomendasi Bidang</h3>
                <span class="text-xs font-mono text-brand-muted">Scoring Heuristik 4 Dimensi</span>
            </div>

            @if($categoryDistribution->isEmpty())
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada hasil asesmen mahasiswa yang tercatat.</p>
            @else
                <div class="space-y-3">
                    @php $maxCount = max($categoryDistribution->max('count'), 1); @endphp
                    @foreach($categoryDistribution->take(7) as $dist)
                        @php $percent = round(($dist->count / $maxCount) * 100); @endphp
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-medium text-slate-200">{{ $dist->name }}</span>
                                <span class="font-mono text-brand-cyan">{{ $dist->count }} Mahasiswa</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-brand-purple to-brand-cyan" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Angkatan Breakdown -->
        <div class="rounded-2xl bg-brand-surface border border-brand-border p-6 space-y-4">
            <div class="border-b border-brand-border/60 pb-3">
                <h3 class="text-base font-bold text-white">Responden per Angkatan</h3>
                <span class="text-xs font-mono text-brand-muted">Partisipasi Sensus Mahasiswa</span>
            </div>

            @if($angkatanDistribution->isEmpty())
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada data partisipasi.</p>
            @else
                <div class="space-y-3">
                    @foreach($angkatanDistribution as $ad)
                        <div class="p-3 rounded-xl bg-slate-900/60 border border-brand-border flex items-center justify-between">
                            <div>
                                <span class="text-xs font-mono uppercase text-slate-400">Angkatan</span>
                                <p class="text-sm font-bold text-white">{{ $ad->respondent_angkatan ?? 'Tidak diisi' }}</p>
                            </div>
                            <span class="text-base font-bold font-mono text-brand-cyan">{{ $ad->count }} Org</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Campaigns List -->
    <div class="rounded-2xl bg-brand-surface border border-brand-border overflow-hidden">
        <div class="p-5 border-b border-brand-border flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-white">Daftar Kampanye Asesmen Massal</h3>
                <p class="text-xs text-slate-400 mt-0.5">Setiap kampanye memiliki tautan publik unik dan QR Code untuk dipindai saat acara organisasi</p>
            </div>
            <a href="{{ route('admin.assessments.create') }}" class="px-3 py-1.5 rounded-lg bg-brand-purple/20 text-brand-purple border border-brand-purple/40 hover:bg-brand-purple/30 text-xs font-medium transition-colors">
                + Kampanye Baru
            </a>
        </div>

        @if($campaigns->isEmpty())
            <div class="p-16 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-800 border border-brand-border flex items-center justify-center text-slate-500 mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h4 class="text-sm font-semibold text-white">Belum Ada Kampanye Asesmen</h4>
                <p class="text-xs text-slate-400 mt-1">Buat kampanye asesmen baru untuk mengumpulkan minat bakat mahasiswa.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/80 text-xs font-mono uppercase text-brand-muted border-b border-brand-border">
                        <tr>
                            <th class="px-5 py-3">Nama Kampanye</th>
                            <th class="px-4 py-3">Tahun Akademik</th>
                            <th class="px-4 py-3">Jumlah Responden</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-border/60">
                        @foreach($campaigns as $camp)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-white">
                                        <a href="{{ route('admin.assessments.show', $camp->id) }}" class="hover:text-brand-cyan transition-colors">
                                            {{ $camp->title }}
                                        </a>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ $camp->description ?? 'Tidak ada deskripsi' }}</p>
                                    <span class="text-[11px] font-mono text-brand-cyan mt-1 block">
                                        URL: /asesmen/{{ $camp->slug }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 font-mono text-xs text-slate-300">
                                    {{ $camp->academic_year }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="font-bold font-mono text-brand-cyan text-base">{{ $camp->responses_count }}</span>
                                    <span class="text-xs text-slate-400">Responden</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-xs font-mono px-2.5 py-1 rounded-full border
                                        {{ $camp->is_active ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-slate-500/10 text-slate-400 border-slate-500/30' }}">
                                        {{ $camp->is_active ? 'AKTIF (DIBUKA)' : 'NONAKTIF (DITUTUP)' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.assessments.show', $camp->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-brand-border text-xs font-mono transition-colors">
                                            Lihat Respons & QR
                                        </a>

                                        <form method="POST" action="{{ route('admin.assessments.toggle_active', $camp->id) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg border text-xs font-mono transition-colors {{ $camp->is_active ? 'border-amber-500/30 text-amber-400 hover:bg-amber-950/20' : 'border-emerald-500/30 text-emerald-400 hover:bg-emerald-950/20' }}">
                                                {{ $camp->is_active ? 'Tutup' : 'Buka' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
