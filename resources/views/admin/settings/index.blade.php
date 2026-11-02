@extends('layouts.admin')

@section('title', 'Pengaturan Sistem & Branding')

@section('content')
<div class="max-w-4xl space-y-6" x-data="{
    wMinat: {{ old('scoring_weight_interest', $settings['scoring_weight_interest'] ?? 45) }},
    wAktivitas: {{ old('scoring_weight_activity', $settings['scoring_weight_activity'] ?? 20) }},
    wPengalaman: {{ old('scoring_weight_experience', $settings['scoring_weight_experience'] ?? 20) }},
    wBelajar: {{ old('scoring_weight_learning', $settings['scoring_weight_learning'] ?? 15) }},
    get totalWeight() {
        return (parseFloat(this.wMinat) || 0) + (parseFloat(this.wAktivitas) || 0) + (parseFloat(this.wPengalaman) || 0) + (parseFloat(this.wBelajar) || 0);
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-mono text-brand-muted mb-1">
                <span class="text-brand-purple">SUPER ADMIN</span>
                <span>/</span>
                <span>KONFIGURASI SISTEM</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Pengaturan Identitas & Bobot Scoring</h1>
            <p class="text-xs text-slate-400 mt-1">Ubah identitas resmi organisasi, tautan media sosial, serta bobot 4 dimensi penilaian minat bakat mahasiswa.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf

        <!-- 1. Identitas Organisasi & Branding -->
        <div class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-5">
            <div class="border-b border-brand-border/60 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-white">Identitas Platform & Universitas</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Dapat disesuaikan tanpa perlu mengubah source code</p>
                </div>
                <span class="text-xs font-mono text-brand-cyan">Branding Config</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-mono uppercase text-slate-300 mb-1">Nama Panjang Aplikasi *</label>
                    <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? 'MINBAK TI — Talent & Competition Hub') }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-300 mb-1">Nama Pendek (Singkatan) *</label>
                    <input type="text" name="app_short_name" value="{{ old('app_short_name', $settings['app_short_name'] ?? 'MINBAK TI') }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-300 mb-1">Nama Organisasi Mahasiswa *</label>
                    <input type="text" name="org_name" value="{{ old('org_name', $settings['org_name'] ?? 'Himpunan Mahasiswa Teknik Informatika') }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-300 mb-1">Nama Universitas / Institusi *</label>
                    <input type="text" name="univ_name" value="{{ old('univ_name', $settings['univ_name'] ?? 'Universitas Teknologi') }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
            </div>
        </div>

        <!-- 2. Kontak & Media Sosial (Instagram) -->
        <div class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-5">
            <div class="border-b border-brand-border/60 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-white">Kontak Resmi & Media Sosial</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Tautan resmi yang tampil pada footer publik dan template promosi</p>
                </div>
                <span class="text-xs font-mono text-purple-400">Social & Support</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-mono uppercase text-slate-300 mb-1">Email Resmi Pengurus *</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? 'minbak.ti@student.ac.id') }}" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-300 mb-1">Nomor Kontak / WhatsApp</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '+62 812-3456-7890') }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-300 mb-1">Instagram Handle</label>
                    <input type="text" name="instagram_handle" value="{{ old('instagram_handle', $settings['instagram_handle'] ?? '@minbak_ti') }}" placeholder="@username" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-300 mb-1">URL Profil Instagram</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? 'https://instagram.com/minbak_ti') }}" placeholder="https://instagram.com/..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">
                </div>
            </div>
        </div>

        <!-- 3. Konfigurasi Scoring Engine Asesmen (4 Dimensi Heuristik) -->
        <div class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-5">
            <div class="border-b border-brand-border/60 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-white">Bobot Penilaian Asesmen Minat (Scoring Engine)</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Penetapan bobot heuristik untuk 4 dimensi penentuan rekomendasi bidang mahasiswa</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-mono" :class="Math.abs(totalWeight - 100) < 0.01 ? 'text-emerald-400' : 'text-rose-400 font-bold'">
                        Total: <span x-text="totalWeight"></span>%
                    </span>
                    <span x-show="Math.abs(totalWeight - 100) < 0.01" class="text-emerald-400 text-xs">✓ Valid</span>
                    <span x-show="Math.abs(totalWeight - 100) >= 0.01" class="text-rose-400 text-xs">✗ Harus 100%</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div class="p-4 rounded-xl bg-slate-900/60 border border-brand-border space-y-2">
                    <label class="block font-mono uppercase text-brand-purple font-semibold">1. Ketertarikan (%)</label>
                    <input type="number" step="1" min="0" max="100" name="scoring_weight_interest" x-model="wMinat" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white font-mono text-center text-lg font-bold">
                    <p class="text-[11px] text-slate-400 leading-tight">Bobot antusiasme responden terhadap bidang topik.</p>
                </div>

                <div class="p-4 rounded-xl bg-slate-900/60 border border-brand-border space-y-2">
                    <label class="block font-mono uppercase text-brand-cyan font-semibold">2. Aktivitas (%)</label>
                    <input type="number" step="1" min="0" max="100" name="scoring_weight_activity" x-model="wAktivitas" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white font-mono text-center text-lg font-bold">
                    <p class="text-[11px] text-slate-400 leading-tight">Kecocokan gaya kerja (individu vs tim, visual vs logika).</p>
                </div>

                <div class="p-4 rounded-xl bg-slate-900/60 border border-brand-border space-y-2">
                    <label class="block font-mono uppercase text-amber-400 font-semibold">3. Pengalaman (%)</label>
                    <input type="number" step="1" min="0" max="100" name="scoring_weight_experience" x-model="wPengalaman" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white font-mono text-center text-lg font-bold">
                    <p class="text-[11px] text-slate-400 leading-tight">Pengalaman membuat proyek atau organisasi terkait.</p>
                </div>

                <div class="p-4 rounded-xl bg-slate-900/60 border border-brand-border space-y-2">
                    <label class="block font-mono uppercase text-emerald-400 font-semibold">4. Orientasi Belajar (%)</label>
                    <input type="number" step="1" min="0" max="100" name="scoring_weight_learning" x-model="wBelajar" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white font-mono text-center text-lg font-bold">
                    <p class="text-[11px] text-slate-400 leading-tight">Keinginan mencoba hal baru & mengeksplorasi semester ini.</p>
                </div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-900/40 border border-brand-border text-xs text-slate-400">
                <strong class="text-slate-300">Catatan Metodologi:</strong> Bobot di atas merupakan heuristik produk awal (default: 45/20/20/15) yang dapat disesuaikan pengurus. Setiap hasil asesmen menyimpan snapshot bobot pada saat dihitung agar hasil masa lalu tidak terdistorsi.
            </div>
        </div>

        <!-- 4. Visi & Misi Divisi -->
        <div class="p-6 sm:p-8 rounded-2xl bg-brand-surface border border-brand-border space-y-5">
            <div class="border-b border-brand-border/60 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-white">Visi, Misi & Program Divisi Minat Bakat</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Teks yang ditampilkan pada halaman publik "Tentang Kami"</p>
                </div>
            </div>

            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-mono uppercase text-slate-300 mb-1">Visi Divisi</label>
                    <textarea name="about_vision" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">{{ old('about_vision', $settings['about_vision'] ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-300 mb-1">Misi Divisi</label>
                    <textarea name="about_mission" rows="4" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">{{ old('about_mission', $settings['about_mission'] ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block font-mono uppercase text-slate-300 mb-1">Program Kerja Unggulan</label>
                    <textarea name="about_division_programs" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white focus:outline-none focus:ring-1 focus:ring-brand-cyan">{{ old('about_division_programs', $settings['about_division_programs'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <button type="submit" :disabled="Math.abs(totalWeight - 100) >= 0.01" class="px-8 py-3 rounded-xl bg-brand-cyan text-brand-navy font-bold text-sm hover:bg-cyan-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-glow-cyan">
                Simpan Konfigurasi Sistem
            </button>
        </div>
    </form>
</div>
@endsection
