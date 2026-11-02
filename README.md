# MINBAK TI — Talent & Competition Hub

> **Sistem Informasi Minat, Bakat, Kompetisi, dan Prestasi Mahasiswa Teknik Informatika**  
> Didesain dengan estetika modern **Digital Talent Lab** (Deep Navy `#0B1020`, Surface Dark `#121A2B`, Electric Purple `#8B5CF6`, Cyan `#22D3EE`), dilengkapi animasi latar jaringan partikel interaktif dan **8-Legged Inverse-Kinematics Cyber Spider Cursor**.

---

## 1. Ringkasan Proyek

**MINBAK TI** adalah platform full-stack terintegrasi yang dirancang untuk mendukung operasional Divisi Minat dan Bakat organisasi mahasiswa Teknik Informatika. Aplikasi ini memfasilitasi seluruh siklus pembinaan talenta mahasiswa:
1. **Pemetaan Minat & Asesmen Massal:** Menggantikan formulir terpisah (seperti Google Forms) dengan wizard asesmen multi-tahap yang memiliki logika percabangan (*branching logic*), QR code live scan untuk proyektor kegiatan, dan *scoring engine* 4-dimensi instan.
2. **Katalog & Operasional Kompetisi:** Publikasi lomba berjenjang (Internal, Regional, Nasional, Internasional), filter dinamis, pendaftaran mandiri mahasiswa via portal, dan **alur entri manual staf** untuk mahasiswa yang mendaftar via **Instagram Direct Message (DM)**, WhatsApp, maupun offline.
3. **Rekam Jejak Prestasi & Verifikasi:** Memisahkan pencatatan partisipasi dengan kemenangan juara. Penerapan prinsip privasi **UU No. 27 Tahun 2022 (UU PDP)** di mana prestasi hanya dipublikasikan secara terbuka jika mahasiswa memberikan persetujuan eksplisit.
4. **Private Management Hub (Admin):** Dashboard analitik dengan 13 menu terstruktur, pelacakan audit log keamanan, ekspor data CSV terotorisasi (khusus Ketua Divisi ke atas), dan konfigurasi branding serta bobot penilaian dinamis.

---

## 2. Fitur Utama

### A. Public Website
- **Landing Page Interaktif:** Hero section dengan headline inspiratif, statistik dinamis dari database, katalog 15 bidang minat teknologi & olahraga, visualisasi perjalanan mahasiswa (*journey visualizer*), dan etalase prestasi terverifikasi.
- **Eksplorasi Bidang Minat:** Katalog dan halaman detail 15 kategori (Frontend, Backend, Mobile, UI/UX, AI/Data Science, Cybersecurity, Game Dev, Cloud/DevOps, Karya Ilmiah, Olahraga, dll) lengkap dengan keahlian umum dan saran aktivitas.
- **Katalog Kompetisi:** Filter multi-kriteria (Tingkat, Format Online/Offline, Status, Tim/Individu), detail teknis, unduh panduan (*guidebook*), dan formulir pendaftaran diri.
- **Asesmen Minat & Bakat:** Mode kuesioner massal dengan validasi server, token rahasia 40-karakter (mencegah IDOR numerik), dan rekomendasi Top 3 jalur peminatan lengkap dengan skor kecocokan 0–100%.
- **Hall of Fame Prestasi:** Rekam jejak kemenangan lomba yang telah lolos verifikasi staf dan mengantongi izin publikasi anggota.
- **Halaman Kebijakan Privasi & Tentang Kami:** Edukasi keterbukaan pemrosesan data anggota dan profil divisi yang dapat dikonfigurasi melalui panel admin.

### B. Efek Visual & Aksesibilitas
- **Interactive Cyber Spider Cursor (`public/js/spider-cursor.js`):**
  - Model laba-laba siber 8 kaki menggunakan kalkulasi kinematika terbalik (*inverse kinematics*) dan gaya pegas (*spring motion*).
  - Kaki bereaksi terhadap arah, sudut hadap, dan akselerasi mouse dengan pola langkah bergantian alami (*alternating crawl gait*).
  - Gerakan nafas mikro (*idle breathing*) saat kursor berhenti.
  - Efek pulsa jaring digital (*web pulse ripple*) berwarna cyan/ungu ketika layar diklik.
  - Perubahan pose halus saat melewati elemen interaktif (tautan, tombol, kartu).
  - **Aksesibilitas Penuh:** `pointer-events: none` memastikan tidak mengganggu klik/formulir, mati otomatis pada perangkat layar sentuh (`pointer: coarse`), menghormati preferensi pengguna `prefers-reduced-motion`, dan dilengkapi tombol toggle visual.
- **Animated Digital Network (`public/js/digital-network.js`):** Layer partikel konstelasi dengan gravitasi kursor dan gradasi lembut ungu-cyan.

### C. Private Management Hub (Admin)
- **13 Menu Operasional:** Ringkasan Analitik, Data Mahasiswa (6 Tab Detail Profil), Minat & Skill, Asesmen & Kampanye, Bank Pertanyaan, Kompetisi, Pendaftaran Peserta, Tim, Verifikasi Prestasi, Laporan & Ekspor CSV, Manajemen Pengurus & Role, Audit Log Keamanan, dan Pengaturan Sistem.
- **Dukungan Media Sosial Instagram:** Tombol salin caption promosi siap pakai untuk Story/Feed Instagram dan tautan publik langsung pada setiap kompetisi.
- **Entri Pendaftaran Manual Staf:** Pencatatan sumber registrasi (`instagram_dm`, `whatsapp`, `offline`, `web_self`) lengkap dengan nama staf pencatat, pencegahan registrasi ganda, dan audit trail otomatis.

---

## 3. Matriks Hak Akses (Role-Based Access Control)

| Modul / Kemampuan | Super Admin | Ketua Divisi | Staff Minbak | Mahasiswa | Pengunjung |
| :--- | :---: | :---: | :---: | :---: | :---: |
| Akses Dashboard Admin (`/admin`) | ✅ | ✅ | ✅ | ❌ | ❌ |
| Kelola Kompetisi & Pendaftaran | ✅ | ✅ | ✅ | ❌ | ❌ |
| Entri Manual Staf (Instagram DM / WA) | ✅ | ✅ | ✅ | ❌ | ❌ |
| Verifikasi Catatan Prestasi | ✅ | ✅ | ✅ | ❌ | ❌ |
| Ekspor Data Mahasiswa (CSV) | ✅ | ✅ | ❌ | ❌ | ❌ |
| Akses Audit Log Keamanan | ✅ | ✅ | ❌ | ❌ | ❌ |
| Manajemen Akun Pengurus & Role | ✅ | ❌ | ❌ | ❌ | ❌ |
| Konfigurasi Branding & Bobot Scoring | ✅ | ❌ | ❌ | ❌ | ❌ |
| Portal Profil & Riwayat Mandiri | ❌ | ❌ | ❌ | ✅ | ❌ |
| Akses Publik & Formulir Asesmen | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 4. Pengelolaan Akun Pengurus & Inisialisasi Sistem

### A. Inisialisasi Akun Produksi (Secure CLI Provisioning)
Untuk membuat akun Super Administrator pertama di lingkungan produksi tanpa kredensial bawaan:
```bash
php artisan minbak:create-admin
```
Perintah ini akan meminta Nama, Email, dan Password aman dengan input tersembunyi (*hidden input*).

### B. Lingkungan Pengembangan Lokal (Development & Testing)
Pada lingkungan pengujian/pengembangan lokal (`APP_ENV=local` / `testing`), database seeder menyediakan akun demo terisolasi untuk menguji seluruh peran (`super_admin`, `ketua_divisi`, `staff`, `mahasiswa`).

Sebelum deployment produksi, jalankan perintah sanitasi untuk menonaktifkan akun demo bawaan:
```bash
php artisan minbak:clean-demo-accounts
```

---

## 5. Arsitektur Database & Entitas Inti

Sistem menggunakan database relasional dengan 17 tabel yang saling terhubung:
- `users`: Akun terautentikasi dengan atribut `role` (`super_admin`, `ketua_divisi`, `staff`, `mahasiswa`) dan status aktif.
- `student_profiles`: Profil mahasiswa, angkatan, kontak, verifikasi, serta persetujuan privasi (`consent_data_processing`, `consent_achievement_public`).
- `interest_categories`: Katalog 15 bidang pemetaan talenta.
- `competitions`: Data lomba berjenjang, jadwal pendaftaran, format tim min/max, kuota, biaya, status, serta tautan juknis.
- `competition_registrations`: Rekam pendaftaran peserta dengan status dinamis, sumber pendaftaran (`instagram_dm`, `whatsapp`, dll), dan constraint unik pencegah pendaftaran ganda.
- `achievement_records`: Rekam kemenangan lomba dengan status verifikasi (`pending`, `verified`, `rejected`), bukti dokumen sertifikat, dan kontrol publikasi UU PDP.
- `survey_campaigns`, `survey_questions`, `survey_options`: Bank kuesioner asesmen dengan token QR unik.
- `survey_responses`, `survey_answers`: Jawaban mahasiswa yang dilindungi token akses 40-karakter.
- `assessment_results`: Hasil kalkulasi scoring engine dengan snapshot konfigurasi bobot saat dihitung.
- `audit_logs`: Pencatatan aktor, jenis aksi, entitas target, alamat IP, dan waktu eksekusi.
- `app_settings`: Pengaturan dinamis branding, kontak resmi, dan bobot persentase scoring.

---

## 6. Persyaratan Sistem

- **PHP:** Versi 8.2 atau 8.3 (dengan ekstensi `pdo_sqlite` / `pdo_mysql`, `curl`, `fileinfo`, `gd`, `mbstring`, `openssl`, `zip`).
- **Composer:** Versi 2.x
- **Node.js:** Versi 18+ atau 20+
- **NPM:** Versi 9+ atau 10+
- **Database:** SQLite (lingkungan pengembangan lokal) atau MySQL / MariaDB (produksi).

---

## 7. Panduan Instalasi & Menjalankan Proyek

### Langkah 1: Clone atau Buka Direktori Proyek
```bash
cd D:\My-Project
```

### Langkah 2: Konfigurasi Environment
Salin template konfigurasi:
```bash
copy .env.example .env
```
Pastikan `APP_KEY` telah dibuat:
```bash
php artisan key:generate
```

### Langkah 3: Migrasi Database & Seeding
Jalankan migrasi bersih dan seeder data awal:
```bash
php artisan migrate:fresh --seed
```

### Langkah 4: Build Asset Frontend (Tailwind CSS + Alpine.js + Vite)
```bash
npm install
npm run build
```

### Langkah 5: Jalankan Server Pengembangan
```bash
php artisan serve
```
Akses aplikasi melalui browser di: `http://localhost:8000`

---

## 8. Menjalankan Pengujian Otomatis (Automated Testing)

Aplikasi dilengkapi dengan rangkaian Unit Test dan Feature Test lengkap yang memverifikasi:
- Ketepatan scoring engine 4 dimensi dan snapshot bobot.
- Proteksi otorisasi middleware (Guest, Mahasiswa, Staff, Ketua, Super Admin).
- Pencegahan pendaftaran ganda pada kompetisi yang sama.
- Alur submission kuesioner dan render hasil rekomendasi.
- Proteksi ekspor data CSV khusus Ketua Divisi.

Jalankan pengujian menggunakan PHP Artisan:
```bash
php artisan test
```

**Hasil Pengujian Terakhir:**
```text
PASS Tests\Unit\AssessmentScoringTest
✓ scoring weights defaults in settings
✓ scoring service computes recommendation results

PASS Tests\Unit\ExampleTest
✓ that true is true

PASS Tests\Feature\ExampleTest
✓ the application returns a successful response

PASS Tests\Feature\MinbakOperationalWorkflowTest
✓ public pages render successfully
✓ exploration field detail renders
✓ competition detail renders
✓ assessment campaign submission and result

PASS Tests\Feature\MinbakSecurityAndRbacTest
✓ guest is redirected from admin
✓ mahasiswa cannot access admin dashboard
✓ staff can access admin but cannot export csv or manage settings
✓ ketua divisi can export csv
✓ super admin can access settings and user management
✓ prevents duplicate competition registrations

Tests: 14 passed (45 assertions)
Duration: 0.73s
```

---

## 9. Panduan Operasional Pengurus

### A. Pengelolaan Kompetisi & Promosi Instagram
1. Buka menu **Kompetisi** (`/admin/kompetisi`) &rarr; Klik **Publikasi Kompetisi Baru**.
2. Masukkan rincian lomba, tingkat (Internal, Regional, Nasional, Internasional), tanggal tenggat, dan status pendaftaran.
3. Setelah disimpan, buka halaman detail kompetisi. Gunakan tombol **"Salin Caption Instagram"** untuk memperoleh teks promosi siap pakai untuk Story/Feed Instagram organisasi lengkap dengan hashtag dan tautan langsung.

### B. Entri Pendaftaran Mahasiswa dari Instagram DM
1. Jika terdapat mahasiswa yang menghubungi Instagram Minat & Bakat meminta didaftarkan, buka menu **Pendaftaran Peserta** (`/admin/pendaftaran`).
2. Klik **+ Entri Manual (IG / WA / Offline)**.
3. Pilih kompetisi yang dituju dan nama mahasiswa dari database.
4. Pilih saluran **Instagram Direct Message (DM)** dan catat username atau bukti persetujuan pada kolom catatan.
5. Simpan. Sistem otomatis mencatat identitas staf dan mencegah duplikasi pendaftaran jika mahasiswa telah terdaftar sebelumnya.

### C. Pembuatan Kampanye Asesmen & Presentasi QR Code
1. Buka menu **Asesmen & Kampanye** (`/admin/asesmen`) &rarr; Klik **+ Buat Kampanye Baru**.
2. Tentukan nama kegiatan (misal: *Pemetaan Talenta TI 2026/2027*).
3. Buka detail kampanye yang baru dibuat.
4. Klik **"Tampilkan Layar Penuh (Proyektor)"** untuk memproyeksikan kode QR berukuran besar di layar auditorium acara agar dapat langsung dipindai mahasiswa melalui smartphone.

### D. Mengubah Bobot Penilaian Asesmen
1. Masuk sebagai **Super Admin** &rarr; Buka menu **Pengaturan Sistem** (`/admin/pengaturan`).
2. Gulir ke bagian **Bobot Penilaian Asesmen Minat**.
3. Sesuaikan persentase Ketertarikan, Aktivitas, Pengalaman, dan Orientasi Belajar. Sistem akan memvalidasi secara real-time agar total bobot tepat bernilai 100%.

---

## 10. Panduan Keamanan & Deployment Produksi

1. **HTTPS Enforced:** Pastikan web server (Nginx/Apache) mengaktifkan sertifikat TLS/SSL.
2. **Environment Production:**
   - Ubah `APP_ENV=production` dan `APP_DEBUG=false` di file `.env`.
   - Jalankan `php artisan config:cache`, `php artisan route:cache`, dan `php artisan view:cache`.
3. **Storage Berkas:**
   - Jalankan `php artisan storage:link` untuk poster publik.
   - Dokumen privasi sensitif (seperti berkas identitas atau bukti persetujuan) disimpan di disk privat dengan otorisasi akses controller.
4. **Backup Database:**
   - Jadwalkan backup rutin berkala untuk database dan file upload.

---

&copy; 2026 **MINBAK TI — Divisi Minat dan Bakat Mahasiswa Teknik Informatika**. All rights reserved.
