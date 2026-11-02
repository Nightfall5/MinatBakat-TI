<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competition;
use App\Models\InterestCategory;
use App\Models\User;

class CompetitionsSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::where('role', 'staff')->orWhere('role', 'super_admin')->first();
        $creatorId = $creator ? $creator->id : null;

        $catFrontend = InterestCategory::where('slug', 'frontend-development')->first();
        $catBackend = InterestCategory::where('slug', 'backend-development')->first();
        $catCyber = InterestCategory::where('slug', 'cybersecurity')->first();
        $catUIUX = InterestCategory::where('slug', 'ui-ux-design')->first();
        $catAI = InterestCategory::where('slug', 'ai-ml')->first();
        $catData = InterestCategory::where('slug', 'data-science')->first();
        $catEssay = InterestCategory::where('slug', 'penulisan-esai-karya-ilmiah')->first();
        $catFutsal = InterestCategory::where('slug', 'olahraga-futsal')->first();

        $competitions = [
            [
                'title' => 'GEMASTIK 2026 — Desain Pengalaman Pengguna (UX Design)',
                'slug' => 'gemastik-2026-ux-design',
                'organizer' => 'Pusat Prestasi Nasional (Puspresnas) & Kemendikbudristek',
                'description' => 'Kompetisi bergengsi tingkat nasional di bidang desain antarmuka dan pengalaman pengguna untuk memecahkan problem sosial masyarakat melalui solusi digital yang inklusif.',
                'interest_category_id' => $catUIUX?->id,
                'level' => 'nasional',
                'format' => 'hybrid',
                'location' => 'Universitas Indonesia (Babak Final)',
                'team_type' => 'team',
                'min_team_members' => 3,
                'max_team_members' => 3,
                'fee_amount' => 0,
                'prize_summary' => 'Medali Emas/Perak/Perunggu, Trofi Bergilir, dan Dana Pembinaan Rp 30.000.000',
                'official_url' => 'https://puspresnas.kemdikbud.go.id/gemastik',
                'guidebook_url' => 'https://puspresnas.kemdikbud.go.id/panduan-gemastik-2026.pdf',
                'status' => 'open',
                'registration_start' => now()->subDays(10),
                'registration_deadline' => now()->addDays(20),
                'event_start' => now()->addDays(35),
                'event_end' => now()->addDays(40),
                'created_by_id' => $creatorId,
            ],
            [
                'title' => 'National Cyber Battle CTF 2026',
                'slug' => 'national-cyber-battle-ctf-2026',
                'organizer' => 'Badan Siber dan Sandi Negara (BSSN) x Komunitas CTF',
                'description' => 'Kompetisi Capture The Flag (Jeopardy & Attack-Defense) mencakup kategori Web Exploitation, Cryptography, Reverse Engineering, Binary Exploitation, dan Digital Forensics.',
                'interest_category_id' => $catCyber?->id,
                'level' => 'nasional',
                'format' => 'online',
                'location' => 'Platform Daring BSSN',
                'team_type' => 'team',
                'min_team_members' => 2,
                'max_team_members' => 3,
                'fee_amount' => 50000,
                'prize_summary' => 'Sertifikat Resmi BSSN, Uang Tunai Total Rp 25.000.000, Fast-track Interview Magang Keamanan Siber',
                'official_url' => 'https://bssn.go.id/ctf-2026',
                'guidebook_url' => 'https://bssn.go.id/rules-ctf.pdf',
                'status' => 'open',
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(15),
                'event_start' => now()->addDays(25),
                'event_end' => now()->addDays(27),
                'created_by_id' => $creatorId,
            ],
            [
                'title' => 'AI Innovation Challenge: GenAI for Healthcare',
                'slug' => 'ai-innovation-challenge-healthcare',
                'organizer' => 'IEEE Student Branch & Google Cloud Indonesia',
                'description' => 'Tantangan inovasi kecerdasan buatan untuk membangun aplikasi cerdas berbasis LLM, Computer Vision, atau analisis data medis yang etis dan berdampak nyata.',
                'interest_category_id' => $catAI?->id,
                'level' => 'internasional',
                'format' => 'online',
                'location' => 'Virtual Presentation via Zoom',
                'team_type' => 'team',
                'min_team_members' => 2,
                'max_team_members' => 4,
                'fee_amount' => 0,
                'prize_summary' => '$5,000 USD Cloud Credits, Hadiah Tunai $3,000 USD, dan Mentoring dari AI Researcher',
                'official_url' => 'https://ieee.org/ai-challenge-2026',
                'guidebook_url' => 'https://ieee.org/guidelines.pdf',
                'status' => 'open',
                'registration_start' => now()->subDays(15),
                'registration_deadline' => now()->addDays(10),
                'event_start' => now()->addDays(18),
                'event_end' => now()->addDays(21),
                'created_by_id' => $creatorId,
            ],
            [
                'title' => 'Hackathon Web Inovasi Kampus 2026',
                'slug' => 'hackathon-web-inovasi-kampus',
                'organizer' => 'Divisi Minat dan Bakat HMIF UTI',
                'description' => 'Kompetisi internal mahasiswa Teknik Informatika untuk merancang website interaktif solutif dalam waktu 24 jam dengan tema Digital Campus Ecosystem.',
                'interest_category_id' => $catFrontend?->id,
                'level' => 'internal_kampus',
                'format' => 'offline',
                'location' => 'Laboratorium Komputer Terpadu Gedung C',
                'team_type' => 'team',
                'min_team_members' => 2,
                'max_team_members' => 3,
                'fee_amount' => 0,
                'prize_summary' => 'Trofi Juara 1, Plakat, Piagam Dekan, dan Uang Pembinaan Rp 5.000.000',
                'official_url' => 'https://minbak.ti/kompetisi/hackathon-web',
                'guidebook_url' => 'https://minbak.ti/docs/panduan-hackathon.pdf',
                'status' => 'open',
                'registration_start' => now()->subDays(3),
                'registration_deadline' => now()->addDays(12),
                'event_start' => now()->addDays(16),
                'event_end' => now()->addDays(17),
                'created_by_id' => $creatorId,
            ],
            [
                'title' => 'Lomba Karya Tulis Ilmiah Nasional (LKTIN) Teknologi Hijau',
                'slug' => 'lktin-teknologi-hijau-2026',
                'organizer' => 'Kementerian Riset dan Inovasi BEM Universitas Diponegoro',
                'description' => 'Kompetisi gagasan ilmiah tertulis bagi mahasiswa se-Indonesia untuk mengusulkan konsep Internet of Things, AI, atau platform digital dalam mendukung Green Technology.',
                'interest_category_id' => $catEssay?->id,
                'level' => 'nasional',
                'format' => 'hybrid',
                'location' => 'Semarang (Grand Final Offline)',
                'team_type' => 'team',
                'min_team_members' => 2,
                'max_team_members' => 3,
                'fee_amount' => 75000,
                'prize_summary' => 'Piala Bergilir Kemenpora, Uang Tunai Rp 12.000.000, dan Publikasi Prosiding Ber-ISSN',
                'official_url' => 'https://lktin-undip.com',
                'guidebook_url' => 'https://lktin-undip.com/buku-panduan.pdf',
                'status' => 'open',
                'registration_start' => now()->subDays(20),
                'registration_deadline' => now()->addDays(8),
                'event_start' => now()->addDays(22),
                'event_end' => now()->addDays(24),
                'created_by_id' => $creatorId,
            ],
            [
                'title' => 'Informatics Futsal Championship (IFC) 2026',
                'slug' => 'informatics-futsal-championship-2026',
                'organizer' => 'Badan Koordinasi Olahraga Mahasiswa Fakultas Ilmu Komputer',
                'description' => 'Turnamen futsal bergengsi antarmahasiswa Teknik Informatika dan Ilmu Komputer tingkat regional untuk menjunjung tinggi sportivitas dan soliditas.',
                'interest_category_id' => $catFutsal?->id,
                'level' => 'regional',
                'format' => 'offline',
                'location' => 'Gelanggang Olahraga Remaja (GOR) Krida',
                'team_type' => 'team',
                'min_team_members' => 8,
                'max_team_members' => 12,
                'fee_amount' => 250000,
                'prize_summary' => 'Trofi Juara 1, Medali Emas, Uang Tunai Rp 7.500.000, dan Gelar Top Scorer',
                'official_url' => 'https://ifc-cup.id',
                'guidebook_url' => 'https://ifc-cup.id/regulasi-pertandingan.pdf',
                'status' => 'open',
                'registration_start' => now()->subDays(7),
                'registration_deadline' => now()->addDays(14),
                'event_start' => now()->addDays(21),
                'event_end' => now()->addDays(23),
                'created_by_id' => $creatorId,
            ],
            [
                'title' => 'International Data Science Hackathon (IDSH) 2025',
                'slug' => 'international-data-science-hackathon-2025',
                'organizer' => 'Kaggle Community & ASEAN Data Science Explorers',
                'description' => 'Kompetisi permodelan analitik data berskala ASEAN yang telah terselenggara dengan rekam jejak capaian delegasi mahasiswa TI.',
                'interest_category_id' => $catData?->id,
                'level' => 'internasional',
                'format' => 'online',
                'location' => 'Virtual Summit',
                'team_type' => 'team',
                'min_team_members' => 2,
                'max_team_members' => 3,
                'fee_amount' => 0,
                'prize_summary' => 'Medali Perak ASEAN, Piagam Rekognisi Internasional, Hadiah $2,000 USD',
                'official_url' => 'https://aseandse.org/hackathon-2025',
                'guidebook_url' => 'https://aseandse.org/guide.pdf',
                'status' => 'completed',
                'registration_start' => now()->subMonths(6),
                'registration_deadline' => now()->subMonths(5),
                'event_start' => now()->subMonths(4),
                'event_end' => now()->subMonths(4)->addDays(3),
                'created_by_id' => $creatorId,
            ],
        ];

        foreach ($competitions as $comp) {
            Competition::updateOrCreate(['slug' => $comp['slug']], $comp);
        }
    }
}
