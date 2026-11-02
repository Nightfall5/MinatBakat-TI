<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\StudentProfile;

class UserAndRoleSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->error('Peringatan Keamanan: Seeder akun demo tidak diizinkan dijalankan pada environment production.');
            return;
        }

        // 1. Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@minbak.ti'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('SuperAdminMinbak2026!'),
                'role' => 'super_admin',
                'is_active' => true,
                'phone' => '081200000001',
            ]
        );

        // 2. Ketua Divisi
        User::updateOrCreate(
            ['email' => 'ketua@minbak.ti'],
            [
                'name' => 'Fadhil Ardiansyah (Ketua Minbak)',
                'password' => Hash::make('KetuaMinbak2026!'),
                'role' => 'ketua_divisi',
                'is_active' => true,
                'phone' => '081200000002',
            ]
        );

        // 3. Staff Minat & Bakat
        $staff = User::updateOrCreate(
            ['email' => 'staff@minbak.ti'],
            [
                'name' => 'Rina Salsabila (Staff Operasional)',
                'password' => Hash::make('StaffMinbak2026!'),
                'role' => 'staff',
                'is_active' => true,
                'phone' => '081200000003',
            ]
        );

        // 4. Mahasiswa Contoh 1
        $userMhs1 = User::updateOrCreate(
            ['email' => 'mahasiswa@student.ac.id'],
            [
                'name' => 'Arya Putra Wicaksana',
                'password' => Hash::make('Mahasiswa2026!'),
                'role' => 'mahasiswa',
                'is_active' => true,
                'phone' => '081234567891',
            ]
        );

        StudentProfile::updateOrCreate(
            ['email' => 'mahasiswa@student.ac.id'],
            [
                'user_id' => $userMhs1->id,
                'full_name' => 'Arya Putra Wicaksana',
                'nim' => '24060122140001',
                'angkatan' => '2024',
                'phone' => '081234567891',
                'instagram_handle' => '@aryaputra_w',
                'portfolio_url' => 'https://aryaputra.dev',
                'github_url' => 'https://github.com/aryaputra-dev',
                'bio' => 'Mahasiswa TI 2024 yang antusias mendalami frontend web modern, design system, dan kompetisi UI/UX nasional.',
                'is_verified' => true,
                'consent_data_processing' => true,
                'consent_achievement_public' => true,
                'created_by_admin_id' => $staff->id,
            ]
        );
    }
}
