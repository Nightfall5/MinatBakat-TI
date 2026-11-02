# MINBAK TI — Talent & Competition Hub

<p align="center">
  <strong>Platform pengelolaan minat, bakat, kompetisi, dan prestasi mahasiswa Teknik Informatika.</strong>
  <br />
  Membantu pengurus membina talenta mahasiswa melalui asesmen, informasi kompetisi, pendataan peserta, dan rekam jejak prestasi.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white" alt="PHP 8.2+" />
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white" alt="Laravel 11" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?logo=tailwindcss&logoColor=white" alt="Tailwind CSS 3" />
  <img src="https://img.shields.io/badge/Alpine.js-3-8BC0D0?logo=alpinedotjs&logoColor=white" alt="Alpine.js 3" />
  <img src="https://img.shields.io/badge/Vite-5-646CFF?logo=vite&logoColor=white" alt="Vite 5" />
</p>

---

## Tentang MINBAK TI

**MINBAK TI** adalah aplikasi web untuk mendukung kegiatan Divisi Minat dan Bakat di lingkungan mahasiswa Teknik Informatika. Sistem ini menyatukan proses pemetaan potensi, publikasi kompetisi, pengelolaan pendaftaran, serta pencatatan prestasi dalam satu platform.

Aplikasi memiliki dua area utama:

- **Public Website** — tempat mahasiswa dan pengunjung melihat bidang minat, informasi kompetisi, asesmen, dan prestasi yang telah disetujui untuk dipublikasikan.
- **Management Hub** — area internal pengurus untuk mengelola data mahasiswa, kompetisi, pendaftaran, asesmen, verifikasi prestasi, pengguna, dan pengaturan sistem.

## Fitur

### Pemetaan Minat & Bakat
- Katalog bidang minat teknologi, kreativitas, dan olahraga.
- Asesmen berbasis kampanye dengan pertanyaan bertahap.
- Rekomendasi tiga bidang minat berdasarkan hasil penilaian.
- Pengaturan bobot penilaian melalui panel administrator.

### Manajemen Kompetisi
- Publikasi kompetisi berdasarkan tingkat dan format pelaksanaan.
- Halaman detail kompetisi beserta informasi pendaftaran.
- Pendaftaran mandiri mahasiswa.
- Pencatatan pendaftaran oleh staf untuk jalur Instagram DM, WhatsApp, atau offline.
- Pengelolaan status dan data tim peserta.

### Prestasi Mahasiswa
- Pencatatan partisipasi dan pencapaian kompetisi.
- Alur verifikasi prestasi oleh pengurus.
- Kontrol publikasi prestasi berdasarkan persetujuan mahasiswa.

### Portal & Administrasi
- Portal profil mahasiswa.
- Dashboard pengurus.
- Pengelolaan akun dan role.
- Audit log aktivitas.
- Pengaturan aplikasi dan bobot asesmen.
- Ekspor data dengan pembatasan hak akses.

## Role & Hak Akses

| Role | Akses utama |
|---|---|
| Super Admin | Seluruh modul, pengelolaan pengguna, pengaturan sistem |
| Ketua Divisi | Operasional pengurus, verifikasi, audit log, dan ekspor data |
| Staff Minbak | Operasional data mahasiswa, kompetisi, pendaftaran, dan prestasi |
| Mahasiswa | Portal pribadi, asesmen, dan pendaftaran kompetisi |
| Pengunjung | Halaman publik dan asesmen yang tersedia |

Akses setiap fitur tetap ditentukan oleh middleware dan otorisasi di sisi server.

## Teknologi

| Bagian | Teknologi |
|---|---|
| Backend | PHP 8.2+, Laravel 11 |
| Frontend | Blade, Tailwind CSS 3, Alpine.js |
| Asset bundling | Vite 5 |
| Interaksi visual | Three.js |
| Database | SQLite untuk pengembangan; MySQL/MariaDB untuk deployment |
| Testing | PHPUnit melalui Laravel Test Runner |

## Struktur Proyek

```text
.
├── app/
│   ├── Console/Commands/     # Perintah administrasi
│   ├── Http/Controllers/     # Controller publik dan admin
│   ├── Http/Middleware/      # Middleware autentikasi/otorisasi
│   ├── Models/               # Model Eloquent
│   └── Services/             # Business logic
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── public/                   # Entry point dan aset publik
├── resources/
│   ├── css/
│   ├── js/
│   └── views/                # Blade templates
├── routes/
├── tests/
├── composer.json
└── package.json
```

## Menjalankan Secara Lokal

### Prasyarat

- PHP 8.2 atau lebih baru beserta ekstensi Laravel yang dibutuhkan.
- Composer 2.x.
- Node.js 18+ dan npm.
- SQLite untuk setup lokal, atau MySQL/MariaDB jika menggunakan database tersebut.

### Instalasi

1. Clone repository dan masuk ke direktori proyek:

   ```bash
   git clone https://github.com/Nightfall5/My-Project.git
   cd My-Project
   ```

2. Install dependency backend dan frontend:

   ```bash
   composer install
   npm install
   ```

3. Siapkan environment:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Pada Windows PowerShell, gunakan `Copy-Item .env.example .env` jika perintah `cp` tidak tersedia.

4. Siapkan database pada file `database/database.sqlite` atau sesuaikan konfigurasi database di `.env`.

   Untuk SQLite pada Windows PowerShell:

   ```powershell
   New-Item -ItemType File -Force database/database.sqlite
   ```

5. Jalankan migrasi dan data awal:

   ```bash
   php artisan migrate --seed
   ```

6. Build aset frontend:

   ```bash
   npm run build
   ```

7. Jalankan aplikasi:

   ```bash
   php artisan serve
   ```

   Buka http://127.0.0.1:8000 di browser.

> Gunakan konfigurasi environment lokal milik sendiri. Jangan mengunggah file `.env`, kredensial, database berisi data pribadi, atau secret ke repository.

## Pengujian

Jalankan test suite dengan:

```bash
php artisan test
```

Test yang tersedia mencakup alur operasional, perhitungan asesmen, serta beberapa skenario autentikasi dan role-based access control.

## Keamanan & Privasi

- Kredensial dan konfigurasi lokal disimpan di file `.env` yang tidak dilacak Git.
- Akses fitur administratif dibatasi melalui autentikasi dan middleware role.
- Data dan prestasi mahasiswa memiliki kontrol persetujuan untuk pemrosesan maupun publikasi.
- Sebelum deployment, pastikan `APP_DEBUG=false`, gunakan HTTPS, konfigurasi storage dengan benar, dan siapkan backup database.

## Status Proyek

Repository ini berisi source code aplikasi MINBAK TI. Konfigurasi deployment, kredensial, dan data operasional tidak disertakan. Jalankan aplikasi di lingkungan lokal terlebih dahulu sebelum melakukan deployment.

---

<p align="center">
  Built to support student potential, participation, and achievement.
</p>
