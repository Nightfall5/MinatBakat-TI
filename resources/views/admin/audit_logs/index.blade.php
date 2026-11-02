@extends('layouts.admin')

@section('title', 'Log Audit Keamanan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-brand-muted mb-1">
                <span class="text-rose-400">KEAMANAN & KEPATUHAN</span>
                <span>/</span>
                <span>LOG AUDIT</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2.5">
                <svg class="w-6 h-6 text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Audit Trail & Keamanan Sistem</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Rekam jejak setiap aksi administratif: ekspor data, modifikasi kompetisi, entri pendaftaran, dan mutasi akun.</p>
        </div>
    </div>

    <!-- Security Notice -->
    <div class="p-4 rounded-xl bg-slate-900/60 border border-brand-border flex items-center justify-between text-xs">
        <div class="flex items-center gap-3">
            <span class="text-emerald-400 font-mono">● ENCRYPTED AUDIT RECORD</span>
            <span class="text-slate-400">Log audit dilindungi dari modifikasi langsung dan hanya dapat diakses oleh Ketua Divisi & Super Admin.</span>
        </div>
        <span class="font-mono text-brand-cyan text-[11px]">{{ $logs->total() }} Log Tercatat</span>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-xl bg-brand-surface border border-brand-border">
        <form method="GET" action="{{ route('admin.audit_logs.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div class="sm:col-span-2">
                <label class="block font-mono text-slate-400 mb-1">PENCARIAN DESKRIPSI / PENGGUNA / IP</label>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama admin, IP address, atau aksi..." class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
            </div>

            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block font-mono text-slate-400 mb-1">JENIS AKSI</label>
                    <select name="action" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="all">Semua Aksi</option>
                        @foreach($actions as $act)
                            <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>{{ $act }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-white font-mono border border-brand-border">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Log Table -->
    <div class="rounded-2xl bg-brand-surface border border-brand-border overflow-hidden">
        @if($logs->isEmpty())
            <div class="p-16 text-center">
                <p class="text-xs text-slate-400">Tidak ada log aktivitas audit yang sesuai kriteria pencarian.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm font-mono">
                    <thead class="bg-slate-900/80 text-xs uppercase text-brand-muted border-b border-brand-border">
                        <tr>
                            <th class="px-5 py-3">Waktu (WIB)</th>
                            <th class="px-4 py-3">Pelaku / Aktor</th>
                            <th class="px-4 py-3">Aksi</th>
                            <th class="px-4 py-3">Keterangan / Objek</th>
                            <th class="px-4 py-3 text-right">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-border/60 text-xs">
                        @foreach($logs as $log)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="px-5 py-3.5 text-slate-400 whitespace-nowrap">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    @if($log->user)
                                        <span class="font-bold text-white font-sans">{{ $log->user->name }}</span>
                                        <span class="text-[10px] text-brand-cyan block">({{ strtoupper($log->user->role) }})</span>
                                    @else
                                        <span class="text-slate-500 italic">Sistem / Anonim</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[11px] border
                                        {{ str_contains($log->action, 'delete') ? 'bg-rose-500/10 text-rose-400 border-rose-500/30' :
                                           (str_contains($log->action, 'create') || str_contains($log->action, 'manual') ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' :
                                           (str_contains($log->action, 'export') ? 'bg-amber-500/10 text-amber-400 border-amber-500/30' : 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30')) }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-slate-200 font-sans">
                                    {{ $log->description }}
                                    @if($log->entity_type && $log->entity_id)
                                        <span class="text-[10px] font-mono text-slate-500 block">Target: {{ $log->entity_type }} #{{ $log->entity_id }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right text-slate-400 whitespace-nowrap">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-brand-border">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
