<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SurveyCampaign;
use App\Models\SurveyQuestion;
use App\Models\SurveyOption;
use App\Models\SurveyResponse;
use App\Models\AssessmentResult;
use App\Models\InterestCategory;
use App\Models\AuditLog;

class AdminAssessmentController extends Controller
{
    public function index()
    {
        $campaigns = SurveyCampaign::withCount('responses')->latest()->get();

        // 1. Overall Aggregated Analytics
        $totalResponses = SurveyResponse::count();
        $totalVerified = SurveyResponse::where('is_verified', true)->count();

        // 2. Category interest distribution from primary recommendations
        $categoryDistribution = AssessmentResult::join('interest_categories', 'assessment_results.primary_category_id', '=', 'interest_categories.id')
            ->selectRaw('interest_categories.name, count(*) as count')
            ->groupBy('interest_categories.name')
            ->orderByDesc('count')
            ->get();

        // 3. Response distribution by angkatan
        $angkatanDistribution = SurveyResponse::selectRaw('respondent_angkatan, count(*) as count')
            ->groupBy('respondent_angkatan')
            ->orderByDesc('respondent_angkatan')
            ->get();

        return view('admin.assessments.index', compact(
            'campaigns',
            'totalResponses',
            'totalVerified',
            'categoryDistribution',
            'angkatanDistribution'
        ));
    }

    public function show(int $id)
    {
        $campaign = SurveyCampaign::with([
            'questions.options',
            'responses.result.primaryCategory',
            'responses.profile',
        ])->findOrFail($id);

        $responses = $campaign->responses()->latest()->paginate(20);

        return view('admin.assessments.show', compact('campaign', 'responses'));
    }

    public function create()
    {
        return view('admin.assessments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'academic_year' => 'required|string|max:20',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'require_login' => 'nullable|boolean',
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (SurveyCampaign::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-" . (++$counter);
        }

        $qrCodeToken = 'CAMP-' . strtoupper(Str::random(10));

        $campaign = SurveyCampaign::create([
            'title' => strip_tags($validated['title']),
            'slug' => $slug,
            'academic_year' => $validated['academic_year'],
            'description' => $validated['description'] ? strip_tags($validated['description']) : null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $request->boolean('is_active'),
            'require_login' => $request->boolean('require_login'),
            'qr_code_token' => $qrCodeToken,
            'created_by_id' => auth()->id(),
        ]);

        AuditLog::log('admin_create_campaign', "Membuat kampanye asesmen baru: {$campaign->title}", 'survey_campaigns', $campaign->id);

        return redirect()->route('admin.assessments.show', $campaign->id)->with('success', 'Kampanye asesmen berhasil dibuat.');
    }

    public function toggleActive(int $id)
    {
        $campaign = SurveyCampaign::findOrFail($id);
        $campaign->is_active = !$campaign->is_active;
        $campaign->save();

        AuditLog::log('admin_toggle_campaign', "Status kampanye {$campaign->title} diubah menjadi: " . ($campaign->is_active ? 'Aktif' : 'Non-Aktif'), 'survey_campaigns', $campaign->id);

        return back()->with('success', "Status kampanye berhasil diubah.");
    }
}
