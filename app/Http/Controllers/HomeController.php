<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentProfile;
use App\Models\Competition;
use App\Models\CompetitionRegistration;
use App\Models\ParticipationRecord;
use App\Models\AchievementRecord;
use App\Models\InterestCategory;
use App\Models\AppSetting;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Live statistics from database (strictly operational, never hardcoded)
        $registeredStudentsCount = StudentProfile::count();
        $activeCompetitionsCount = Competition::where('status', 'open')->count();
        $participationsCount = ParticipationRecord::count() + 
            CompetitionRegistration::whereIn('status', ['confirmed', 'competing', 'completed'])->count();
        $verifiedAchievementsCount = AchievementRecord::where('verification_status', 'verified')
            ->where('is_public_published', true)
            ->count();

        // 2. Interest categories for exploration preview
        $categories = InterestCategory::where('is_active', true)
            ->orderBy('order_index')
            ->limit(8)
            ->get();

        // 3. Latest published open competitions
        $latestCompetitions = Competition::with('category')
            ->where('status', 'open')
            ->orderBy('registration_deadline')
            ->limit(4)
            ->get();

        // 4. Verified public achievements
        $achievements = AchievementRecord::with(['profile', 'competition'])
            ->where('verification_status', 'verified')
            ->where('is_public_published', true)
            ->latest('verified_at')
            ->limit(3)
            ->get();

        return view('public.home', compact(
            'registeredStudentsCount',
            'activeCompetitionsCount',
            'participationsCount',
            'verifiedAchievementsCount',
            'categories',
            'latestCompetitions',
            'achievements'
        ));
    }
}
