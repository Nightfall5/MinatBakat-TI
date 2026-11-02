@extends('layouts.admin')

@section('title', 'Manajemen Pengurus & Role')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-brand-muted mb-1">
                <span class="text-brand-purple">SUPER ADMIN</span>
                <span>/</span>
                <span>PENGURUS & ROLE</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Manajemen Pengurus & Hak Akses</h1>
            <p class="text-xs text-slate-400 mt-1">Kelola akun staf, ketua divisi, dan penetapan role sesuai matriks Role-Based Access Control (RBAC).</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.create') }}" class="px-4 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 transition-colors flex items-center gap-2 shadow-glow-cyan">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span>+ Tambah Pengurus Baru</span>
            </a>
        </div>
    </div>

    <!-- RBAC Matrix Legend -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
        <div class="p-3.5 rounded-xl bg-purple-950/30 border border-brand-purple/30">
            <div class="flex items-center gap-2 text-brand-purple font-mono font-bold uppercase">
                <span class="w-2 h-2 rounded-full bg-brand-purple"></span>
                <span>Super Admin</span>
            </div>
            <p class="text-[11px] text-slate-300 mt-1">Akses penuh sistem, konfigurasi, audit log, dan kelola pengurus.</p>
        </div>

        <div class="p-3.5 rounded-xl bg-blue-950/30 border border-blue-500/30">
            <div class="flex items-center gap-2 text-blue-400 font-mono font-bold uppercase">
                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                <span>Ketua Divisi</span>
            </div>
            <p class="text-[11px] text-slate-300 mt-1">Operasional divisi, ekspor CSV, audit log, dan persetujuan.</p>
        </div>

        <div class="p-3.5 rounded-xl bg-cyan-950/30 border border-brand-cyan/30">
            <div class="flex items-center gap-2 text-brand-cyan font-mono font-bold uppercase">
                <span class="w-2 h-2 rounded-full bg-brand-cyan"></span>
                <span>Staff Minbak</span>
            </div>
            <p class="text-[11px] text-slate-300 mt-1">Entri pendaftaran, CRUD lomba, pencatatan prestasi, data anggota.</p>
        </div>

        <div class="p-3.5 rounded-xl bg-slate-900/60 border border-slate-700">
            <div class="flex items-center gap-2 text-slate-400 font-mono font-bold uppercase">
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                <span>Mahasiswa</span>
            </div>
            <p class="text-[11px] text-slate-300 mt-1">Portal mandiri, asesmen minat, dan riwayat partisipasi sendiri.</p>
        </div>
    </div>

    <!-- Users Table -->
    <div class="rounded-2xl bg-brand-surface border border-brand-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-900/80 text-xs font-mono uppercase text-brand-muted border-b border-brand-border">
                    <tr>
                        <th class="px-5 py-3">Nama Pengurus</th>
                        <th class="px-4 py-3">Email & Kontak</th>
                        <th class="px-4 py-3">Role Hak Akses</th>
                        <th class="px-4 py-3">Status Akun</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border/60">
                    @foreach($users as $u)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-bold text-white flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-slate-800 border border-brand-border flex items-center justify-center font-bold text-xs text-brand-cyan">
                                        {{ substr($u->name, 0, 1) }}
                                    </div>
                                    <span>{{ $u->name }}</span>
                                    @if($u->id === auth()->id())
                                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-brand-cyan/20 text-brand-cyan border border-brand-cyan/30">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-xs font-mono">
                                <span class="text-slate-200">{{ $u->email }}</span>
                                <p class="text-slate-500 mt-0.5">{{ $u->phone ?? 'Tanpa nomor kontak' }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs font-mono px-2.5 py-1 rounded-full border inline-block
                                    {{ $u->role === 'super_admin' ? 'bg-purple-500/10 text-purple-400 border-purple-500/30 font-bold' :
                                       ($u->role === 'ketua_divisi' ? 'bg-blue-500/10 text-blue-400 border-blue-500/30' :
                                       ($u->role === 'staff' ? 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30' : 'bg-slate-500/10 text-slate-400 border-slate-500/30')) }}">
                                    {{ strtoupper(str_replace('_', ' ', $u->role)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs font-mono px-2.5 py-1 rounded-full border inline-block
                                    {{ $u->is_active ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-rose-500/10 text-rose-400 border-rose-500/30' }}">
                                    {{ $u->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2" x-data="{ editModal: false }">
                                    <button @click="editModal = true" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-brand-border text-xs font-mono transition-colors">
                                        Edit
                                    </button>

                                    @if($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle_active', $u->id) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg border text-xs font-mono transition-colors {{ $u->is_active ? 'border-amber-500/30 text-amber-400 hover:bg-amber-950/20' : 'border-emerald-500/30 text-emerald-400 hover:bg-emerald-950/20' }}">
                                                {{ $u->is_active ? 'Suspend' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Quick Edit Modal -->
                                    <div x-show="editModal" 
                                         x-transition
                                         @keydown.escape.window="editModal = false"
                                         class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 text-left"
                                         style="display: none;">
                                        <div class="max-w-md w-full bg-brand-surface border border-brand-border rounded-2xl p-6 space-y-4 shadow-2xl relative" @click.outside="editModal = false">
                                            <div class="flex justify-between items-center border-b border-brand-border pb-3">
                                                <h3 class="text-base font-bold text-white">Edit Akun: {{ $u->name }}</h3>
                                                <button @click="editModal = false" class="text-slate-400 hover:text-white">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>

                                            <form method="POST" action="{{ route('admin.users.update', $u->id) }}" class="space-y-3 text-xs">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="block font-mono text-slate-300 uppercase mb-1">Nama Lengkap</label>
                                                    <input type="text" name="name" value="{{ $u->name }}" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white">
                                                </div>

                                                <div>
                                                    <label class="block font-mono text-slate-300 uppercase mb-1">Email</label>
                                                    <input type="email" name="email" value="{{ $u->email }}" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white">
                                                </div>

                                                <div>
                                                    <label class="block font-mono text-slate-300 uppercase mb-1">Role Akses</label>
                                                    <select name="role" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white">
                                                        <option value="super_admin" {{ $u->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                                        <option value="ketua_divisi" {{ $u->role === 'ketua_divisi' ? 'selected' : '' }}>Ketua Divisi</option>
                                                        <option value="staff" {{ $u->role === 'staff' ? 'selected' : '' }}>Staff Minat & Bakat</option>
                                                        <option value="mahasiswa" {{ $u->role === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block font-mono text-slate-300 uppercase mb-1">Ganti Password (Kosongkan jika tidak diubah)</label>
                                                    <input type="password" name="password" placeholder="Min. 8 karakter" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white">
                                                </div>

                                                <label class="flex items-center gap-2 pt-1 cursor-pointer">
                                                    <input type="checkbox" name="is_active" value="1" {{ $u->is_active ? 'checked' : '' }} class="rounded bg-slate-800 border-brand-border text-brand-cyan">
                                                    <span class="text-slate-300">Akun Aktif</span>
                                                </label>

                                                <div class="flex justify-end gap-2 pt-3 border-t border-brand-border">
                                                    <button type="button" @click="editModal = false" class="px-3 py-2 rounded-lg border border-brand-border text-slate-400">Batal</button>
                                                    <button type="submit" class="px-4 py-2 rounded-lg bg-brand-cyan text-brand-navy font-bold font-mono">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-brand-border">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
