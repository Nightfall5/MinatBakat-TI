@extends('layouts.admin')

@section('title', 'Manajemen Kompetisi')
@section('page_title', 'Operasional & Manajemen Kompetisi')

@section('content')
<div class="space-y-6">
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('admin.competitions.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold bg-brand-cyan text-brand-navy hover:bg-cyan-300 text-xs shadow-glow-cyan transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Buat Kompetisi Baru</span>
        </a>

        <span class="text-xs font-mono text-slate-400">Total: {{ $competitions->total() }} Kompetisi Terdaftar</span>
    </div>

    <!-- Filter Form -->
    <div class="p-5 rounded-2xl bg-brand-surface border border-brand-border">
        <form method="GET" action="{{ route('admin.competitions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            <div class="lg:col-span-2">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama kompetisi atau penyelenggara..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
            </div>

            <div>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    <option value="all">Semua Status</option>
                    <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Pendaftaran Buka</option>
                    <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>Pendaftaran Tutup</option>
                    <option value="ongoing" {{ $status === 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-brand-purple text-white font-bold hover:bg-purple-600 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.competitions.index') }}" class="p-2.5 rounded-xl border border-brand-border text-slate-400 hover:text-white transition-colors" title="Reset">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </a>
            </div>
        </form>
    </div>

    <!-- Competitions Table -->
    <div class="rounded-2xl bg-brand-surface border border-brand-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-brand-border/80 bg-slate-900/60 font-mono uppercase text-slate-400">
                        <th class="p-4">Kompetisi</th>
                        <th class="p-4">Penyelenggara & Tingkat</th>
                        <th class="p-4">Format & Tim</th>
                        <th class="p-4">Tenggat Pendaftaran</th>
                        <th class="p-4">Pendaftar</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border/60">
                    @forelse($competitions as $comp)
                        <tr class="hover:bg-slate-900/40 transition-colors">
                            <td class="p-4">
                                <a href="{{ route('admin.competitions.show', $comp->id) }}" class="font-bold text-white hover:text-brand-cyan text-sm block">
                                    {{ $comp->title }}
                                </a>
                                <span class="text-slate-400 text-[11px] font-mono">Kategori: {{ $comp->category?->name ?? 'Umum' }}</span>
                            </td>
                            <td class="p-4">
                                <span class="text-white block">{{ $comp->organizer }}</span>
                                <span class="text-[10px] font-mono uppercase px-2 py-0.5 rounded {{ $comp->level_badge_class }}">
                                    {{ $comp->level }}
                                </span>
                            </td>
                            <td class="p-4 font-mono">
                                <span class="capitalize text-slate-200 block">{{ $comp->format }}</span>
                                <span class="text-slate-400 text-[11px]">{{ $comp->team_type === 'team' ? "Tim ({$comp->min_team_members}-{$comp->max_team_members})" : 'Individu' }}</span>
                            </td>
                            <td class="p-4 font-mono">
                                <span class="text-white block">{{ $comp->registration_deadline?->format('d M Y') ?? 'TBA' }}</span>
                                <span class="text-slate-400 text-[11px]">{{ $comp->registration_deadline?->diffForHumans() }}</span>
                            </td>
                            <td class="p-4 font-mono font-bold text-brand-cyan">
                                {{ $comp->registrations_count }} Peserta
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded text-[10px] font-mono uppercase font-bold {{ $comp->status === 'open' ? 'bg-emerald-950 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                                    {{ $comp->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.competitions.show', $comp->id) }}" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-brand-cyan" title="Buka Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.competitions.edit', $comp->id) }}" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-purple-300" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.competitions.destroy', $comp->id) }}" onsubmit="return confirm('Hapus kompetisi {{ $comp->title }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-slate-800 hover:bg-rose-950 text-slate-400 hover:text-rose-400" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">
                                Tidak ada data kompetisi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-brand-border/80">
            {{ $competitions->links() }}
        </div>
    </div>
</div>
@endsection
