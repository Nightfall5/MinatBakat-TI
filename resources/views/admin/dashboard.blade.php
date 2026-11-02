@extends('layouts.admin')

@section('title', 'Ringkasan Eksekutif')
@section('page_title', 'Ringkasan Eksekutif & Operasional')

@section('content')
<div class="space-y-8">
    <!-- Top Stats Cards Grid (4 Primary Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Anggota Terdaftar -->
        <div class="p-6 rounded-2xl bg-brand-surface border border-brand-border/80 flex items-center justify-between">
            <div>
                <span class="text-xs font-mono uppercase text-brand-muted block">Mahasiswa Terdata</span>
                <span class="text-3xl font-extrabold text-brand-cyan font-mono mt-1 block">
                    {{ number_format($totalStudents) }}
                </span>
                <span class="text-[11px] text-emerald-400 mt-1 block">
                    {{ number_format($verifiedStudents) }} Terverifikasi
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brand-cyan/10 border border-brand-cyan/30 flex items-center justify-center text-brand-cyan">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <!-- Card 2: Kompetisi Dibuka -->
        <div class="p-6 rounded-2xl bg-brand-surface border border-brand-border/80 flex items-center justify-between">
            <div>
                <span class="text-xs font-mono uppercase text-brand-muted block">Kompetisi Terbuka</span>
                <span class="text-3xl font-extrabold text-brand-purpleLight font-mono mt-1 block">
                    {{ number_format($activeCompetitions) }}
                </span>
                <span class="text-[11px] text-slate-400 mt-1 block">
                    Siap Delegasi
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brand-purple/10 border border-brand-purple/30 flex items-center justify-center text-brand-purpleLight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
        </div>

        <!-- Card 3: Menunggu Ditinjau (Priority) -->
        <div class="p-6 rounded-2xl bg-brand-surface border border-brand-border/80 flex items-center justify-between {{ $pendingRegistrationsCount > 0 ? 'border-amber-500/50 shadow-glow-combined' : '' }}">
            <div>
                <span class="text-xs font-mono uppercase text-brand-muted block">Perlu Ditinjau</span>
                <span class="text-3xl font-extrabold text-amber-400 font-mono mt-1 block">
                    {{ number_format($pendingRegistrationsCount) }}
                </span>
                <span class="text-[11px] text-amber-300 mt-1 block">
                    Pendaftaran Mahasiswa
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Card 4: Prestasi Terverifikasi -->
        <div class="p-6 rounded-2xl bg-brand-surface border border-brand-border/80 flex items-center justify-between">
            <div>
                <span class="text-xs font-mono uppercase text-brand-muted block">Prestasi Terverifikasi</span>
                <span class="text-3xl font-extrabold text-emerald-400 font-mono mt-1 block">
                    {{ number_format($verifiedAchievements) }}
                </span>
                <span class="text-[11px] text-slate-400 mt-1 block">
                    Rekam Jejak Juara
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"/></svg>
            </div>
        </div>
    </div>

    <!-- Middle Split: Priority Pending Registrations & Upcoming Deadlines -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Action Item 1: Pending Registrations -->
        <div class="p-6 rounded-2xl bg-brand-surface border border-brand-border space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span>Pendaftaran Menunggu Persetujuan Staf</span>
                    </h3>
                    <p class="text-xs text-brand-muted">Pengajuan mahasiswa yang belum dikonfirmasi statusnya.</p>
                </div>
                <a href="{{ route('admin.registrations.index', ['status' => 'submitted']) }}" class="text-xs text-brand-cyan hover:underline">Kelola Semua &rarr;</a>
            </div>

            <div class="space-y-2.5">
                @forelse($pendingRegistrations as $pReg)
                    <div class="p-3.5 rounded-xl bg-slate-900/80 border border-brand-border flex items-center justify-between gap-4">
                        <div class="truncate">
                            <span class="text-sm font-bold text-white block truncate">{{ $pReg->profile?->full_name }}</span>
                            <span class="text-xs text-brand-cyan block truncate">{{ $pReg->competition?->title }}</span>
                            <span class="text-[10px] font-mono text-slate-400">Via {{ $pReg->registration_source }} &bull; {{ $pReg->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <form method="POST" action="{{ route('admin.registrations.update_status', $pReg->id) }}">
                                @csrf
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="px-2.5 py-1 rounded bg-emerald-900/80 text-emerald-300 border border-emerald-500/40 text-[11px] font-semibold hover:bg-emerald-800">
                                    Setujui
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.registrations.update_status', $pReg->id) }}">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="px-2.5 py-1 rounded bg-rose-950 text-rose-300 border border-rose-500/40 text-[11px] font-semibold hover:bg-rose-900">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">Tidak ada pendaftaran yang menunggu peninjauan saat ini.</p>
                @endforelse
            </div>
        </div>

        <!-- Action Item 2: Upcoming Deadlines -->
        <div class="p-6 rounded-2xl bg-brand-surface border border-brand-border space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-purple"></span>
                        <span>Tenggat Pendaftaran Terdekat</span>
                    </h3>
                    <p class="text-xs text-brand-muted">Kompetisi yang segera menutup pendaftaran peserta.</p>
                </div>
                <a href="{{ route('admin.competitions.index') }}" class="text-xs text-brand-purpleLight hover:underline">Semua Lomba &rarr;</a>
            </div>

            <div class="space-y-2.5">
                @forelse($upcomingDeadlines as $uComp)
                    <div class="p-3.5 rounded-xl bg-slate-900/80 border border-brand-border flex items-center justify-between gap-4">
                        <div class="truncate">
                            <h4 class="text-sm font-bold text-white truncate">{{ $uComp->title }}</h4>
                            <span class="text-xs text-slate-400">{{ $uComp->organizer }} &bull; {{ $uComp->level }}</span>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-xs font-mono font-bold text-amber-400 block">
                                {{ $uComp->registration_deadline?->format('d M Y') }}
                            </span>
                            <span class="text-[10px] font-mono text-slate-400">
                                ({{ $uComp->registration_deadline?->diffForHumans() }})
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">Tidak ada tenggat kompetisi terdekat.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Bottom Split: Interest Categories Analytics & Recent Audit Trail -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Distribution of Interests -->
        <div class="p-6 rounded-2xl bg-brand-surface border border-brand-border space-y-4">
            <h3 class="text-base font-bold text-white">Distribusi Minat Anggota Terdata</h3>
            <p class="text-xs text-brand-muted">Jumlah mahasiswa yang menyatakan minat pada bidang-bidang berikut:</p>

            <div class="space-y-3 pt-2">
                @foreach($categoriesDistribution as $catDist)
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-medium text-slate-200">{{ $catDist->name }}</span>
                            <span class="font-mono text-brand-cyan">{{ $catDist->student_interests_count }} mahasiswa</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-900 overflow-hidden">
                            @php
                                $maxCount = max(1, $categoriesDistribution->max('student_interests_count'));
                                $pct = min(100, round(($catDist->student_interests_count / $maxCount) * 100));
                            @endphp
                            <div class="h-full bg-gradient-to-r from-brand-purple to-brand-cyan" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Audit Trail -->
        <div class="p-6 rounded-2xl bg-brand-surface border border-brand-border space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-bold text-white">Log Aktivitas & Audit Terakhir</h3>
                <a href="{{ route('admin.audit_logs.index') }}" class="text-xs text-brand-cyan hover:underline">Lihat Log Lengkap &rarr;</a>
            </div>

            <div class="space-y-2.5 text-xs">
                @foreach($recentAudits as $log)
                    <div class="p-3 rounded-xl bg-slate-900/60 border border-brand-border/60 flex items-start justify-between gap-3">
                        <div>
                            <span class="font-bold text-slate-200 block">{{ $log->description }}</span>
                            <span class="text-[10px] font-mono text-slate-400">
                                Oleh: {{ $log->user?->name ?? 'Sistem / Responden Publik' }} &bull; IP: {{ $log->ip_address }}
                            </span>
                        </div>
                        <span class="text-[10px] font-mono text-slate-400 flex-shrink-0">
                            {{ $log->created_at->diffForHumans() }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
