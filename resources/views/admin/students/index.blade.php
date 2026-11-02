@extends('layouts.admin')

@section('title', 'Data Mahasiswa & Pemetaan Talenta')
@section('page_title', 'Database Mahasiswa & Pemetaan Talenta')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold bg-brand-cyan text-brand-navy hover:bg-cyan-300 text-xs shadow-glow-cyan transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Mahasiswa Manual</span>
            </a>

            @if(auth()->user()->isKetuaOrAbove())
                <a href="{{ route('admin.students.export', ['angkatan' => $angkatan, 'kategori' => $categorySlug]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold bg-brand-surface border border-brand-border hover:border-brand-cyan text-slate-200 text-xs transition-colors" title="Ekspor data mahasiswa hasil filter ke CSV">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Ekspor CSV (Terotorisasi)</span>
                </a>
            @endif
        </div>

        <span class="text-xs font-mono text-slate-400">Total: {{ $students->total() }} Mahasiswa Terdata</span>
    </div>

    <!-- Filter Form -->
    <div class="p-5 rounded-2xl bg-brand-surface border border-brand-border">
        <form method="GET" action="{{ route('admin.students.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            <!-- Search -->
            <div class="lg:col-span-2">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, NIM, email, atau WhatsApp..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:ring-1 focus:ring-brand-cyan focus:outline-none">
            </div>

            <!-- Angkatan -->
            <div>
                <select name="angkatan" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                    <option value="all">Semua Angkatan</option>
                    @foreach($angkatanList as $thn)
                        <option value="{{ $thn }}" {{ $angkatan === $thn ? 'selected' : '' }}>Angkatan {{ $thn }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kategori Minat -->
            <div>
                <select name="kategori" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                    <option value="all">Semua Minat</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ $categorySlug === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-brand-purple text-white font-bold hover:bg-purple-600 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.students.index') }}" class="p-2.5 rounded-xl border border-brand-border text-slate-400 hover:text-white transition-colors" title="Reset">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </a>
            </div>
        </form>
    </div>

    <!-- Student Table -->
    <div class="rounded-2xl bg-brand-surface border border-brand-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-brand-border/80 bg-slate-900/60 font-mono uppercase text-slate-400">
                        <th class="p-4">Mahasiswa</th>
                        <th class="p-4">NIM & Angkatan</th>
                        <th class="p-4">Kontak</th>
                        <th class="p-4">Minat Utama</th>
                        <th class="p-4">Keahlian</th>
                        <th class="p-4">Verifikasi</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border/60">
                    @forelse($students as $st)
                        <tr class="hover:bg-slate-900/40 transition-colors">
                            <td class="p-4">
                                <a href="{{ route('admin.students.show', $st->id) }}" class="font-bold text-white hover:text-brand-cyan text-sm block">
                                    {{ $st->full_name }}
                                </a>
                                <span class="text-slate-400 text-[11px] font-mono">{{ $st->email }}</span>
                            </td>
                            <td class="p-4 font-mono">
                                <span class="text-white block">{{ $st->nim ?? '-' }}</span>
                                <span class="text-slate-400">Angkatan {{ $st->angkatan ?? '-' }}</span>
                            </td>
                            <td class="p-4 font-mono text-[11px]">
                                <div>WA: {{ $st->phone ?? '-' }}</div>
                                <div class="text-slate-400">IG: {{ $st->instagram_handle ?? '-' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @forelse($st->interests as $in)
                                        <span class="px-2 py-0.5 rounded bg-brand-purple/20 text-purple-300 border border-brand-purple/30 text-[10px]">
                                            {{ $in->category?->name }}
                                        </span>
                                    @empty
                                        <span class="text-slate-500 italic">Belum terdata</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @forelse($st->skills as $sk)
                                        <span class="px-2 py-0.5 rounded bg-slate-900 text-slate-300 border border-slate-700 text-[10px]">
                                            {{ $sk->skill_name }}
                                        </span>
                                    @empty
                                        <span class="text-slate-500 italic">Belum terdata</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="p-4">
                                <form method="POST" action="{{ route('admin.students.toggle_verify', $st->id) }}">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded text-[10px] font-mono uppercase font-bold {{ $st->is_verified ? 'bg-emerald-950 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-400 border border-slate-700' }}" title="Klik untuk mengubah status verifikasi">
                                        {{ $st->is_verified ? 'TERVERIFIKASI' : 'BELUM' }}
                                    </button>
                                </form>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.students.show', $st->id) }}" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200" title="Buka Detail">
                                        <svg class="w-4 h-4 text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.students.destroy', $st->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa {{ $st->full_name }}?')">
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
                                Tidak ada data mahasiswa yang cocok dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-brand-border/80">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
