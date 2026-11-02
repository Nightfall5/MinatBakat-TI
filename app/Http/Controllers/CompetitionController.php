<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competition;
use App\Models\InterestCategory;
use App\Models\CompetitionRegistration;
use App\Models\StudentProfile;
use App\Models\AuditLog;

class CompetitionController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('kategori');
        $level = $request->query('tingkat');
        $status = $request->query('status', 'open');
        $format = $request->query('format');
        $teamType = $request->query('tipe');
        $search = $request->query('q');

        $query = Competition::with('category');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categorySlug) {
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($level && $level !== 'all') {
            $query->where('level', $level);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($format && $format !== 'all') {
            $query->where('format', $format);
        }

        if ($teamType && $teamType !== 'all') {
            $query->where('team_type', $teamType);
        }

        $competitions = $query->orderBy('registration_deadline')->paginate(9)->withQueryString();
        $categories = InterestCategory::where('is_active', true)->orderBy('name')->get();

        return view('public.competitions.index', compact('competitions', 'categories', 'categorySlug', 'level', 'status', 'format', 'teamType', 'search'));
    }

    public function show(string $slug)
    {
        $competition = Competition::with('category')->where('slug', $slug)->firstOrFail();

        // Check if current authenticated student has already registered
        $userRegistration = null;
        if (auth()->check()) {
            $profile = auth()->user()->studentProfile;
            if ($profile) {
                $userRegistration = CompetitionRegistration::where('competition_id', $competition->id)
                    ->where('student_profile_id', $profile->id)
                    ->first();
            }
        }

        $relatedCompetitions = Competition::where('interest_category_id', $competition->interest_category_id)
            ->where('id', '!=', $competition->id)
            ->where('status', 'open')
            ->limit(3)
            ->get();

        return view('public.competitions.show', compact('competition', 'userRegistration', 'relatedCompetitions'));
    }

    public function registerSelf(Request $request, int $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk dengan akun mahasiswa untuk mengajukan pendaftaran lomba.');
        }

        $user = auth()->user();
        $competition = Competition::findOrFail($id);

        if ($competition->status !== 'open') {
            return back()->with('error', 'Pendaftaran untuk kompetisi ini sudah tidak dibuka.');
        }

        if ($competition->registration_deadline && $competition->registration_deadline->isPast()) {
            return back()->with('error', 'Batas waktu pendaftaran kompetisi ini telah berakhir.');
        }

        // Retrieve or create student profile for user
        $profile = $user->studentProfile;
        if (!$profile) {
            $profile = StudentProfile::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'consent_data_processing' => true,
            ]);
        }

        // Prevent duplicate registration
        $existing = CompetitionRegistration::where('competition_id', $competition->id)
            ->where('student_profile_id', $profile->id)
            ->first();

        if ($existing) {
            return back()->with('warning', "Anda sudah pernah mendaftar pada kompetisi ini dengan status: " . strtoupper($existing->status));
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $reg = CompetitionRegistration::create([
                'competition_id' => $competition->id,
                'student_profile_id' => $profile->id,
                'user_id' => $user->id,
                'registration_source' => 'web_self',
                'status' => 'submitted',
                'admin_notes' => $validated['notes'] ? "Catatan mahasiswa: " . e($validated['notes']) : null,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('warning', 'Anda sudah terdaftar pada kompetisi ini.');
        }

        AuditLog::log('student_register_competition', "Mahasiswa {$user->name} mendaftar mandiri pada lomba {$competition->title}", 'competition_registrations', $reg->id);

        return back()->with('success', 'Permintaan pendaftaran berhasil dikirim! Pengurus Divisi Minat dan Bakat akan meninjau berkas Anda.');
    }
}
