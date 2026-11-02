<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SurveyCampaign;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use App\Models\AssessmentResult;
use App\Models\StudentProfile;
use App\Models\AuditLog;
use App\Services\AssessmentScoringService;

class AssessmentController extends Controller
{
    public function index()
    {
        $campaigns = SurveyCampaign::where('is_active', true)->latest()->get();
        return view('public.assessment.index', compact('campaigns'));
    }

    public function show(string $slug)
    {
        $campaign = SurveyCampaign::with(['questions.options'])
            ->where('slug', $slug)
            ->firstOrFail();

        if (!$campaign->isActiveNow()) {
            return view('public.assessment.closed', compact('campaign'));
        }

        // Group questions by step
        $step1Questions = $campaign->questions->where('step_number', 1)->values();
        $step2Questions = $campaign->questions->where('step_number', 2)->values();
        $step3Questions = $campaign->questions->where('step_number', 3)->values();

        return view('public.assessment.form', compact('campaign', 'step1Questions', 'step2Questions', 'step3Questions'));
    }

    public function submit(Request $request, string $slug, AssessmentScoringService $scoringService)
    {
        $campaign = SurveyCampaign::where('slug', $slug)->firstOrFail();

        if (!$campaign->isActiveNow()) {
            return back()->with('error', 'Kampanye asesmen ini sedang ditutup atau belum dimulai.');
        }

        $validated = $request->validate([
            'respondent_name' => 'required|string|max:150',
            'respondent_email' => 'required|email|max:150',
            'respondent_angkatan' => 'required|string|max:10',
            'respondent_nim' => 'nullable|string|max:30',
            'consent_agreed' => 'required|accepted',
            'answers' => 'required|array',
            'answers.*' => 'required',
        ], [
            'consent_agreed.accepted' => 'Anda wajib menyetujui pemrosesan data untuk keperluan asesmen minat dan bakat.',
            'respondent_name.required' => 'Nama lengkap wajib diisi.',
            'respondent_email.required' => 'Alamat email wajib diisi.',
            'respondent_angkatan.required' => 'Angkatan wajib dipilih.',
            'answers.required' => 'Semua pertanyaan wajib dijawab.',
        ]);

        // Link with existing user/profile if authenticated
        $userId = auth()->id();
        $studentProfileId = null;

        if ($userId) {
            $profile = auth()->user()->studentProfile;
            $studentProfileId = $profile?->id;
        } else {
            // Find student profile by email or NIM if already recorded
            $existingProfile = StudentProfile::where('email', $validated['respondent_email'])->first();
            $studentProfileId = $existingProfile?->id;
        }

        $normalizedEmail = strtolower(trim($validated['respondent_email']));

        // Check for rapid duplicate submission (within 10 minutes)
        $recentResponse = SurveyResponse::where('campaign_id', $campaign->id)
            ->where('respondent_email', $normalizedEmail)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->latest()
            ->first();

        if ($recentResponse && $recentResponse->access_token) {
            return redirect()->route('assessment.result', ['token' => $recentResponse->access_token])
                ->with('info', 'Kamu baru saja mengirimkan asesmen untuk kampanye ini. Ini adalah hasil rekomendasi terbarumu.');
        }

        // Generate unguessable secure token for results access
        $accessToken = Str::random(40);
        $ipHash = hash('sha256', $request->ip() . config('app.key'));

        // Create Response record
        $response = SurveyResponse::create([
            'campaign_id' => $campaign->id,
            'user_id' => $userId,
            'student_profile_id' => $studentProfileId,
            'respondent_name' => strip_tags($validated['respondent_name']),
            'respondent_email' => strtolower(trim($validated['respondent_email'])),
            'respondent_angkatan' => $validated['respondent_angkatan'],
            'respondent_nim' => !empty($validated['respondent_nim']) ? strip_tags($validated['respondent_nim']) : null,
            'access_token' => $accessToken,
            'consent_agreed' => true,
            'ip_hash' => $ipHash,
        ]);

        // Save Answers
        foreach ($validated['answers'] as $qId => $optVal) {
            if (is_numeric($optVal)) {
                SurveyAnswer::create([
                    'response_id' => $response->id,
                    'question_id' => (int) $qId,
                    'selected_option_id' => (int) $optVal,
                ]);
            } else {
                SurveyAnswer::create([
                    'response_id' => $response->id,
                    'question_id' => (int) $qId,
                    'answer_text' => is_string($optVal) ? strip_tags($optVal) : json_encode($optVal),
                ]);
            }
        }

        // Run scoring engine immediately
        $scoringService->compute($response);

        // Audit log
        AuditLog::log('assessment_submitted', "Responden {$response->respondent_name} menyelesaikan asesmen kampanye {$campaign->title}", 'survey_responses', $response->id);

        return redirect()->route('assessment.result', ['token' => $accessToken])
            ->with('success', 'Asesmen berhasil diselesaikan! Berikut adalah rekomendasi jalur talenta untukmu.');
    }

    public function result(string $token)
    {
        $response = SurveyResponse::with(['campaign', 'result.primaryCategory', 'result.secondaryCategory', 'result.tertiaryCategory'])
            ->where('access_token', $token)
            ->firstOrFail();

        $result = $response->result;
        if (!$result) {
            abort(404, 'Hasil asesmen belum selesai diproses.');
        }

        $recommendations = $result->recommendations_list;
        $dimensions = $result->dimensions;
        $scores = $result->scores;

        return view('public.assessment.result', compact('response', 'result', 'recommendations', 'dimensions', 'scores', 'token'));
    }
}
