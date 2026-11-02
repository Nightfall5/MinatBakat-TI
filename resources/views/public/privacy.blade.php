@extends('layouts.app')

@section('title', 'Kebijakan Privasi Data Mahasiswa — ' . $appName)

@section('content')
<div class="py-12 md:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10 text-center">
            <span class="text-xs font-mono uppercase tracking-wider text-brand-cyan">Perlindungan Data Pribadi</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-1">Kebijakan Privasi Data Mahasiswa</h1>
            <p class="text-slate-400 text-sm mt-2">
                Terakhir diperbarui: Oktober 2026 &bull; Divisi Minat dan Bakat {{ $orgName }}
            </p>
        </div>

        <div class="glass-panel rounded-3xl p-8 sm:p-12 space-y-8 text-sm text-slate-300 leading-relaxed">
            <div>
                <h2 class="text-xl font-bold text-white mb-3">1. Komitmen Perlindungan Privasi</h2>
                <p>
                    Platform {{ $appName }} beroperasi dengan prinsip keamanan sejak tahap perancangan (Security by Design) dan minimisasi data. Kami menyadari bahwa informasi identitas, minat bakat, keahlian, dan riwayat kegiatan mahasiswa merupakan data yang harus dilindungi secara bertanggung jawab sesuai dengan peraturan perundang-undangan yang berlaku di Indonesia, termasuk prinsip Undang-Undang Nomor 27 Tahun 2022 tentang Pelindungan Data Pribadi (UU PDP).
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-white mb-3">2. Data yang Dikumpulkan</h2>
                <p class="mb-2">Kami mengumpulkan data mahasiswa hanya sebatas yang diperlukan untuk kepentingan operasional divisi:</p>
                <ul class="list-disc list-inside space-y-1 text-slate-400 pl-2">
                    <li><strong>Data Identitas Dasar:</strong> Nama lengkap, email institusi/kampus, nomor kontak (WhatsApp), dan angkatan. NIM hanya dikumpulkan apabila diperlukan untuk verifikasi keanggotaan.</li>
                    <li><strong>Data Minat & Talenta:</strong> Jawaban survei asesmen, preferensi bidang keahlian, tingkat pengalaman yang dilaporkan secara mandiri, dan portofolio yang dibagikan secara sukarela.</li>
                    <li><strong>Data Partisipasi Lomba:</strong> Riwayat pendaftaran kompetisi (termasuk permintaan yang masuk via Instagram DM atau formulir mandiri), tim, dan sertifikat prestasi.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-bold text-white mb-3">3. Tujuan Pemrosesan Data</h2>
                <p class="mb-2">Data yang dikumpulkan semata-mata dipergunakan untuk:</p>
                <ol class="list-decimal list-inside space-y-1 text-slate-400 pl-2">
                    <li>Melakukan pemetaan agregat minat dan bakat mahasiswa guna perancangan program kerja divisi yang relevan.</li>
                    <li>Memberikan rekomendasi bidang eksplorasi serta kurasi kompetisi yang sesuai dengan minat anggota.</li>
                    <li>Menghubungi mahasiswa calon delegasi kompetisi untuk konfirmasi keikutsertaan dan pembentukan tim.</li>
                    <li>Mendokumentasikan rekam jejak prestasi mahasiswa Teknik Informatika.</li>
                </ol>
            </div>

            <div>
                <h2 class="text-xl font-bold text-white mb-3">4. Pemisahan Izin Publikasi Prestasi</h2>
                <p>
                    Kami menerapkan pemisahan yang tegas antara <strong>penggunaan data internal</strong> dan <strong>publikasi publik</strong>. Capaian lomba seorang mahasiswa tidak akan dipublikasikan pada halaman Prestasi publik tanpa adanya persetujuan publikasi (<em>consent</em>) dari mahasiswa yang bersangkutan dan verifikasi resmi dari pengurus divisi.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-white mb-3">5. Batasan Akses dan Keamanan Teknis</h2>
                <p>
                    Detail jawaban asesmen individual dan data kontak pribadi hanya dapat diakses oleh staf pengurus yang memiliki wewenang sah (Role-Based Access Control). Ekspor data dibatasi secara ketat, diproteksi dengan otorisasi tingkat lanjut, serta dicatat dalam log audit keamanan (Audit Trail).
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-white mb-3">6. Hak Mahasiswa sebagai Subjek Data</h2>
                <p class="mb-2">Sebagai mahasiswa pemilik data, Anda berhak untuk:</p>
                <ul class="list-disc list-inside space-y-1 text-slate-400 pl-2">
                    <li>Mengakses dan memperbarui data profil serta keahlian Anda melalui Portal Mahasiswa.</li>
                    <li>Menarik kembali persetujuan penayangan publik atas catatan prestasi Anda sewaktu-waktu.</li>
                    <li>Mengajukan permohonan penghapusan atau pengarsipan data keikutsertaan melalui pengurus resmi.</li>
                </ul>
            </div>

            <div class="pt-6 border-t border-brand-border/60">
                <p class="text-xs text-slate-400">
                    Pertanyaan dan permintaan koreksi data dapat disampaikan melalui kontak resmi pengurus di: <a href="mailto:{{ $contactEmail }}" class="text-brand-cyan hover:underline">{{ $contactEmail }}</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
