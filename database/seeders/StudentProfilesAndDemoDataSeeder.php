<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StudentSkill;
use App\Models\StudentInterest;
use App\Models\InterestCategory;
use App\Models\Competition;
use App\Models\CompetitionRegistration;
use App\Models\ParticipationRecord;
use App\Models\AchievementRecord;

class StudentProfilesAndDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->error('Peringatan Keamanan: Seeder data simulasi mahasiswa tidak diizinkan dijalankan pada environment production.');
            return;
        }

        $staff = User::where('role', 'staff')->first();
        $ketua = User::where('role', 'ketua_divisi')->first();

        $catFrontend = InterestCategory::where('slug', 'frontend-development')->first();
        $catCyber = InterestCategory::where('slug', 'cybersecurity')->first();
        $catUIUX = InterestCategory::where('slug', 'ui-ux-design')->first();
        $catData = InterestCategory::where('slug', 'data-science')->first();
        $catAI = InterestCategory::where('slug', 'ai-ml')->first();
        $catFutsal = InterestCategory::where('slug', 'olahraga-futsal')->first();

        $compUX = Competition::where('slug', 'gemastik-2026-ux-design')->first();
        $compCTF = Competition::where('slug', 'national-cyber-battle-ctf-2026')->first();
        $compHackathon = Competition::where('slug', 'hackathon-web-inovasi-kampus')->first();
        $compFutsal = Competition::where('slug', 'informatics-futsal-championship-2026')->first();
        $compPastData = Competition::where('slug', 'international-data-science-hackathon-2025')->first();

        // 1. Existing Arya Profile (Frontend & UI/UX)
        $aryaProfile = StudentProfile::where('email', 'mahasiswa@student.ac.id')->first();
        if ($aryaProfile) {
            StudentSkill::updateOrCreate(
                ['student_profile_id' => $aryaProfile->id, 'skill_name' => 'Tailwind CSS & Blade'],
                ['proficiency_level' => 'intermediate', 'tools' => 'Tailwind v3, Alpine.js, VS Code']
            );
            StudentSkill::updateOrCreate(
                ['student_profile_id' => $aryaProfile->id, 'skill_name' => 'UI Wireframing & Prototyping'],
                ['proficiency_level' => 'intermediate', 'tools' => 'Figma, FigJam']
            );

            if ($catFrontend) {
                StudentInterest::updateOrCreate(
                    ['student_profile_id' => $aryaProfile->id, 'interest_category_id' => $catFrontend->id],
                    ['interest_level' => 5, 'experience_level' => 3]
                );
            }
            if ($catUIUX) {
                StudentInterest::updateOrCreate(
                    ['student_profile_id' => $aryaProfile->id, 'interest_category_id' => $catUIUX->id],
                    ['interest_level' => 4, 'experience_level' => 3]
                );
            }

            // Arya registered to Hackathon (self-service)
            if ($compHackathon) {
                CompetitionRegistration::updateOrCreate(
                    ['competition_id' => $compHackathon->id, 'student_profile_id' => $aryaProfile->id],
                    [
                        'user_id' => $aryaProfile->user_id,
                        'registration_source' => 'web_self',
                        'status' => 'confirmed',
                        'admin_notes' => 'Pendaftaran online disetujui. Berkas portofolio valid.',
                        'confirmed_at' => now()->subDays(1),
                    ]
                );
            }
        }

        // 2. Demo Student: Bintang Pratama (Cybersecurity enthusiast)
        $bintang = StudentProfile::updateOrCreate(
            ['email' => 'bintang.pratama@student.ac.id'],
            [
                'full_name' => 'Bintang Pratama Nugraha',
                'nim' => '24060123130045',
                'angkatan' => '2023',
                'phone' => '081234567892',
                'instagram_handle' => '@bintang.sec',
                'github_url' => 'https://github.com/bintangsec',
                'bio' => 'Spesialis Web Penetration Testing dan Reverse Engineering. Aktif berburu vulnerability dan bermain CTF.',
                'is_verified' => true,
                'consent_data_processing' => true,
                'consent_achievement_public' => true,
                'created_by_admin_id' => $staff?->id,
            ]
        );
        StudentSkill::updateOrCreate(
            ['student_profile_id' => $bintang->id, 'skill_name' => 'Web App Penetration Testing'],
            ['proficiency_level' => 'advanced', 'tools' => 'Burp Suite, OWASP ZAP, Kali Linux']
        );
        StudentSkill::updateOrCreate(
            ['student_profile_id' => $bintang->id, 'skill_name' => 'Linux System Administration'],
            ['proficiency_level' => 'intermediate', 'tools' => 'Ubuntu, Bash Scripting']
        );
        if ($catCyber) {
            StudentInterest::updateOrCreate(
                ['student_profile_id' => $bintang->id, 'interest_category_id' => $catCyber->id],
                ['interest_level' => 5, 'experience_level' => 4]
            );
        }

        // Staff registered Bintang via Instagram DM inquiry!
        if ($compCTF) {
            CompetitionRegistration::updateOrCreate(
                ['competition_id' => $compCTF->id, 'student_profile_id' => $bintang->id],
                [
                    'registration_source' => 'instagram_dm',
                    'registered_by_staff_id' => $staff?->id,
                    'status' => 'confirmed',
                    'admin_notes' => 'Pendaftar menghubungi via Instagram DM @minbak_ti pada 10 Okt 2026. Data diverifikasi oleh Staf Rina.',
                    'confirmed_at' => now()->subDays(2),
                ]
            );
        }

        // 3. Demo Student: Clarissa Amalia (Data Science & AI) with past verified achievement
        $clarissa = StudentProfile::updateOrCreate(
            ['email' => 'clarissa.amalia@student.ac.id'],
            [
                'full_name' => 'Clarissa Amalia Putri',
                'nim' => '24060122120018',
                'angkatan' => '2022',
                'phone' => '081234567893',
                'instagram_handle' => '@clarissa_ds',
                'github_url' => 'https://github.com/clarissads',
                'bio' => 'Peneliti muda bidang data science, machine learning, dan analisis prediktif dengan rekam jejak lomba internasional.',
                'is_verified' => true,
                'consent_data_processing' => true,
                'consent_achievement_public' => true,
                'created_by_admin_id' => $ketua?->id,
            ]
        );
        StudentSkill::updateOrCreate(
            ['student_profile_id' => $clarissa->id, 'skill_name' => 'Machine Learning & Python'],
            ['proficiency_level' => 'advanced', 'tools' => 'PyTorch, Scikit-Learn, Pandas']
        );
        if ($catData) {
            StudentInterest::updateOrCreate(
                ['student_profile_id' => $clarissa->id, 'interest_category_id' => $catData->id],
                ['interest_level' => 5, 'experience_level' => 5]
            );
        }

        if ($compPastData) {
            ParticipationRecord::updateOrCreate(
                ['student_profile_id' => $clarissa->id, 'competition_id' => $compPastData->id],
                [
                    'participation_date' => now()->subMonths(4),
                    'role_description' => 'Lead Data Scientist & Modeling Architect',
                ]
            );

            AchievementRecord::updateOrCreate(
                ['student_profile_id' => $clarissa->id, 'competition_id' => $compPastData->id],
                [
                    'achievement_title' => 'Runner Up (Juara 2) ASEAN Data Science Hackathon',
                    'rank_position' => 2,
                    'level' => 'internasional',
                    'verification_status' => 'verified',
                    'verified_by_id' => $ketua?->id,
                    'verified_at' => now()->subMonths(3),
                    'official_publication_url' => 'https://aseandse.org/winners-2025',
                    'is_public_published' => true,
                    'notes' => 'Prestasi telah diverifikasi melalui sertifikat resmi dan publikasi resmi panitia ASEAN DSE.',
                ]
            );
        }

        // 4. Demo Student: Dimas Bagas (Olahraga Futsal)
        $dimas = StudentProfile::updateOrCreate(
            ['email' => 'dimas.bagas@student.ac.id'],
            [
                'full_name' => 'Dimas Bagas Satria',
                'nim' => '24060124140089',
                'angkatan' => '2024',
                'phone' => '081234567894',
                'instagram_handle' => '@dimasbagas_s',
                'bio' => 'Pemain jangkar futsal tim Teknik Informatika. Berkomitmen menjaga performa fisik dan sportivitas tim.',
                'is_verified' => true,
                'consent_data_processing' => true,
                'consent_achievement_public' => true,
                'created_by_admin_id' => $staff?->id,
            ]
        );
        StudentSkill::updateOrCreate(
            ['student_profile_id' => $dimas->id, 'skill_name' => 'Taktik Futsal & Stamina'],
            ['proficiency_level' => 'advanced', 'tools' => 'Anchor / Pivot position']
        );
        if ($catFutsal) {
            StudentInterest::updateOrCreate(
                ['student_profile_id' => $dimas->id, 'interest_category_id' => $catFutsal->id],
                ['interest_level' => 5, 'experience_level' => 4]
            );
        }
        if ($compFutsal) {
            CompetitionRegistration::updateOrCreate(
                ['competition_id' => $compFutsal->id, 'student_profile_id' => $dimas->id],
                [
                    'registration_source' => 'whatsapp',
                    'registered_by_staff_id' => $staff?->id,
                    'status' => 'confirmed',
                    'admin_notes' => 'Pendaftaran melalui WhatsApp koordinator cabor futsal. Terpilih sebagai kapten regu.',
                    'confirmed_at' => now()->subDays(3),
                ]
            );
        }
    }
}
