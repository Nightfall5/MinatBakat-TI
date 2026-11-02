<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppSetting;

class AppSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name', 'value' => 'MINBAK TI — Talent & Competition Hub', 'group' => 'general'],
            ['key' => 'app_short_name', 'value' => 'MINBAK TI', 'group' => 'general'],
            ['key' => 'org_name', 'value' => 'Himpunan Mahasiswa Teknik Informatika', 'group' => 'general'],
            ['key' => 'univ_name', 'value' => 'Universitas Teknologi Indonesia', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'minbak.ti@example.ac.id', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+62 812-3456-7890', 'group' => 'contact'],
            ['key' => 'instagram_handle', 'value' => '@minbak_ti', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/minbak_ti', 'group' => 'social'],
            ['key' => 'privacy_policy_url', 'value' => '/kebijakan-privasi', 'group' => 'legal'],
            ['key' => 'about_vision', 'value' => 'Menjadi wadah pengembangan potensi, kreativitas, daya saing, dan pencapaian prestasi mahasiswa Teknik Informatika di tingkat regional, nasional, dan internasional.', 'group' => 'about'],
            ['key' => 'about_mission', 'value' => "1. Melakukan pemetaan minat dan bakat mahasiswa secara objektif dan terstruktur.\n2. Memberikan pendampingan serta fasilitas delegasi kompetisi akademik maupun non-akademik.\n3. Membangun rekam jejak prestasi yang transparan, terverifikasi, dan menginspirasi seluruh civitas akademika.", 'group' => 'about'],
            ['key' => 'about_division_programs', 'value' => "• Pemetaan Bakat & Asesmen Talenta Mahasiswa Baru\n• InfoHub Kompetisi & Kurasi Lomba Terverifikasi\n• Bootcamp Persiapan Delegasi & Mentoring Lomba\n• Pembinaan Cabang Olahraga Mahasiswa (Futsal & Voli)\n• Hall of Fame & Rekognisi Prestasi Mahasiswa TI", 'group' => 'about'],
            ['key' => 'scoring_weight_interest', 'value' => '45', 'group' => 'scoring'],
            ['key' => 'scoring_weight_activity', 'value' => '20', 'group' => 'scoring'],
            ['key' => 'scoring_weight_experience', 'value' => '20', 'group' => 'scoring'],
            ['key' => 'scoring_weight_learning', 'value' => '15', 'group' => 'scoring'],
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
