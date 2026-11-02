<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InterestCategory;
use App\Models\Competition;

class InterestExplorationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = InterestCategory::where('is_active', true);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('common_skills', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('order_index')->get();

        return view('public.exploration.index', compact('categories', 'search'));
    }

    public function show(string $slug)
    {
        $category = InterestCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Related active competitions
        $competitions = Competition::where('interest_category_id', $category->id)
            ->where('status', 'open')
            ->orderBy('registration_deadline')
            ->get();

        // Other related categories
        $relatedCategories = InterestCategory::where('is_active', true)
            ->where('id', '!=', $category->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('public.exploration.show', compact('category', 'competitions', 'relatedCategories'));
    }
}
