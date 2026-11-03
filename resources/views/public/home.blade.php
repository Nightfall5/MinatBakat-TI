@extends('layouts.app')

@section('title', 'MINBAK TI — Minat, Bakat & Prestasi')

@section('content')
<!-- Denty-inspired MINBAK TI landing hero -->
<section class="denty-hero relative">
    <div class="max-w-7xl mx-auto px-0 sm:px-2 lg:px-4">
        <div class="denty-hero-shell">
            <div class="denty-hero-copy denty-reveal">
                <div class="denty-eyebrow">
                    <span class="denty-eyebrow-dot" aria-hidden="true"></span>
                    <span>DIVISI MINAT & BAKAT HMIF · {{ date('Y') }}</span>
                </div>
                <h1 class="denty-hero-title">Temukan bidangmu.<br><span>Kembangkan potensimu.</span></h1>
                <p class="denty-hero-description">
                    Kenali kekuatan diri, jelajahi bidang yang kamu sukai, temukan kompetisi yang relevan,
                    dan bangun rekam jejak prestasi bersama komunitas Teknik Informatika.
                </p>
                <div class="denty-hero-actions">
                    <a href="{{ route('assessment.index') }}" class="denty-btn-primary">
                        <span>Mulai Asesmen</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                    </a>
                    <a href="{{ route('competitions.index') }}" class="denty-btn-secondary"><span>Jelajahi Kompetisi</span></a>
                </div>
                <div class="denty-hero-note">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v3m0 12v3m9-9h-3M6 12H3m15.36-6.36-2.12 2.12M7.76 16.24l-2.12 2.12m12.72 0-2.12-2.12M7.76 7.76 5.64 5.64"/></svg>
                    <span>Eksplorasi minat · Peluang kompetisi · Prestasi terverifikasi</span>
                </div>
            </div>
            <div class="denty-hero-art denty-reveal" aria-label="Pratinjau bidang minat mahasiswa">
                <div class="denty-art-orbit" aria-hidden="true"></div>
                <div class="denty-art-panel">
                    <div class="denty-art-kicker"><span>RUANG EKSPLORASI</span><span class="denty-art-status">Temukan jalurmu</span></div>
                    <h2 class="denty-art-title">Mulai dari rasa ingin tahu.</h2>
                    <p class="denty-art-subtitle">Pilih bidang yang ingin kamu kenali lebih jauh.</p>
                    <div class="denty-art-list">
                        @forelse($categories->take(3) as $index => $category)
                            <a href="{{ route('exploration.show', $category->slug) }}" class="denty-art-category">
                                <span class="denty-art-index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="denty-art-category-copy"><strong>{{ $category->name }}</strong><small>Jelajahi bidang dan keterampilannya</small></span>
                                <span class="denty-art-arrow" aria-hidden="true">↗</span>
                            </a>
                        @empty
                            <a href="{{ route('exploration.index') }}" class="denty-art-category">
                                <span class="denty-art-index">01</span><span class="denty-art-category-copy"><strong>Eksplorasi bidang minat</strong><small>Temukan jalur yang ingin kamu kembangkan</small></span><span class="denty-art-arrow" aria-hidden="true">↗</span>
                            </a>
                        @endforelse
                    </div>
                    <div class="denty-art-footer"><span>Minat menjadi langkah pertama.</span><strong>MINBAK TI</strong></div>
                </div>
                <a href="{{ route('competitions.index') }}" class="denty-floating-chip">
                    <span class="denty-floating-chip-mark" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12Z"/></svg>
                    </span>
                    <span><strong>Lihat peluang terbaru</strong><small>Temukan kompetisi yang terbuka</small></span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Live Operational Statistics Section -->
<section class="relative -mt-6 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            <!-- Stat 1: Mahasiswa Terdaftar -->
            <div class="glass-panel rounded-2xl p-6 text-center transform transition-transform hover:-translate-y-1">
                <div class="text-3xl sm:text-4xl font-extrabold text-brand-cyan mb-1 font-mono denty-stat-number" data-count-up="{{ $registeredStudentsCount }}">
                    {{ number_format($registeredStudentsCount) }}
                </div>
                <div class="text-xs sm:text-sm font-medium text-slate-400">
                    Mahasiswa Terdata
                </div>
            </div>

            <!-- Stat 2: Kompetisi Aktif -->
            <div class="glass-panel rounded-2xl p-6 text-center transform transition-transform hover:-translate-y-1">
                <div class="text-3xl sm:text-4xl font-extrabold text-brand-purpleLight mb-1 font-mono denty-stat-number" data-count-up="{{ $activeCompetitionsCount }}">
                    {{ number_format($activeCompetitionsCount) }}
                </div>
                <div class="text-xs sm:text-sm font-medium text-slate-400">
                    Kompetisi Dibuka
                </div>
            </div>

            <!-- Stat 3: Total Partisipasi -->
            <div class="glass-panel rounded-2xl p-6 text-center transform transition-transform hover:-translate-y-1">
                <div class="text-3xl sm:text-4xl font-extrabold text-emerald-400 mb-1 font-mono denty-stat-number" data-count-up="{{ $participationsCount }}">
                    {{ number_format($participationsCount) }}
                </div>
                <div class="text-xs sm:text-sm font-medium text-slate-400">
                    Aktivitas Partisipasi
                </div>
            </div>

            <!-- Stat 4: Prestasi Terverifikasi -->
            <div class="glass-panel rounded-2xl p-6 text-center transform transition-transform hover:-translate-y-1">
                <div class="text-3xl sm:text-4xl font-extrabold text-amber-400 mb-1 font-mono denty-stat-number" data-count-up="{{ $verifiedAchievementsCount }}">
                    {{ number_format($verifiedAchievementsCount) }}
                </div>
                <div class="text-xs sm:text-sm font-medium text-slate-400">
                    Prestasi Terverifikasi
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Katalog Bidang Minat Section -->
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-xs font-mono tracking-wider text-brand-cyan uppercase">Katalog Bakat & Minat</span>
                <h2 class="text-3xl font-extrabold text-white mt-1">Eksplorasi Bidang Keahlian</h2>
                <p class="text-slate-400 text-sm mt-2 max-w-xl">
                    Beragam jalur peminatan mulai dari rekayasa perangkat lunak, AI, keamanan siber, desain visual, hingga olahraga.
                </p>
            </div>
            <a href="{{ route('exploration.index') }}" class="mt-4 md:mt-0 inline-flex items-center gap-2 text-sm font-semibold text-brand-cyan hover:underline">
                <span>Lihat Semua 15 Bidang</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($categories as $category)
                <div class="glass-panel glass-panel-hover rounded-2xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-brand-surface border border-brand-border flex items-center justify-center text-brand-cyan mb-4 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">{{ $category->name }}</h3>
                        <p class="text-xs text-slate-400 leading-relaxed mb-4 line-clamp-3">
                            {{ $category->description }}
                        </p>
                    </div>

                    <div>
                        <!-- Skills tags preview -->
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            @foreach(array_slice($category->skills_array, 0, 3) as $skill)
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-slate-900/80 text-slate-300 border border-slate-700/60">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>

                        <a href="{{ route('exploration.show', $category->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-cyan hover:text-white transition-colors">
                            <span>Pelajari Jalur Ini</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-12 text-slate-400 text-sm">
                    Belum ada kategori bidang yang ditambahkan.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Kompetisi Terbaru Section -->
<section class="py-20 bg-brand-surface/40 border-y border-brand-border/60 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-xs font-mono tracking-wider text-brand-purpleLight uppercase">Peluang & Tantangan</span>
                <h2 class="text-3xl font-extrabold text-white mt-1">Kompetisi Terbaru</h2>
                <p class="text-slate-400 text-sm mt-2 max-w-xl">
                    Kurasi lomba teknologi, riset ilmiah, dan turnamen yang sedang membuka pendaftaran.
                </p>
            </div>
            <a href="{{ route('competitions.index') }}" class="mt-4 md:mt-0 inline-flex items-center gap-2 text-sm font-semibold text-brand-purpleLight hover:underline">
                <span>Lihat Semua Lomba</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($latestCompetitions as $comp)
                <div class="glass-panel glass-panel-purple-hover rounded-2xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="text-[11px] font-mono uppercase px-2.5 py-1 rounded-full {{ $comp->level_badge_class }}">
                                {{ strtoupper($comp->level) }}
                            </span>
                            <span class="text-xs font-mono text-slate-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Deadline: {{ $comp->registration_deadline?->format('d M Y') ?? 'TBA' }}
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2 hover:text-brand-cyan transition-colors">
                            <a href="{{ route('competitions.show', $comp->slug) }}">{{ $comp->title }}</a>
                        </h3>
                        <p class="text-xs text-brand-cyan font-medium mb-3">
                            Penyelenggara: {{ $comp->organizer }}
                        </p>
                        <p class="text-xs text-slate-400 leading-relaxed mb-4 line-clamp-2">
                            {{ $comp->description }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-brand-border/60 flex items-center justify-between text-xs">
                        <span class="text-slate-300 font-medium">
                            Format: <span class="capitalize text-white">{{ $comp->format }}</span> ({{ $comp->team_type === 'team' ? 'Tim' : 'Individu' }})
                        </span>
                        <a href="{{ route('competitions.show', $comp->slug) }}" class="inline-flex items-center gap-1.5 font-semibold text-brand-cyan hover:underline">
                            <span>Detail Lomba</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 text-slate-400 text-sm">
                    Saat ini belum ada kompetisi aktif yang terpublikasi.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Visualisasi Perjalanan Mahasiswa (Cara Kerja) -->
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-mono tracking-wider text-brand-cyan uppercase">Alur Pembinaan</span>
            <h2 class="text-3xl font-extrabold text-white mt-1">Perjalanan Mahasiswa di MINBAK TI</h2>
            <p class="text-slate-400 text-sm mt-2">
                Dari eksplorasi minat dasar hingga pengakuan prestasi tingkat universitas dan nasional.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <!-- Step 1 -->
            <div class="glass-panel rounded-xl p-4 text-center">
                <div class="w-8 h-8 rounded-full bg-brand-surface border border-brand-cyan/40 text-brand-cyan font-bold flex items-center justify-center mx-auto mb-3 text-xs">1</div>
                <h4 class="text-xs font-bold text-white mb-1">Eksplorasi</h4>
                <p class="text-[11px] text-slate-400">Pahami 15 kategori minat teknologi</p>
            </div>

            <!-- Step 2 -->
            <div class="glass-panel rounded-xl p-4 text-center">
                <div class="w-8 h-8 rounded-full bg-brand-surface border border-brand-purple/40 text-brand-purple font-bold flex items-center justify-center mx-auto mb-3 text-xs">2</div>
                <h4 class="text-xs font-bold text-white mb-1">Asesmen</h4>
                <p class="text-[11px] text-slate-400">Ikuti survei pemetaan talenta</p>
            </div>

            <!-- Step 3 -->
            <div class="glass-panel rounded-xl p-4 text-center">
                <div class="w-8 h-8 rounded-full bg-brand-surface border border-brand-cyan/40 text-brand-cyan font-bold flex items-center justify-center mx-auto mb-3 text-xs">3</div>
                <h4 class="text-xs font-bold text-white mb-1">Cari Lomba</h4>
                <p class="text-[11px] text-slate-400">Temukan kompetisi yang pas</p>
            </div>

            <!-- Step 4 -->
            <div class="glass-panel rounded-xl p-4 text-center">
                <div class="w-8 h-8 rounded-full bg-brand-surface border border-brand-purple/40 text-brand-purple font-bold flex items-center justify-center mx-auto mb-3 text-xs">4</div>
                <h4 class="text-xs font-bold text-white mb-1">Daftar</h4>
                <p class="text-[11px] text-slate-400">Daftar web / via Instagram DM</p>
            </div>

            <!-- Step 5 -->
            <div class="glass-panel rounded-xl p-4 text-center">
                <div class="w-8 h-8 rounded-full bg-brand-surface border border-brand-cyan/40 text-brand-cyan font-bold flex items-center justify-center mx-auto mb-3 text-xs">5</div>
                <h4 class="text-xs font-bold text-white mb-1">Partisipasi</h4>
                <p class="text-[11px] text-slate-400">Bimbingan & delegasi divisi</p>
            </div>

            <!-- Step 6 -->
            <div class="glass-panel rounded-xl p-4 text-center">
                <div class="w-8 h-8 rounded-full bg-brand-surface border border-emerald-500/40 text-emerald-400 font-bold flex items-center justify-center mx-auto mb-3 text-xs">6</div>
                <h4 class="text-xs font-bold text-white mb-1">Prestasi</h4>
                <p class="text-[11px] text-slate-400">Verifikasi & rekognisi publik</p>
            </div>
        </div>
    </div>
</section>

<!-- Prestasi Terverifikasi Section -->
@if($achievements->isNotEmpty())
<section class="py-20 bg-brand-surface/40 border-t border-brand-border/60 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-mono tracking-wider text-amber-400 uppercase">Rekam Jejak Capaian</span>
            <h2 class="text-3xl font-extrabold text-white mt-1">Prestasi Mahasiswa</h2>
            <p class="text-slate-400 text-sm mt-2">
                Hanya menampilkan data capaian yang telah diverifikasi panitia dan memperoleh izin publikasi.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($achievements as $ach)
                <div class="glass-panel rounded-2xl p-6 border-amber-500/20 hover:border-amber-500/50 transition-colors">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-mono uppercase bg-amber-950/80 text-amber-300 border border-amber-500/30">
                            JUARA {{ $ach->rank_position }}
                        </span>
                        <span class="text-xs font-mono text-slate-400 uppercase">
                            {{ $ach->level }}
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-white mb-1">
                        {{ $ach->achievement_title }}
                    </h3>
                    <p class="text-xs text-brand-cyan mb-3">
                        {{ $ach->competition?->title }}
                    </p>
                    <p class="text-xs text-slate-300 font-medium pt-3 border-t border-brand-border/60">
                        Mahasiswa: <span class="text-white">{{ $ach->profile?->full_name }}</span> ({{ $ach->profile?->angkatan }})
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Call to Action Banner -->
<section class="py-20 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl p-8 sm:p-12 bg-gradient-to-r from-brand-purple/20 via-brand-surface to-brand-cyan/20 border border-brand-cyan/30 text-center relative overflow-hidden shadow-glow-combined">
            <h2 class="text-2xl sm:text-4xl font-extrabold text-white mb-4">
                Siap Menemukan Jalur Potensi Terhebatmu?
            </h2>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto mb-8">
                Isi asesmen minat dan bakat sekarang dalam 3 menit. Dapatkan rekomendasi bidang, kurasi lomba terkait, dan kesempatan delegasi resmi.
            </p>
            <a href="{{ route('assessment.index') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold bg-white text-brand-navy hover:bg-slate-100 shadow-xl transition-all transform hover:-translate-y-0.5">
                <span>Mulai Asesmen Sekarang</span>
                <svg class="w-5 h-5 text-brand-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endsection
