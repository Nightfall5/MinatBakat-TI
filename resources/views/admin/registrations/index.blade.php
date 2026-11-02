@extends('layouts.admin')

@section('title', 'Pendaftaran Peserta Kompetisi')

@section('content')
<div class="space-y-6">
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-brand-muted mb-1">
                <span class="text-brand-cyan">OPERASIONAL</span>
                <span>/</span>
                <span>PENDAFTARAN KOMPETISI</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Pendaftaran Peserta & Entri Manual</h1>
            <p class="text-xs text-slate-400 mt-1">Kelola seluruh pengajuan keikutsertaan kompetisi dari portal mahasiswa, Instagram DM, maupun WhatsApp.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.registrations.create') }}" class="px-4 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 transition-colors flex items-center gap-2 shadow-glow-cyan">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Entri Manual (IG / WA / Offline)</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-xl bg-brand-surface border border-brand-border">
        <form method="GET" action="{{ route('admin.registrations.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            <div class="lg:col-span-2">
                <label class="block font-mono text-slate-400 mb-1">PENCARIAN MAHASISWA</label>
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, NIM, atau email..." class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
            </div>

            <div>
                <label class="block font-mono text-slate-400 mb-1">KOMPETISI</label>
                <select name="competition_id" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    <option value="all">Semua Kompetisi</option>
                    @foreach($competitions as $comp)
                        <option value="{{ $comp->id }}" {{ $competitionId == $comp->id ? 'selected' : '' }}>{{ Str::limit($comp->title, 28) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-mono text-slate-400 mb-1">SUMBER DAFTAR</label>
                <select name="source" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    <option value="all">Semua Sumber</option>
                    <option value="instagram_dm" {{ $source === 'instagram_dm' ? 'selected' : '' }}>Instagram DM</option>
                    <option value="whatsapp" {{ $source === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                    <option value="web_self" {{ $source === 'web_self' ? 'selected' : '' }}>Portal Mahasiswa</option>
                    <option value="offline" {{ $source === 'offline' ? 'selected' : '' }}>Offline</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block font-mono text-slate-400 mb-1">STATUS</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="all">Semua Status</option>
                        <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="under_review" {{ $status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="competing" {{ $status === 'competing' ? 'selected' : '' }}>Competing</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-white font-mono border border-brand-border">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Registrations Table -->
    <div class="rounded-2xl bg-brand-surface border border-brand-border overflow-hidden">
        @if($registrations->isEmpty())
            <div class="p-16 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-800 border border-brand-border flex items-center justify-center text-slate-500 mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h4 class="text-sm font-semibold text-white">Tidak Ada Pendaftaran Ditemukan</h4>
                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan filter atau tambahkan pendaftaran manual baru.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/80 text-xs font-mono uppercase text-brand-muted border-b border-brand-border">
                        <tr>
                            <th class="px-5 py-3">Mahasiswa</th>
                            <th class="px-4 py-3">Kompetisi</th>
                            <th class="px-4 py-3">Sumber & Tanggal</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Catatan Staf</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-border/60">
                        @foreach($registrations as $reg)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-white">
                                        <a href="{{ route('admin.students.show', $reg->student_profile_id) }}" class="hover:text-brand-cyan transition-colors">
                                            {{ $reg->profile?->full_name ?? 'Mahasiswa #' . $reg->student_profile_id }}
                                        </a>
                                    </div>
                                    <span class="text-xs font-mono text-slate-400">NIM: {{ $reg->profile?->nim ?? '-' }} &bull; Angk. {{ $reg->profile?->batch_year ?? '-' }}</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">WA: {{ $reg->profile?->phone_whatsapp ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <a href="{{ route('admin.competitions.show', $reg->competition_id) }}" class="font-medium text-slate-200 hover:text-brand-cyan transition-colors line-clamp-1">
                                        {{ $reg->competition?->title ?? '-' }}
                                    </a>
                                    <span class="text-xs font-mono text-slate-500">{{ $reg->competition?->organizer ?? '' }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-[11px] font-mono px-2 py-0.5 rounded border inline-block
                                        {{ $reg->registration_source === 'instagram_dm' ? 'bg-pink-500/10 text-pink-400 border-pink-500/30' :
                                           ($reg->registration_source === 'whatsapp' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' :
                                           ($reg->registration_source === 'web_self' ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30' : 'bg-slate-500/10 text-slate-400 border-slate-500/30')) }}">
                                        {{ strtoupper(str_replace('_', ' ', $reg->registration_source)) }}
                                    </span>
                                    <p class="text-[11px] font-mono text-slate-400 mt-1">{{ $reg->created_at->format('d/m/Y H:i') }}</p>
                                    @if($reg->staffRegistrar)
                                        <p class="text-[10px] text-slate-500">Staf: {{ $reg->staffRegistrar->name }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="text-xs font-mono px-2.5 py-1 rounded-full border inline-block
                                        {{ $reg->status === 'confirmed' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' :
                                           ($reg->status === 'under_review' ? 'bg-amber-500/10 text-amber-400 border-amber-500/30' :
                                           ($reg->status === 'competing' ? 'bg-purple-500/10 text-purple-400 border-purple-500/30' :
                                           ($reg->status === 'completed' ? 'bg-blue-500/10 text-blue-400 border-blue-500/30' :
                                           ($reg->status === 'rejected' ? 'bg-rose-500/10 text-rose-400 border-rose-500/30' : 'bg-slate-500/10 text-slate-400 border-slate-500/30')))) }}">
                                        {{ strtoupper($reg->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-xs text-slate-400 max-w-xs">
                                    {{ $reg->admin_notes ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.registrations.update_status', $reg->id) }}" class="inline-flex items-center gap-1.5">
                                            @csrf
                                            <select name="status" class="px-2 py-1 bg-slate-900 border border-brand-border rounded text-xs text-slate-200 focus:outline-none focus:border-brand-cyan">
                                                <option value="submitted" {{ $reg->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                                <option value="under_review" {{ $reg->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                                <option value="confirmed" {{ $reg->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="competing" {{ $reg->status === 'competing' ? 'selected' : '' }}>Competing</option>
                                                <option value="completed" {{ $reg->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="rejected" {{ $reg->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            <button type="submit" class="px-2 py-1 rounded bg-brand-purple/20 text-brand-purple border border-brand-purple/40 hover:bg-brand-purple/30 text-xs font-mono transition-colors">
                                                Simpan
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.registrations.destroy', $reg->id) }}" onsubmit="return confirm('Hapus data pendaftaran peserta ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-500 hover:text-rose-400 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-brand-border">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
