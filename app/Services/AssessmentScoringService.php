<?php

namespace App\Services;

use App\Models\InterestCategory;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use App\Models\AssessmentResult;
use App\Models\Competition;
use App\Models\AppSetting;

class AssessmentScoringService
{
    /**
     * Compute assessment results for a survey response.
     */
    public function compute(SurveyResponse $response): AssessmentResult
    {
        // 1. Fetch configurable weights from AppSetting
        $weightInterest = (float) AppSetting::get('scoring_weight_interest', '45') / 100;
        $weightActivity = (float) AppSetting::get('scoring_weight_activity', '20') / 100;
        $weightExperience = (float) AppSetting::get('scoring_weight_experience', '20') / 100;
        $weightLearning = (float) AppSetting::get('scoring_weight_learning', '15') / 100;

        $scoringSnapshot = [
            'interest_weight' => $weightInterest,
            'activity_weight' => $weightActivity,
            'experience_weight' => $weightExperience,
            'learning_weight' => $weightLearning,
            'computed_at' => now()->toIso8601String(),
        ];

        // 2. Load all active categories
        $categories = InterestCategory::where('is_active', true)->get();
        $categoryScores = [];
        foreach ($categories as $cat) {
            $categoryScores[$cat->slug] = [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'raw_score' => 0.0,
                'dimension_interest' => 0.0,
                'dimension_activity' => 0.0,
                'dimension_experience' => 0.0,
                'dimension_learning' => 0.0,
            ];
        }

        // Global dimensions accumulator
        $globalDimensions = [
            'experience_level' => 1,
            'competition_interest' => 3,
            'work_style' => 'flexible',
            'learning_method' => 'project_based',
        ];

        // 3. Process each answer
        $answers = SurveyAnswer::with('option', 'question')->where('response_id', $response->id)->get();

        foreach ($answers as $ans) {
            $option = $ans->option;
            if (!$option) continue;

            $weights = $option->weights_array;

            // Check meta dimensions
            if (isset($weights['_experience'])) {
                $globalDimensions['experience_level'] = (int) $weights['_experience'];
            }
            if (isset($weights['_interest'])) {
                $globalDimensions['competition_interest'] = (int) $weights['_interest'];
            }

            // Distribute points to categories
            foreach ($weights as $key => $points) {
                if (str_starts_with($key, '_')) continue;

                if (isset($categoryScores[$key])) {
                    // Step 2 & Step 3 question weights
                    $pointsVal = (float) $points;
                    $step = $ans->question->step_number ?? 2;

                    if ($step === 2) {
                        $categoryScores[$key]['dimension_activity'] += $pointsVal;
                    } else {
                        $categoryScores[$key]['dimension_interest'] += $pointsVal;
                    }
                }
            }
        }

        // Incorporate experience & learning orientation into category calculation
        $expMultiplier = max(1.0, (float) $globalDimensions['experience_level']);
        foreach ($categoryScores as $slug => &$item) {
            $item['dimension_experience'] = $expMultiplier * 3.5;
            $item['dimension_learning'] = 4.0;

            // Total composite score
            $item['raw_score'] = (
                ($item['dimension_interest'] * $weightInterest) +
                ($item['dimension_activity'] * $weightActivity) +
                ($item['dimension_experience'] * $weightExperience) +
                ($item['dimension_learning'] * $weightLearning)
            );
        }
        unset($item);

        // 4. Normalize scores (0 - 100)
        $maxRaw = 0.0;
        foreach ($categoryScores as $item) {
            if ($item['raw_score'] > $maxRaw) {
                $maxRaw = $item['raw_score'];
            }
        }

        $normalized = [];
        foreach ($categoryScores as $slug => $item) {
            $scorePct = $maxRaw > 0 ? round(($item['raw_score'] / $maxRaw) * 100, 1) : 50.0;
            // Floor at 25% minimum baseline for positive presentation
            $scorePct = max(25.0, min(100.0, $scorePct));
            $normalized[$slug] = $scorePct;
            $categoryScores[$slug]['normalized_score'] = $scorePct;
        }

        // Deterministic multi-tier sorting:
        // 1. Normalized score DESC
        // 2. Raw composite score DESC
        // 3. Interest dimension DESC
        // 4. Activity dimension DESC
        // 5. Category Name ASC (alphabetical tie-breaker)
        uasort($categoryScores, function ($a, $b) {
            if ($b['normalized_score'] != $a['normalized_score']) {
                return $b['normalized_score'] <=> $a['normalized_score'];
            }
            if ($b['raw_score'] != $a['raw_score']) {
                return $b['raw_score'] <=> $a['raw_score'];
            }
            if ($b['dimension_interest'] != $a['dimension_interest']) {
                return $b['dimension_interest'] <=> $a['dimension_interest'];
            }
            if ($b['dimension_activity'] != $a['dimension_activity']) {
                return $b['dimension_activity'] <=> $a['dimension_activity'];
            }
            return strcmp($a['name'], $b['name']);
        });

        // 5. Select Top 3 recommendations
        $topThree = array_slice($categoryScores, 0, 3, true);
        $topList = [];

        $rank = 1;
        $primaryId = null;
        $secondaryId = null;
        $tertiaryId = null;

        foreach ($topThree as $slug => $item) {
            $catModel = $categories->firstWhere('id', $item['id']);
            if (!$catModel) continue;

            if ($rank === 1) $primaryId = $catModel->id;
            elseif ($rank === 2) $secondaryId = $catModel->id;
            elseif ($rank === 3) $tertiaryId = $catModel->id;

            // Find related open competitions
            $matchedCompetitions = Competition::where('interest_category_id', $catModel->id)
                ->where('status', 'open')
                ->limit(2)
                ->get(['id', 'title', 'slug', 'level', 'registration_deadline'])
                ->map(fn($c) => [
                    'id' => $c->id,
                    'title' => $c->title,
                    'slug' => $c->slug,
                    'level' => $c->level,
                    'deadline' => $c->registration_deadline?->format('d M Y'),
                ])->toArray();

            $topList[] = [
                'rank' => $rank,
                'category_id' => $catModel->id,
                'category_name' => $catModel->name,
                'category_slug' => $catModel->slug,
                'icon' => $catModel->icon,
                'score' => $item['normalized_score'],
                'reason' => "Jawabanmu menunjukkan kecocokan kuat pada preferensi aktivitas dan ketertarikan di bidang {$catModel->name}. Jalur ini sangat potensial untuk terus diasah melalui proyek mandiri dan delegasi lomba divisi.",
                'suggested_skills' => $catModel->skills_array,
                'starter_projects' => json_decode($catModel->starter_projects, true) ?: [],
                'matched_competitions' => $matchedCompetitions,
            ];

            $rank++;
        }

        // 6. Save or update AssessmentResult
        return AssessmentResult::updateOrCreate(
            ['response_id' => $response->id],
            [
                'student_profile_id' => $response->student_profile_id,
                'primary_category_id' => $primaryId,
                'secondary_category_id' => $secondaryId,
                'tertiary_category_id' => $tertiaryId,
                'top_recommendations' => json_encode($topList),
                'dimension_scores' => json_encode($globalDimensions),
                'normalized_scores' => json_encode($normalized),
                'scoring_config_snapshot' => json_encode($scoringSnapshot),
            ]
        );
    }
}
