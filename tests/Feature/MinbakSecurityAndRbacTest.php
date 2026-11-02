<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use App\Models\StudentProfile;
use App\Models\CompetitionRegistration;
use App\Models\AuditLog;

class MinbakSecurityAndRbacTest extends TestCase
{
    public function test_guest_is_redirected_from_admin()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_mahasiswa_cannot_access_admin_dashboard()
    {
        $mahasiswa = User::where('role', 'mahasiswa')->first();
        if (!$mahasiswa) {
            $mahasiswa = User::create([
                'name' => 'Test Mahasiswa',
                'email' => 'mhs_test@student.ac.id',
                'password' => bcrypt('password123'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ]);
        }

        $response = $this->actingAs($mahasiswa)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_staff_can_access_admin_but_cannot_export_csv_or_manage_settings()
    {
        $staff = User::where('role', 'staff')->first();
        if (!$staff) {
            $staff = User::create([
                'name' => 'Test Staff',
                'email' => 'staff_test@minbak.ti',
                'password' => bcrypt('password123'),
                'role' => 'staff',
                'is_active' => true,
            ]);
        }

        // Staff can access admin dashboard & competitions
        $responseDashboard = $this->actingAs($staff)->get('/admin');
        $responseDashboard->assertStatus(200);

        $responseCompetitions = $this->actingAs($staff)->get('/admin/kompetisi');
        $responseCompetitions->assertStatus(200);

        // Staff cannot access export CSV (Ketua or above only)
        $responseExport = $this->actingAs($staff)->get('/admin/mahasiswa/export-csv');
        $responseExport->assertStatus(403);

        // Staff cannot access system settings (Super Admin only)
        $responseSettings = $this->actingAs($staff)->get('/admin/pengaturan');
        $responseSettings->assertStatus(403);
    }

    public function test_ketua_divisi_can_export_csv()
    {
        $ketua = User::where('role', 'ketua_divisi')->first();
        if (!$ketua) {
            $ketua = User::create([
                'name' => 'Test Ketua',
                'email' => 'ketua_test@minbak.ti',
                'password' => bcrypt('password123'),
                'role' => 'ketua_divisi',
                'is_active' => true,
            ]);
        }

        $response = $this->actingAs($ketua)->get('/admin/mahasiswa/export-csv');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
    }

    public function test_super_admin_can_access_settings_and_user_management()
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        if (!$superAdmin) {
            $superAdmin = User::create([
                'name' => 'Test SuperAdmin',
                'email' => 'sa_test@minbak.ti',
                'password' => bcrypt('password123'),
                'role' => 'super_admin',
                'is_active' => true,
            ]);
        }

        $responseSettings = $this->actingAs($superAdmin)->get('/admin/pengaturan');
        $responseSettings->assertStatus(200);

        $responseUsers = $this->actingAs($superAdmin)->get('/admin/pengguna');
        $responseUsers->assertStatus(200);
    }

    public function test_prevents_duplicate_competition_registrations()
    {
        $staff = User::where('role', 'staff')->first();
        $student = StudentProfile::first();
        $competition = Competition::first();

        $this->assertNotNull($student);
        $this->assertNotNull($competition);

        // Ensure first registration exists
        CompetitionRegistration::firstOrCreate(
            ['competition_id' => $competition->id, 'student_profile_id' => $student->id],
            ['registration_source' => 'web_self', 'status' => 'confirmed']
        );

        // Attempt second registration via staff manual entry
        $response = $this->actingAs($staff)->post('/admin/pendaftaran/manual', [
            'competition_id' => $competition->id,
            'student_profile_id' => $student->id,
            'registration_source' => 'instagram_dm',
            'status' => 'confirmed',
            'admin_notes' => 'Attempting duplicate',
        ]);

        $response->assertSessionHas('error');
    }
}
