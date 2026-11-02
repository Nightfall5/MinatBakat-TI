<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AssessmentScoringService;
use App\Models\InterestCategory;
use App\Models\SurveyCampaign;
use App\Models\SurveyQuestion;
use App\Models\SurveyOption;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use App\Models\AssessmentResult;
use App\Models\AppSetting;

class AssessmentScoringTest extends TestCase
{
    protected AssessmentScoringService $scoringService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scoringService = new AssessmentScoringService();
    }

    public function test_scoring_weights_defaults_in_settings()
    {
        $weightInterest = (float) AppSetting::get('scoring_weight_interest', '45');
        $weightActivity = (float) AppSetting::get('scoring_weight_activity', '20');
        $weightExperience = (float) AppSetting::get('scoring_weight_experience', '20');
        $weightLearning = (float) AppSetting::get('scoring_weight_learning', '15');

        $total = $weightInterest + $weightActivity + $weightExperience + $weightLearning;
        $this->assertEqualsWithDelta(100.0, $total, 0.01);
    }

    public function test_scoring_service_computes_recommendation_results()
    {
        $campaign = SurveyCampaign::where('is_active', true)->first();
        $this->assertNotNull($campaign);

        // Find a question with options
        $question = SurveyQuestion::with('options')->where('campaign_id', $campaign->id)->first();
        $this->assertNotNull($question);
        $option = $question->options->first();
        $this->assertNotNull($option);

        // Create survey response
        $response = SurveyResponse::create([
            'campaign_id' => $campaign->id,
            'respondent_name' => 'Testing Candidate',
            'respondent_email' => 'candidate_test@student.ac.id',
            'respondent_angkatan' => '2026',
            'access_token' => 'test-token-' . uniqid(),
            'consent_agreed' => true,
        ]);

        // Add answer
        SurveyAnswer::create([
            'response_id' => $response->id,
            'question_id' => $question->id,
            'selected_option_id' => $option->id,
        ]);

        $result = $this->scoringService->compute($response);

        $this->assertInstanceOf(AssessmentResult::class, $result);
        $this->assertEquals($response->id, $result->response_id);
        $this->assertNotNull($result->primary_category_id);
        $this->assertGreaterThanOrEqual(0, $result->primary_score);
        $this->assertLessThanOrEqual(100, $result->primary_score);
        $this->assertIsArray($result->weights_snapshot);
        $this->assertArrayHasKey('interest_weight', $result->weights_snapshot);
    }
}
