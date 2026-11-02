@extends('layouts.admin')

@section('title', $student->full_name . ' — Profil Anggota')
@section('page_title', 'Profil Mahasiswa & Pemetaan Talenta')

@section('content')
<div class="space-y-6" x-data="{ tab: 'dasar' }">
    <!-- Header Card -->
    <div class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-purple to-brand-cyan flex items-center justify-center text-white text-2xl font-bold shadow-glow-combined">
                {{ substr($student->full_name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-bold text-white">{{ $student->full_name }}</h2>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-mono uppercase font-bold {{ $student->is_verified ? 'bg-emerald-950 text-emerald-300 border border-emerald-500/40' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                        {{ $student->is_verified ? 'TERVERIFIKASI' : 'BELUM DIVERIFIKASI' }}
                    </span>
                </div>
                <p class="text-xs font-mono text-brand-cyan mt-0.5">
                    NIM: {{ $student->nim ?? '-' }} &bull; Angkatan: {{ $student->angkatan ?? '-' }} &bull; Email: {{ $student->email }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.students.index') }}" class="px-4 py-2 rounded-xl text-xs font-medium border border-brand-border text-slate-300 hover:text-white hover:bg-slate-800">
                &larr; Kembali
            </a>
        </div>
    </div>

    <!-- 6 Tabs Navigation -->
    <div class="flex flex-wrap border-b border-brand-border gap-2 text-xs font-mono">
        <button type="button" @click="tab = 'dasar'" :class="tab === 'dasar' ? 'border-brand-cyan text-brand-cyan bg-brand-surface' : 'border-transparent text-slate-400 hover:text-white'" class="px-4 py-3 border-b-2 font-bold transition-colors">
            1. Informasi Dasar
        </button>
        <button type="button" @click="tab = 'minat'" :class="tab === 'minat' ? 'border-brand-cyan text-brand-cyan bg-brand-surface' : 'border-transparent text-slate-400 hover:text-white'" class="px-4 py-3 border-b-2 font-bold transition-colors">
            2. Minat & Hasil Asesmen
        </button>
        <button type="button" @click="tab = 'skill'" :class="tab === 'skill' ? 'border-brand-cyan text-brand-cyan bg-brand-surface' : 'border-transparent text-slate-400 hover:text-white'" class="px-4 py-3 border-b-2 font-bold transition-colors">
            3. Skill & Portofolio
        </button>
        <button type="button" @click="tab = 'kompetisi'" :class="tab === 'kompetisi' ? 'border-brand-cyan text-brand-cyan bg-brand-surface' : 'border-transparent text-slate-400 hover:text-white'" class="px-4 py-3 border-b-2 font-bold transition-colors">
            4. Riwayat Kompetisi
        </button>
        <button type="button" @click="tab = 'prestasi'" :class="tab === 'prestasi' ? 'border-brand-cyan text-brand-cyan bg-brand-surface' : 'border-transparent text-slate-400 hover:text-white'" class="px-4 py-3 border-b-2 font-bold transition-colors">
            5. Prestasi Terverifikasi
        </button>
        <button type="button" @click="tab = 'audit'" :class="tab === 'audit' ? 'border-brand-cyan text-brand-cyan bg-brand-surface' : 'border-transparent text-slate-400 hover:text-white'" class="px-4 py-3 border-b-2 font-bold transition-colors">
            6. Catatan Administratif
        </button>
    </div>

    <!-- Tab 1: Informasi Dasar -->
    <div x-show="tab === 'dasar'" class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-6">
        <h3 class="text-base font-bold text-white">Edit Informasi Anggota</h3>
        <form method="POST" action="{{ route('admin.students.update', $student->id) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Nama Lengkap *</label>
                    <input type="text" name="full_name" value="{{ $student->full_name }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">NIM</label>
                    <input type="text" name="nim" value="{{ $student->nim }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ $student->email }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Angkatan *</label>
                    <input type="text" name="angkatan" value="{{ $student->angkatan }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">No. WhatsApp</label>
                    <input type="text" name="phone" value="{{ $student->phone }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
                <div>
                    <label class="block font-mono uppercase text-slate-400 mb-1">Akun Instagram</label>
                    <input type="text" name="instagram_handle" value="{{ $student->instagram_handle }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-mono uppercase text-slate-400 mb-1">Bio / Catatan Latar Belakang</label>
                    <textarea name="bio" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">{{ $student->bio }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-6 pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300">
                    <input type="checkbox" name="is_verified" value="1" {{ $student->is_verified ? 'checked' : '' }} class="rounded bg-slate-900 text-brand-cyan">
                    <span>Status Terverifikasi</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300">
                    <input type="checkbox" name="consent_achievement_public" value="1" {{ $student->consent_achievement_public ? 'checked' : '' }} class="rounded bg-slate-900 text-brand-cyan">
                    <span>Izin Publikasi Prestasi Publik</span>
                </label>
            </div>

            <div class="pt-4 border-t border-brand-border/60 flex justify-end">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Tab 2: Minat & Hasil Asesmen -->
    <div x-show="tab === 'minat'" class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-6" style="display:none;">
        <h3 class="text-base font-bold text-white">Pemetaan Minat & Hasil Asesmen Talenta</h3>

        <!-- Assessment Results History -->
        <div class="space-y-4">
            <span class="text-xs font-mono uppercase text-slate-400 block">Riwayat Pengisian Asesmen:</span>
            @forelse($student->surveyResponses as $sResp)
                <div class="p-4 rounded-xl bg-slate-900 border border-brand-border space-y-3">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-white">{{ $sResp->campaign?->title }}</span>
                        <span class="font-mono text-slate-400">{{ $sResp->created_at->format('d M Y H:i') }}</span>
                    </div>

                    @if($sResp->result)
                        <div class="p-3 rounded-lg bg-slate-950 border border-brand-border/80 text-xs flex justify-between items-center">
                            <div>
                                <span class="text-slate-400 block text-[10px]">Rekomendasi Teratas:</span>
                                <span class="font-bold text-brand-cyan text-sm">{{ $sResp->result->primaryCategory?->name }}</span>
                            </div>
                            <a href="{{ route('assessment.result', $sResp->access_token) }}" target="_blank" class="px-3 py-1.5 rounded bg-brand-surface border border-brand-cyan text-brand-cyan hover:bg-slate-800 text-xs">
                                Buka Rekomendasi Lengkap &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-xs text-slate-400 py-4 italic">Belum ada riwayat pengisian asesmen oleh mahasiswa ini.</p>
            @endforelse
        </div>
    </div>

    <!-- Tab 3: Skill & Portofolio -->
    <div x-show="tab === 'skill'" class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-6" style="display:none;">
        <h3 class="text-base font-bold text-white">Keahlian & Tautan Portofolio</h3>

        <div class="space-y-3">
            <span class="text-xs font-mono uppercase text-slate-400 block">Daftar Keahlian:</span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse($student->skills as $sk)
                    <div class="p-3.5 rounded-xl bg-slate-900 border border-brand-border flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-white block">{{ $sk->skill_name }}</span>
                            <span class="text-[10px] font-mono uppercase text-brand-muted">{{ $sk->proficiency_level }} &bull; {{ $sk->tools }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 col-span-2 py-4 italic">Belum ada keahlian yang terdata.</p>
                @endforelse
            </div>
        </div>

        <div class="pt-4 border-t border-brand-border/60 text-xs space-y-2">
            <div>GitHub: <a href="{{ $student->github_url }}" target="_blank" class="text-brand-cyan hover:underline">{{ $student->github_url ?: '-' }}</a></div>
            <div>Portofolio: <a href="{{ $student->portfolio_url }}" target="_blank" class="text-brand-cyan hover:underline">{{ $student->portfolio_url ?: '-' }}</a></div>
        </div>
    </div>

    <!-- Tab 4: Riwayat Kompetisi -->
    <div x-show="tab === 'kompetisi'" class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-6" style="display:none;">
        <h3 class="text-base font-bold text-white">Riwayat Keikutsertaan Kompetisi</h3>

        <div class="space-y-3">
            @forelse($student->registrations as $cReg)
                <div class="p-4 rounded-xl bg-slate-900 border border-brand-border flex justify-between items-center text-xs">
                    <div>
                        <span class="font-bold text-white block text-sm">{{ $cReg->competition?->title }}</span>
                        <span class="text-slate-400 font-mono text-[10px]">Jalur: {{ $cReg->registration_source }} &bull; Tanggal: {{ $cReg->created_at->format('d M Y') }}</span>
                    </div>
                    <span class="px-2.5 py-1 rounded text-[10px] font-mono font-bold uppercase {{ $cReg->status_badge_class }}">
                        {{ strtoupper($cReg->status) }}
                    </span>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-4 italic">Mahasiswa ini belum memiliki riwayat pendaftaran kompetisi.</p>
            @endforelse
        </div>
    </div>

    <!-- Tab 5: Prestasi Terverifikasi -->
    <div x-show="tab === 'prestasi'" class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-6" style="display:none;">
        <h3 class="text-base font-bold text-white">Prestasi Resmi Mahasiswa</h3>

        <div class="space-y-3">
            @forelse($student->achievements as $ach)
                <div class="p-4 rounded-xl bg-slate-900 border border-brand-border flex justify-between items-center text-xs">
                    <div>
                        <span class="font-bold text-white block text-sm">{{ $ach->achievement_title }}</span>
                        <span class="text-brand-cyan block">{{ $ach->competition?->title }}</span>
                        <span class="text-slate-400 font-mono text-[10px]">Tingkat: {{ $ach->level }} &bull; Publik: {{ $ach->is_public_published ? 'Ditayangkan' : 'Internal Saja' }}</span>
                    </div>
                    <span class="px-2.5 py-1 rounded text-[10px] font-mono font-bold uppercase {{ $ach->status_badge_class }}">
                        {{ strtoupper($ach->verification_status) }}
                    </span>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-4 italic">Belum ada prestasi terverifikasi untuk mahasiswa ini.</p>
            @endforelse
        </div>
    </div>

    <!-- Tab 6: Catatan Administratif & Audit -->
    <div x-show="tab === 'audit'" class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-4 text-xs text-slate-300" style="display:none;">
        <h3 class="text-base font-bold text-white">Catatan Administratif & Integritas</h3>
        <p>Record ID Internal: <span class="font-mono text-white">#{{ $student->id }}</span></p>
        <p>Akun Pengguna Terhubung: <span class="font-mono text-white">{{ $student->user ? $student->user->email : 'Belum Ditautkan' }}</span></p>
        <p>Persetujuan Pemrosesan Data: <span class="font-mono text-emerald-400">{{ $student->consent_data_processing ? 'Diberikan' : 'Belum' }}</span></p>
        <p>Dibuat pada: <span class="font-mono text-white">{{ $student->created_at->format('d M Y H:i:s') }}</span></p>
        <p>Terakhir diperbarui: <span class="font-mono text-white">{{ $student->updated_at->format('d M Y H:i:s') }}</span></p>
    </div>
</div>
@endsection
