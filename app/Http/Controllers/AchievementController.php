<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AchievementRecord;
use App\Models\InterestCategory;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('kategori');
        $level = $request->query('tingkat');
        $year = $request->query('tahun');
        $search = $request->query('q');

        $query = AchievementRecord::with(['profile', 'competition.category'])
            ->where('verification_status', 'verified')
            ->where('is_public_published', true);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('achievement_title', 'like', "%{$search}%")
                  ->orWhereHas('competition', fn($cq) => $cq->where('title', 'like', "%{$search}%"))
                  ->orWhereHas('profile', fn($pq) => $pq->where('full_name', 'like', "%{$search}%"));
            });
        }

        if ($categorySlug) {
            $query->whereHas('competition.category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($level && $level !== 'all') {
            $query->where('level', $level);
        }

        if ($year && $year !== 'all') {
            $query->whereYear('verified_at', $year);
        }

        $achievements = $query->latest('verified_at')->paginate(12)->withQueryString();
        $categories = InterestCategory::where('is_active', true)->orderBy('name')->get();

        return view('public.achievements.index', compact('achievements', 'categories', 'categorySlug', 'level', 'year', 'search'));
    }
}
