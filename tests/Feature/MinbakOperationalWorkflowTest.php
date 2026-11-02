<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Competition;
use App\Models\StudentProfile;
use App\Models\SurveyCampaign;
use App\Models\SurveyQuestion;
use App\Models\InterestCategory;
use App\Models\AuditLog;

class MinbakOperationalWorkflowTest extends TestCase
{
    public function test_public_pages_render_successfully()
    {
        $this->get('/')->assertStatus(200);
        $this->get('/eksplorasi')->assertStatus(200);
        $this->get('/kompetisi')->assertStatus(200);
        $this->get('/prestasi')->assertStatus(200);
        $this->get('/tentang')->assertStatus(200);
        $this->get('/kebijakan-privasi')->assertStatus(200);
        $this->get('/asesmen')->assertStatus(200);
    }

    public function test_exploration_field_detail_renders()
    {
        $category = InterestCategory::where('is_active', true)->first();
        if ($category) {
            $response = $this->get('/eksplorasi/' . $category->slug);
            $response->assertStatus(200);
            $response->assertSee($category->name);
        }
    }

    public function test_competition_detail_renders()
    {
        $competition = Competition::where('status', 'open')->first();
        if ($competition) {
            $response = $this->get('/kompetisi/' . $competition->slug);
            $response->assertStatus(200);
            $response->assertSee($competition->title);
        }
    }

    public function test_assessment_campaign_submission_and_result()
    {
        $campaign = SurveyCampaign::with('questions.options')->where('is_active', true)->first();
        $this->assertNotNull($campaign);

        $answers = [];
        foreach ($campaign->questions as $q) {
            if ($q->options->isNotEmpty()) {
                $answers[$q->id] = $q->options->first()->id;
            } else {
                $answers[$q->id] = 'Sample answer text';
            }
        }

        $response = $this->post("/asesmen/{$campaign->slug}/submit", [
            'respondent_name' => 'Budi Sanjaya',
            'respondent_email' => 'budi.sanjaya@student.ac.id',
            'respondent_angkatan' => '2026',
            'respondent_nim' => '26101152630001',
            'consent_agreed' => 1,
            'answers' => $answers,
        ]);

        $response->assertStatus(302);
        // Should redirect to result with access token
        $location = $response->headers->get('Location');
        $this->assertTrue(str_contains($location, '/asesmen/hasil/'));

        // Visit the result URL directly using the redirect location
        $resultResponse = $this->get($location);
        $resultResponse->assertStatus(200);
        $resultResponse->assertSee('Rekomendasi Utama');
    }
}
