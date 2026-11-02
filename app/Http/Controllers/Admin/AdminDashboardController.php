<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentProfile;
use App\Models\Competition;
use App\Models\CompetitionRegistration;
use App\Models\AchievementRecord;
use App\Models\SurveyResponse;
use App\Models\InterestCategory;
use App\Models\AuditLog;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Core Summary Metrics
        $totalStudents = StudentProfile::count();
        $verifiedStudents = StudentProfile::where('is_verified', true)->count();
        $activeCompetitions = Competition::where('status', 'open')->count();
        $pendingRegistrationsCount = CompetitionRegistration::where('status', 'submitted')->count();
        $totalParticipations = CompetitionRegistration::whereIn('status', ['confirmed', 'competing', 'completed'])->count();
        $verifiedAchievements = AchievementRecord::where('verification_status', 'verified')->count();
        $totalAssessmentResponses = SurveyResponse::count();

        // 2. Priority Action Items: Pending registrations awaiting review
        $pendingRegistrations = CompetitionRegistration::with(['competition', 'profile'])
            ->where('status', 'submitted')
            ->latest()
            ->limit(5)
            ->get();

        // 3. Upcoming Deadlines (< 14 days)
        $upcomingDeadlines = Competition::where('status', 'open')
            ->whereNotNull('registration_deadline')
            ->where('registration_deadline', '>=', now())
            ->orderBy('registration_deadline')
            ->limit(5)
            ->get();

        // 4. Interest Categories Distribution
        $categoriesDistribution = InterestCategory::where('is_active', true)
            ->withCount('studentInterests')
            ->orderByDesc('student_interests_count')
            ->limit(6)
            ->get();

        // 5. Recent System Audit Activities
        $recentAudits = AuditLog::with('user')
            ->latest('created_at')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'verifiedStudents',
            'activeCompetitions',
            'pendingRegistrationsCount',
            'totalParticipations',
            'verifiedAchievements',
            'totalAssessmentResponses',
            'pendingRegistrations',
            'upcomingDeadlines',
            'categoriesDistribution',
            'recentAudits'
        ));
    }
}
