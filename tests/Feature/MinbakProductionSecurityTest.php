<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Competition;
use App\Models\CompetitionRegistration;
use App\Models\AchievementRecord;
use App\Models\AuditLog;
use App\Models\SurveyCampaign;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\InterestCategory;
use App\Services\AssessmentScoringService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class MinbakProductionSecurityTest extends TestCase
{
    public function test_security_headers_present_on_web_requests()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_csv_export_sanitizes_formula_injection_characters()
    {
        $ketua = User::where('role', 'ketua_divisi')->first();
        if (!$ketua) {
            $ketua = User::create([
                'name' => 'Ketua Test CSV',
                'email' => 'ketua_csv@minbak.ti',
                'password' => bcrypt('password123'),
                'role' => 'ketua_divisi',
                'is_active' => true,
            ]);
        }

        // Create student with malicious spreadsheet formula string in full_name
        $maliciousStudent = StudentProfile::create([
            'nim' => '99999999999999',
            'full_name' => '=HYPERLINK("http://attacker.com","Click")',
            'batch_year' => 2026,
            'email' => 'malicious_csv@student.ac.id',
            'phone' => '+6281299998888',
            'is_verified' => true,
        ]);

        $response = $this->actingAs($ketua)->get('/admin/mahasiswa/export-csv');
        $response->assertStatus(200);

        $content = $response->streamedContent();

        // Must be prefixed with a single quote to neutralize formula execution in Excel/Calc
        $this->assertStringContainsString("'=HYPERLINK", $content);
        $this->assertStringContainsString("'+6281299998888", $content);

        $maliciousStudent->delete();
    }

    public function test_private_proof_download_prevents_idor()
    {
        Storage::fake('local');
        $fakePath = 'private_proofs/test_proof_' . uniqid() . '.pdf';
        Storage::disk('local')->put($fakePath, '%PDF-1.4 test content');

        $studentA = StudentProfile::first();
        $studentB = StudentProfile::skip(1)->first() ?? StudentProfile::create([
            'nim' => '88888888888888',
            'full_name' => 'Student B Distinct',
            'batch_year' => 2025,
            'email' => 'student_b@student.ac.id',
            'is_verified' => true,
        ]);

        // Create user accounts for both students
        $userA = User::firstOrCreate(
            ['email' => 'user_a_proof@student.ac.id'],
            ['name' => 'User A', 'password' => bcrypt('secret123'), 'role' => 'mahasiswa', 'is_active' => true]
        );
        $studentA->update(['user_id' => $userA->id]);

        $userB = User::firstOrCreate(
            ['email' => 'user_b_proof@student.ac.id'],
            ['name' => 'User B', 'password' => bcrypt('secret123'), 'role' => 'mahasiswa', 'is_active' => true]
        );
        $studentB->update(['user_id' => $userB->id]);

        // Create achievement belonging to Student A
        $competition = Competition::first();
        $achievement = AchievementRecord::create([
            'student_profile_id' => $studentA->id,
            'competition_id' => $competition->id,
            'achievement_title' => 'Juara 1 Hackathon IDOR Test',
            'level' => 'nasional',
            'rank_position' => 1,
            'proof_document_path' => $fakePath,
            'verification_status' => 'pending',
            'is_public_published' => false,
        ]);

        // 1. Unauthenticated guest attempting download -> Redirect to login
        $responseGuest = $this->get("/portal/prestasi/{$achievement->id}/bukti");
        $responseGuest->assertRedirect('/login');

        // 2. Student B (different student) attempting download -> 403 Forbidden (IDOR prevented)
        $responseStudentB = $this->actingAs($userB)->get("/portal/prestasi/{$achievement->id}/bukti");
        $responseStudentB->assertStatus(403);

        // 3. Student A (owner) attempting download -> 200 Download Streamed
        $responseStudentA = $this->actingAs($userA)->get("/portal/prestasi/{$achievement->id}/bukti");
        $responseStudentA->assertStatus(200);

        // 4. Staff attempting download via admin route -> 200 Download Streamed
        $staff = User::where('role', 'staff')->first();
        $responseStaff = $this->actingAs($staff)->get("/admin/prestasi/{$achievement->id}/bukti");
        $responseStaff->assertStatus(200);

        $achievement->delete();
    }

    public function test_registration_invalid_status_transition_is_rejected()
    {
        $staff = User::where('role', 'staff')->first();
        $student = StudentProfile::first();
        $competition = Competition::first();

        $registration = CompetitionRegistration::firstOrCreate(
            ['competition_id' => $competition->id, 'student_profile_id' => $student->id],
            ['registration_source' => 'web_self', 'status' => 'completed']
        );
        $registration->update(['status' => 'completed']);

        // Completed is a terminal state; trying to revert to submitted must be rejected
        $response = $this->actingAs($staff)->post("/admin/pendaftaran/{$registration->id}/status", [
            'status' => 'submitted',
            'admin_notes' => 'Attempting illegal status rollback',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals('completed', $registration->fresh()->status);
    }

    public function test_superadmin_cannot_demote_themselves_in_user_management()
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        $this->assertNotNull($superAdmin);

        $response = $this->actingAs($superAdmin)->put("/admin/pengguna/{$superAdmin->id}", [
            'name' => $superAdmin->name,
            'email' => $superAdmin->email,
            'role' => 'mahasiswa', // Attempting to demote self
            'is_active' => true,
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals('super_admin', $superAdmin->fresh()->role);
    }

    public function test_audit_log_redacts_passwords_and_sensitive_tokens_in_metadata()
    {
        $log = AuditLog::log('user_login_test', 'Audit test description', 'users', 1, [
            'safe_param' => 'application_action',
            'password' => 'secret_plain_text_password',
            'access_token' => 'tok_9876543210_live',
            'nested' => [
                'api_key' => 'key_abcdef123456',
                'description' => 'valid nested info',
            ]
        ]);

        $this->assertNotNull($log);
        $metadataJson = $log->metadata;

        $this->assertStringNotContainsString('secret_plain_text_password', $metadataJson);
        $this->assertStringNotContainsString('tok_9876543210_live', $metadataJson);
        $this->assertStringNotContainsString('key_abcdef123456', $metadataJson);

        $this->assertStringContainsString('[REDACTED]', $metadataJson);
        $this->assertStringContainsString('application_action', $metadataJson);
        $this->assertStringContainsString('valid nested info', $metadataJson);

        $log->delete();
    }

    public function test_scoring_engine_tie_breaking_is_deterministic()
    {
        $service = new AssessmentScoringService();
        $campaign = SurveyCampaign::where('is_active', true)->first();

        // Create response with no answers to trigger equal fallback scores
        $response = SurveyResponse::create([
            'campaign_id' => $campaign->id,
            'respondent_name' => 'Deterministic Tie Test',
            'respondent_email' => 'tie_test@student.ac.id',
            'respondent_angkatan' => '2026',
            'access_token' => 'tie-token-' . uniqid(),
            'consent_agreed' => true,
        ]);

        $result1 = $service->compute($response);
        $topList1 = json_decode($result1->top_recommendations, true);

        // Re-compute again on identical input
        $result2 = $service->compute($response);
        $topList2 = json_decode($result2->top_recommendations, true);

        $this->assertCount(3, $topList1);
        $this->assertCount(3, $topList2);

        // Deterministic identical order
        $this->assertEquals($topList1[0]['category_name'], $topList2[0]['category_name']);
        $this->assertEquals($topList1[1]['category_name'], $topList2[1]['category_name']);
        $this->assertEquals($topList1[2]['category_name'], $topList2[2]['category_name']);

        $response->answers()->delete();
        $response->result()->delete();
        $response->delete();
    }

    public function test_rapid_duplicate_assessment_submission_redirects_to_existing_token()
    {
        $campaign = SurveyCampaign::with('questions.options')->where('is_active', true)->first();
        $this->assertNotNull($campaign);

        $email = 'rapid_submit_' . uniqid() . '@student.ac.id';
        $answers = [];
        foreach ($campaign->questions as $q) {
            if ($q->options->isNotEmpty()) {
                $answers[$q->id] = $q->options->first()->id;
            }
        }

        // First submission
        $res1 = $this->post("/asesmen/{$campaign->slug}/submit", [
            'respondent_name' => 'Rapid Submitter',
            'respondent_email' => $email,
            'respondent_angkatan' => '2026',
            'consent_agreed' => 1,
            'answers' => $answers,
        ]);

        $res1->assertStatus(302);
        $loc1 = $res1->headers->get('Location');

        // Immediate second submission with identical email
        $res2 = $this->post("/asesmen/{$campaign->slug}/submit", [
            'respondent_name' => 'Rapid Submitter',
            'respondent_email' => $email,
            'respondent_angkatan' => '2026',
            'consent_agreed' => 1,
            'answers' => $answers,
        ]);

        $res2->assertStatus(302);
        $loc2 = $res2->headers->get('Location');

        // Both redirect to the exact same token URL
        $this->assertEquals($loc1, $loc2);

        // SurveyResponse count for this email remains 1
        $count = SurveyResponse::where('respondent_email', $email)->count();
        $this->assertEquals(1, $count);
    }
}
