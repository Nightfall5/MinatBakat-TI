@extends('layouts.app')

@section('title', 'Portal Mahasiswa — MINBAK TI')

@section('content')
<div class="py-10 md:py-14" x-data="{ addSkillModal: false, achModal: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Top Profile Banner -->
        <div class="glass-panel rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-purple to-brand-cyan flex items-center justify-center text-white text-2xl font-bold shadow-glow-combined">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                        @if($profile->is_verified)
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono uppercase bg-emerald-950 text-emerald-300 border border-emerald-500/40">Terverifikasi</span>
                        @endif
                    </div>
                    <p class="text-xs text-brand-cyan font-mono mt-0.5">
                        NIM: {{ $profile->nim ?? 'Belum diisi' }} &bull; Angkatan: {{ $profile->angkatan ?? '-' }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1 max-w-xl">
                        {{ $profile->bio ?? 'Belum ada bio pengantar.' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('portal.profile') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-brand-surface border border-brand-border hover:border-brand-cyan text-slate-200 hover:text-white transition-colors">
                    Edit Profil Saya
                </a>
                <button type="button" @click="achModal = true" class="px-4 py-2 rounded-xl text-xs font-bold bg-amber-400 text-slate-950 hover:bg-amber-300 transition-colors">
                    + Ajukan Prestasi
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Competitions & Assessments -->
            <div class="lg:col-span-2 space-y-8">
                <!-- 1. Registered Competitions -->
                <div class="glass-panel rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Kompetisi yang Saya Ikuti</span>
                        </h2>
                        <a href="{{ route('competitions.index') }}" class="text-xs text-brand-cyan hover:underline">Cari Lomba Baru &rarr;</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($registrations as $reg)
                            <div class="p-4 rounded-xl bg-slate-900/60 border border-brand-border flex items-center justify-between gap-4">
                                <div class="truncate">
                                    <span class="text-[10px] font-mono uppercase px-2 py-0.5 rounded {{ $reg->competition?->level_badge_class }}">
                                        {{ $reg->competition?->level }}
                                    </span>
                                    <h4 class="text-sm font-bold text-white mt-1 truncate">
                                        <a href="{{ route('competitions.show', $reg->competition?->slug) }}" class="hover:text-brand-cyan">
                                            {{ $reg->competition?->title }}
                                        </a>
                                    </h4>
                                    <span class="text-[11px] font-mono text-slate-400">Jalur: {{ $reg->registration_source }} &bull; Tanggal: {{ $reg->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="px-2.5 py-1 rounded text-[11px] font-mono uppercase font-bold {{ $reg->status_badge_class }}">
                                        {{ strtoupper($reg->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-xs text-slate-400">
                                Belum ada riwayat pendaftaran kompetisi. <a href="{{ route('competitions.index') }}" class="text-brand-cyan underline">Temukan kompetisi sekarang</a>.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 2. Riwayat Hasil Asesmen -->
                <div class="glass-panel rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-purpleLight" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>Riwayat Hasil Asesmen Bakat</span>
                        </h2>
                        <a href="{{ route('assessment.index') }}" class="text-xs text-brand-purpleLight hover:underline">Ikuti Asesmen Baru &rarr;</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($assessmentResponses as $resp)
                            <div class="p-4 rounded-xl bg-slate-900/60 border border-brand-border flex items-center justify-between gap-4">
                                <div>
                                    <span class="text-xs font-bold text-white block">{{ $resp->campaign?->title }}</span>
                                    <span class="text-[11px] font-mono text-slate-400">
                                        Rekomendasi Utama: <strong class="text-brand-cyan">{{ $resp->result?->primaryCategory?->name ?? 'Dalam Proses' }}</strong> &bull; {{ $resp->created_at->format('d M Y') }}
                                    </span>
                                </div>
                                <a href="{{ route('assessment.result', $resp->access_token) }}" class="px-3 py-1.5 rounded-lg bg-brand-surface border border-brand-cyan text-brand-cyan text-xs font-semibold hover:bg-slate-800">
                                    Buka Hasil
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-8 text-xs text-slate-400">
                                Anda belum pernah mengisi survei asesmen pemetaan talenta. <a href="{{ route('assessment.index') }}" class="text-brand-cyan underline">Mulai asesmen</a>.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 3. Prestasi Terverifikasi Saya -->
                <div class="glass-panel rounded-2xl p-6">
                    <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        <span>Daftar Prestasi Saya</span>
                    </h2>

                    <div class="space-y-3">
                        @forelse($achievements as $ach)
                            <div class="p-4 rounded-xl bg-slate-900/60 border border-brand-border flex items-center justify-between gap-4">
                                <div>
                                    <span class="text-xs font-bold text-white block">{{ $ach->achievement_title }}</span>
                                    <span class="text-[11px] font-mono text-slate-400">{{ $ach->competition?->title }} &bull; Tingkat {{ $ach->level }}</span>
                                    @if($ach->proof_document_path)
                                        <a href="{{ route('portal.achievement.download_proof', $ach->id) }}" class="text-[10px] font-mono text-emerald-400 hover:underline block mt-1">
                                            📄 Unduh Berkas Bukti
                                        </a>
                                    @endif
                                </div>
                                <div>
                                    <span class="px-2.5 py-1 rounded text-[10px] font-mono uppercase font-bold {{ $ach->status_badge_class }}">
                                        {{ strtoupper($ach->verification_status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-xs text-slate-400">
                                Belum ada prestasi yang dicatat. Pernah menjuarai kompetisi? <button @click="achModal = true" class="text-amber-400 underline font-semibold">Ajukan klaim prestasi</button>.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Col: Skills & Suggestions -->
            <div class="space-y-6">
                <!-- Skills Card -->
                <div class="glass-panel rounded-2xl p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-white">Keahlian Saya</h3>
                        <button type="button" @click="addSkillModal = true" class="text-xs text-brand-cyan hover:underline font-semibold">+ Tambah</button>
                    </div>

                    <div class="space-y-2">
                        @forelse($skills as $skill)
                            <div class="p-3 rounded-xl bg-slate-900/80 border border-brand-border flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-white block">{{ $skill->skill_name }}</span>
                                    <span class="text-[10px] font-mono uppercase text-brand-muted">{{ $skill->proficiency_level }} &bull; {{ $skill->tools }}</span>
                                </div>
                                <form method="POST" action="{{ route('portal.skill.delete', $skill->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus keahlian ini?')" class="text-slate-500 hover:text-rose-400 text-xs">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-3 text-center">Belum ada keahlian yang ditambahkan.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Open Competitions Recommendation -->
                <div class="glass-panel rounded-2xl p-6">
                    <h3 class="text-base font-bold text-white mb-3">Rekomendasi Lomba Baru</h3>
                    <div class="space-y-3">
                        @foreach($openCompetitions as $oComp)
                            <a href="{{ route('competitions.show', $oComp->slug) }}" class="p-3 rounded-xl bg-slate-900/60 border border-brand-border hover:border-brand-cyan/40 block transition-colors">
                                <span class="text-[10px] font-mono uppercase text-brand-cyan">{{ $oComp->level }}</span>
                                <h4 class="text-xs font-bold text-white truncate">{{ $oComp->title }}</h4>
                                <span class="text-[10px] text-slate-400 block mt-1">Deadline: {{ $oComp->registration_deadline?->format('d M Y') }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Add Skill -->
    <div x-show="addSkillModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" style="display:none;">
        <div class="glass-panel rounded-2xl max-w-sm w-full p-6 space-y-4" @click.outside="addSkillModal = false">
            <h3 class="text-base font-bold text-white">Tambah Keahlian Baru</h3>
            <form method="POST" action="{{ route('portal.skill.add') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Nama Skill *</label>
                    <input type="text" name="skill_name" required placeholder="Contoh: Laravel / Flutter / Figma" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Tingkat Kemahiran *</label>
                    <select name="proficiency_level" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                        <option value="beginner">Beginner (Pemula)</option>
                        <option value="intermediate">Intermediate (Menengah)</option>
                        <option value="advanced">Advanced (Mahir)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Tools / Framework yang Digunakan</label>
                    <input type="text" name="tools" placeholder="Contoh: VS Code, Git, Docker" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="addSkillModal = false" class="px-3 py-1.5 rounded-lg border border-brand-border text-xs text-slate-300">Batal</button>
                    <button type="submit" class="px-4 py-1.5 rounded-lg bg-brand-cyan text-brand-navy font-bold text-xs">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Submit Achievement Verification -->
    <div x-show="achModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" style="display:none;">
        <div class="glass-panel rounded-2xl max-w-lg w-full p-6 sm:p-8 space-y-4" @click.outside="achModal = false">
            <h3 class="text-lg font-bold text-white">Ajukan Verifikasi Prestasi Lomba</h3>
            <p class="text-xs text-slate-400">Kirimkan rincian prestasi yang pernah kamu raih untuk diverifikasi oleh pengurus divisi.</p>
            <form method="POST" action="{{ route('portal.achievement.submit') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Kompetisi Terkait *</label>
                    <select name="competition_id" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                        <option value="">Pilih Kompetisi...</option>
                        @foreach(\App\Models\Competition::orderBy('title')->get() as $c)
                            <option value="{{ $c->id }}">{{ $c->title }} ({{ $c->level }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Gelar / Capaian *</label>
                        <input type="text" name="achievement_title" required placeholder="Juara 1 / Best UI/UX / Finalis" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Peringkat (Angka) *</label>
                        <input type="number" name="rank_position" min="1" max="100" value="1" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Tingkat Capaian *</label>
                    <select name="level" required class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                        <option value="internal_kampus">Internal Kampus</option>
                        <option value="regional">Regional</option>
                        <option value="nasional" selected>Nasional</option>
                        <option value="internasional">Internasional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Tautan Publikasi Resmi (Opsional)</label>
                    <input type="url" name="official_publication_url" placeholder="https://penyelenggara.com/pengumuman-juara" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white text-xs focus:ring-1 focus:ring-brand-cyan focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1">Bukti Sertifikat (PDF / JPG / PNG, Max 5MB)</label>
                    <input type="file" name="proof_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 rounded-lg bg-slate-900 border border-brand-border text-white text-xs">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="achModal = false" class="px-4 py-2 rounded-lg border border-brand-border text-xs text-slate-300">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-amber-400 text-slate-950 font-bold text-xs">Kirim untuk Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
