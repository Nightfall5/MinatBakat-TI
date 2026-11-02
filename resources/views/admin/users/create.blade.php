@extends('layouts.admin')

@section('title', 'Tambah Pengurus / Pengguna Baru')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-2 text-xs font-mono text-brand-muted">
        <a href="{{ route('admin.users.index') }}" class="hover:text-brand-cyan transition-colors">PENGURUS & ROLE</a>
        <span>/</span>
        <span class="text-brand-cyan">TAMBAH PENGGUNA</span>
    </div>

    <div class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-6">
        <div class="border-b border-brand-border/60 pb-4">
            <h2 class="text-lg font-bold text-white">Formulir Pendaftaran Pengurus Baru</h2>
            <p class="text-xs text-slate-400 mt-1">Akun yang dibuat akan langsung memiliki izin sesuai tingkatan role yang dipilih.</p>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Rian Pratama, S.Kom." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    @error('name')
                        <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Alamat Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@minbak.ti" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                    @error('email')
                        <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Role / Hak Akses *</label>
                    <select name="role" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff Minat & Bakat</option>
                        <option value="ketua_divisi" {{ old('role') === 'ketua_divisi' ? 'selected' : '' }}>Ketua Divisi Minbak</option>
                        <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Administrator</option>
                        <option value="mahasiswa" {{ old('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa (Anggota)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="081234567890" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
            </div>

            <div>
                <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Kata Sandi (Password) *</label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                <span class="text-[11px] text-slate-400">Password akan di-hash secara aman menggunakan algoritma bcrypt Laravel.</span>
                @error('password')
                    <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-3 p-3.5 rounded-xl bg-slate-900/60 border border-brand-border cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="rounded bg-slate-800 border-brand-border text-brand-cyan focus:ring-brand-cyan">
                    <div>
                        <span class="text-sm font-semibold text-white">Akun Langsung Aktif</span>
                        <p class="text-xs text-slate-400 mt-0.5">Pengguna dapat langsung login ke sistem dengan kredensial ini.</p>
                    </div>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-brand-border/60">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:bg-slate-800 text-xs font-medium">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 transition-colors shadow-glow-cyan">
                    Buat Akun Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
