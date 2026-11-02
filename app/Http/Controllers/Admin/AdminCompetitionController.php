<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Competition;
use App\Models\InterestCategory;
use App\Models\AuditLog;

class AdminCompetitionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');
        $status = $request->query('status');
        $level = $request->query('level');

        $query = Competition::with('category')->withCount('registrations');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($level && $level !== 'all') {
            $query->where('level', $level);
        }

        $competitions = $query->orderBy('registration_deadline')->paginate(12)->withQueryString();

        return view('admin.competitions.index', compact('competitions', 'search', 'status', 'level'));
    }

    public function create()
    {
        $categories = InterestCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.competitions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'organizer' => 'required|string|max:150',
            'interest_category_id' => 'nullable|exists:interest_categories,id',
            'level' => 'required|in:internal_kampus,regional,nasional,internasional',
            'format' => 'required|in:online,offline,hybrid',
            'location' => 'nullable|string|max:255',
            'team_type' => 'required|in:individual,team',
            'min_team_members' => 'required|integer|min:1|max:20',
            'max_team_members' => 'required|integer|min:1|max:20',
            'fee_amount' => 'nullable|numeric|min:0',
            'prize_summary' => 'nullable|string|max:500',
            'official_url' => 'nullable|url|max:255',
            'guidebook_url' => 'nullable|url|max:255',
            'poster_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:3072', // max 3MB
            'status' => 'required|in:draft,scheduled,open,closed,ongoing,completed,archived',
            'registration_start' => 'nullable|date',
            'registration_deadline' => 'nullable|date|after_or_equal:registration_start',
            'event_start' => 'nullable|date',
            'event_end' => 'nullable|date|after_or_equal:event_start',
            'description' => 'required|string',
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Competition::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-" . (++$counter);
        }

        $posterPath = null;
        if ($request->hasFile('poster_image')) {
            $file = $request->file('poster_image');
            $fileName = 'comp_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $posterPath = $file->storeAs('posters', $fileName, 'public');
        }

        $competition = Competition::create([
            'title' => strip_tags($validated['title']),
            'slug' => $slug,
            'organizer' => strip_tags($validated['organizer']),
            'description' => $validated['description'],
            'interest_category_id' => $validated['interest_category_id'],
            'level' => $validated['level'],
            'format' => $validated['format'],
            'location' => $validated['location'] ? strip_tags($validated['location']) : null,
            'team_type' => $validated['team_type'],
            'min_team_members' => $validated['min_team_members'],
            'max_team_members' => $validated['max_team_members'],
            'fee_amount' => $validated['fee_amount'] ?? 0,
            'prize_summary' => $validated['prize_summary'] ? strip_tags($validated['prize_summary']) : null,
            'official_url' => $validated['official_url'],
            'guidebook_url' => $validated['guidebook_url'],
            'poster_path' => $posterPath,
            'status' => $validated['status'],
            'registration_start' => $validated['registration_start'],
            'registration_deadline' => $validated['registration_deadline'],
            'event_start' => $validated['event_start'],
            'event_end' => $validated['event_end'],
            'created_by_id' => auth()->id(),
        ]);

        AuditLog::log('admin_create_competition', "Membuat kompetisi baru {$competition->title}", 'competitions', $competition->id);

        return redirect()->route('admin.competitions.show', $competition->id)->with('success', 'Kompetisi berhasil dibuat.');
    }

    public function show(int $id)
    {
        $competition = Competition::with([
            'category',
            'creator',
            'registrations.profile',
            'registrations.staffRegistrar',
            'teams.leader',
            'teams.members.profile',
            'achievements.profile',
        ])->findOrFail($id);

        return view('admin.competitions.show', compact('competition'));
    }

    public function edit(int $id)
    {
        $competition = Competition::findOrFail($id);
        $categories = InterestCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.competitions.edit', compact('competition', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $competition = Competition::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'organizer' => 'required|string|max:150',
            'interest_category_id' => 'nullable|exists:interest_categories,id',
            'level' => 'required|in:internal_kampus,regional,nasional,internasional',
            'format' => 'required|in:online,offline,hybrid',
            'location' => 'nullable|string|max:255',
            'team_type' => 'required|in:individual,team',
            'min_team_members' => 'required|integer|min:1|max:20',
            'max_team_members' => 'required|integer|min:1|max:20',
            'fee_amount' => 'nullable|numeric|min:0',
            'prize_summary' => 'nullable|string|max:500',
            'official_url' => 'nullable|url|max:255',
            'guidebook_url' => 'nullable|url|max:255',
            'poster_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:3072',
            'status' => 'required|in:draft,scheduled,open,closed,ongoing,completed,archived',
            'registration_start' => 'nullable|date',
            'registration_deadline' => 'nullable|date',
            'event_start' => 'nullable|date',
            'event_end' => 'nullable|date',
            'description' => 'required|string',
        ]);

        $posterPath = $competition->poster_path;
        if ($request->hasFile('poster_image')) {
            $file = $request->file('poster_image');
            $fileName = 'comp_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $posterPath = $file->storeAs('posters', $fileName, 'public');
        }

        $competition->update([
            'title' => strip_tags($validated['title']),
            'organizer' => strip_tags($validated['organizer']),
            'description' => $validated['description'],
            'interest_category_id' => $validated['interest_category_id'],
            'level' => $validated['level'],
            'format' => $validated['format'],
            'location' => $validated['location'] ? strip_tags($validated['location']) : null,
            'team_type' => $validated['team_type'],
            'min_team_members' => $validated['min_team_members'],
            'max_team_members' => $validated['max_team_members'],
            'fee_amount' => $validated['fee_amount'] ?? 0,
            'prize_summary' => $validated['prize_summary'] ? strip_tags($validated['prize_summary']) : null,
            'official_url' => $validated['official_url'],
            'guidebook_url' => $validated['guidebook_url'],
            'poster_path' => $posterPath,
            'status' => $validated['status'],
            'registration_start' => $validated['registration_start'],
            'registration_deadline' => $validated['registration_deadline'],
            'event_start' => $validated['event_start'],
            'event_end' => $validated['event_end'],
        ]);

        AuditLog::log('admin_update_competition', "Memperbarui kompetisi {$competition->title}", 'competitions', $competition->id);

        return redirect()->route('admin.competitions.show', $competition->id)->with('success', 'Data kompetisi berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $competition = Competition::findOrFail($id);
        $title = $competition->title;
        $competition->delete();

        AuditLog::log('admin_delete_competition', "Menghapus kompetisi {$title}", 'competitions', $id);

        return redirect()->route('admin.competitions.index')->with('success', "Kompetisi {$title} berhasil dihapus.");
    }
}
