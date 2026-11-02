<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Essential operational data is always seeded.
     * Demo accounts and mock student data are strictly restricted to non-production environments.
     */
    public function run(): void
    {
        // 1. Core Operational Foundation (Production-Safe)
        $this->call([
            InterestCategoriesSeeder::class,
            AppSettingSeeder::class,
            SurveyCampaignAndQuestionsSeeder::class,
        ]);

        // 2. Mock & Demonstration Data (Strictly Local / Testing Only)
        if (!app()->isProduction()) {
            $this->call([
                UserAndRoleSeeder::class,
                CompetitionsSeeder::class,
                StudentProfilesAndDemoDataSeeder::class,
            ]);
        } else {
            $this->command?->warn('Production environment terdeteksi: Akun pengurus demo dan data simulasi mahasiswa dilewati secara aman.');
        }
    }
}
