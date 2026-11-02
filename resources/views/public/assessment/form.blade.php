@extends('layouts.app')

@section('title', 'Form Asesmen — ' . $campaign->title)

@section('content')
<div class="py-12 md:py-16" x-data="{
    step: 1,
    totalSteps: 3,
    selectedBranch: '',
    name: '{{ auth()->user()?->name ?? old('respondent_name') }}',
    email: '{{ auth()->user()?->email ?? old('respondent_email') }}',
    angkatan: '{{ auth()->user()?->studentProfile?->angkatan ?? old('respondent_angkatan') }}',
    nim: '{{ auth()->user()?->studentProfile?->nim ?? old('respondent_nim') }}',
    consent: true,
    answers: {},
    validateStep1() {
        if (!this.name.trim() || !this.email.trim() || !this.angkatan) {
            alert('Mohon lengkapi Nama, Email, dan Angkatan terlebih dahulu.');
            return false;
        }
        if (!this.consent) {
            alert('Anda wajib menyetujui pemrosesan data untuk melanjutkan.');
            return false;
        }
        return true;
    },
    validateStep2() {
        if (!this.selectedBranch) {
            alert('Mohon pilih satu aktivitas yang paling menarik minatmu pada pertanyaan minat umum.');
            return false;
        }
        return true;
    }
}">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Bar Indicator -->
        <div class="glass-panel rounded-2xl p-6 mb-8">
            <div class="flex items-center justify-between text-xs font-mono mb-2">
                <span class="text-brand-cyan">TAHAP <span x-text="step"></span> DARI 3</span>
                <span class="text-slate-400" x-text="step === 1 ? 'Profil & Pengalaman' : (step === 2 ? 'Minat Umum & Preferensi' : 'Pendalaman Minat & Branching')"></span>
            </div>
            <div class="w-full h-2 rounded-full bg-slate-900 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-brand-purple to-brand-cyan transition-all duration-300"
                     :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
            </div>
        </div>

        <!-- Assessment Form -->
        <form method="POST" action="{{ route('assessment.submit', $campaign->slug) }}" class="space-y-8" @submit="if(step < 3){ $event.preventDefault(); }">
            @csrf

            <!-- ===================== TAHAP 1: PROFIL & PENGALAMAN ===================== -->
            <div x-show="step === 1" class="space-y-6">
                <div class="glass-panel rounded-3xl p-6 sm:p-8 space-y-6">
                    <div class="border-b border-brand-border/60 pb-4">
                        <h2 class="text-xl font-bold text-white">Tahap 1 — Identitas & Latar Belakang</h2>
                        <p class="text-xs text-slate-400 mt-1">Data identitas digunakan untuk memvalidasi rekam keanggotaan dan menghubungkan rekomendasi ke profil Anda.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Nama Lengkap *</label>
                            <input type="text" name="respondent_name" x-model="name" required placeholder="Contoh: Arya Putra Wicaksana" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Email Mahasiswa / Utama *</label>
                            <input type="email" name="respondent_email" x-model="email" required placeholder="nama@student.ac.id" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Angkatan Kuliah *</label>
                            <select name="respondent_angkatan" x-model="angkatan" required class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                                <option value="">Pilih Angkatan...</option>
                                <option value="2026">2026 (Mahasiswa Baru)</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="alumni">Alumni / Lainnya</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5">NIM (Nomor Induk Mahasiswa — Opsional)</label>
                            <input type="text" name="respondent_nim" x-model="nim" placeholder="Contoh: 24060122140001" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-brand-border text-white text-sm focus:ring-2 focus:ring-brand-cyan focus:outline-none">
                        </div>
                    </div>

                    <!-- Step 1 Questions (Pengalaman belajar & kompetisi) -->
                    <div class="space-y-6 pt-4 border-t border-brand-border/60">
                        @foreach($step1Questions as $q)
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-white">
                                    {{ $loop->iteration }}. {{ $q->question_text }}
                                </label>
                                <div class="space-y-2">
                                    @foreach($q->options as $opt)
                                        <label class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-900/60 border border-brand-border hover:border-brand-cyan/50 cursor-pointer transition-colors">
                                            <input type="radio" 
                                                   name="answers[{{ $q->id }}]" 
                                                   value="{{ $opt->id }}" 
                                                   required
                                                   class="mt-1 text-brand-cyan focus:ring-brand-cyan">
                                            <span class="text-xs sm:text-sm text-slate-200">{{ $opt->option_text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Consent agreement -->
                    <div class="pt-4 border-t border-brand-border/60">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="consent_agreed" value="1" x-model="consent" required class="mt-0.5 text-brand-cyan focus:ring-brand-cyan rounded">
                            <span class="text-xs text-slate-400 leading-relaxed">
                                Saya menyetujui pemrosesan data identitas dan jawaban survei ini oleh Divisi Minat dan Bakat untuk keperluan pemetaan talenta dan rekomendasi kegiatan lomba sesuai <a href="{{ route('privacy') }}" target="_blank" class="text-brand-cyan underline">Kebijakan Privasi</a>.
                            </span>
                        </label>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="button" 
                                @click="if(validateStep1()){ step = 2; window.scrollTo({top: 0, behavior: 'smooth'}); }"
                                class="inline-flex items-center gap-2 px-7 py-3 rounded-xl font-bold bg-gradient-to-r from-brand-purple to-brand-cyan text-white shadow-glow-combined hover:opacity-95 text-sm">
                            <span>Lanjut ke Tahap 2 &rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ===================== TAHAP 2: MINAT UMUM & PREFERENSI ===================== -->
            <div x-show="step === 2" class="space-y-6" style="display:none;">
                <div class="glass-panel rounded-3xl p-6 sm:p-8 space-y-8">
                    <div class="border-b border-brand-border/60 pb-4">
                        <h2 class="text-xl font-bold text-white">Tahap 2 — Minat Umum & Preferensi Aktivitas</h2>
                        <p class="text-xs text-slate-400 mt-1">Pilihlah opsi yang paling mencerminkan rasa ingin tahumu dan cara kamu menikmati proses berkarya.</p>
                    </div>

                    @foreach($step2Questions as $q)
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-white">
                                {{ $loop->iteration }}. {{ $q->question_text }}
                            </label>

                            <div class="space-y-2">
                                @foreach($q->options as $opt)
                                    <label class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-900/60 border border-brand-border hover:border-brand-cyan/50 cursor-pointer transition-colors"
                                           @if($opt->target_branch) @click="selectedBranch = '{{ $opt->target_branch }}'" @endif>
                                        <input type="radio" 
                                               name="answers[{{ $q->id }}]" 
                                               value="{{ $opt->id }}"
                                               @if($opt->target_branch) @change="selectedBranch = '{{ $opt->target_branch }}'" @endif
                                               required
                                               class="mt-1 text-brand-cyan focus:ring-brand-cyan">
                                        <span class="text-xs sm:text-sm text-slate-200">{{ $opt->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="flex items-center justify-between pt-6 border-t border-brand-border/60">
                        <button type="button" @click="step = 1; window.scrollTo({top: 0, behavior: 'smooth'});" class="px-5 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:bg-slate-800 text-xs font-medium">
                            &larr; Kembali ke Tahap 1
                        </button>
                        <button type="button" 
                                @click="if(validateStep2()){ step = 3; window.scrollTo({top: 0, behavior: 'smooth'}); }"
                                class="inline-flex items-center gap-2 px-7 py-3 rounded-xl font-bold bg-gradient-to-r from-brand-purple to-brand-cyan text-white shadow-glow-combined hover:opacity-95 text-sm">
                            <span>Lanjut ke Pendalaman &rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ===================== TAHAP 3: BRANCHING LOGIC & HASIL ===================== -->
            <div x-show="step === 3" class="space-y-6" style="display:none;">
                <div class="glass-panel rounded-3xl p-6 sm:p-8 space-y-8">
                    <div class="border-b border-brand-border/60 pb-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-purple/20 border border-brand-purple/40 text-[11px] font-mono text-purple-300 mb-2">
                            <span>PERTANYAAN ADAPTIF BERDASARKAN PREFERENSIMU</span>
                        </div>
                        <h2 class="text-xl font-bold text-white">Tahap 3 — Pendalaman Minat Spesifik</h2>
                        <p class="text-xs text-slate-400 mt-1">Pertanyaan ini disesuaikan dengan pilihan aktivitas yang kamu pilih pada tahap sebelumnya.</p>
                    </div>

                    <!-- Branching Questions Container -->
                    <div class="space-y-6">
                        @foreach($step3Questions as $q)
                            <div class="space-y-3 p-5 rounded-2xl bg-slate-900/60 border border-brand-border/80"
                                 x-show="!selectedBranch || selectedBranch === '{{ $q->branch_key }}' || '{{ $q->branch_key }}' === 'explore_branch'">
                                <label class="block text-sm font-semibold text-white">
                                    {{ $q->question_text }}
                                </label>
                                <div class="space-y-2">
                                    @foreach($q->options as $opt)
                                        <label class="flex items-start gap-3 p-3 rounded-xl bg-brand-navy border border-brand-border hover:border-brand-purple/60 cursor-pointer transition-colors">
                                            <input type="radio" 
                                                   name="answers[{{ $q->id }}]" 
                                                   value="{{ $opt->id }}"
                                                   class="mt-1 text-brand-purple focus:ring-brand-purple">
                                            <span class="text-xs sm:text-sm text-slate-200">{{ $opt->option_text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between pt-6 border-t border-brand-border/60">
                        <button type="button" @click="step = 2; window.scrollTo({top: 0, behavior: 'smooth'});" class="px-5 py-2.5 rounded-xl border border-brand-border text-slate-300 hover:bg-slate-800 text-xs font-medium">
                            &larr; Kembali ke Tahap 2
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl font-bold bg-gradient-to-r from-emerald-500 to-brand-cyan text-slate-950 shadow-glow-cyan hover:opacity-95 text-sm transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Selesaikan & Lihat Rekomendasi</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
