<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentProfile;
use App\Models\StudentSkill;
use App\Models\CompetitionRegistration;
use App\Models\Competition;
use App\Models\AchievementRecord;
use App\Models\SurveyResponse;
use App\Models\AuditLog;

class StudentPortalController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
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

        $skills = $profile->skills;
        $registrations = CompetitionRegistration::with('competition.category')
            ->where('student_profile_id', $profile->id)
            ->latest()
            ->get();

        $achievements = AchievementRecord::with('competition')
            ->where('student_profile_id', $profile->id)
            ->latest()
            ->get();

        $assessmentResponses = SurveyResponse::with(['campaign', 'result.primaryCategory'])
            ->where(function($q) use ($user, $profile) {
                $q->where('user_id', $user->id)
                  ->orWhere('student_profile_id', $profile->id)
                  ->orWhere('respondent_email', $user->email);
            })
            ->latest()
            ->get();

        $openCompetitions = Competition::with('category')
            ->where('status', 'open')
            ->whereNotIn('id', $registrations->pluck('competition_id'))
            ->limit(3)
            ->get();

        return view('portal.dashboard', compact('user', 'profile', 'skills', 'registrations', 'achievements', 'assessmentResponses', 'openCompetitions'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        $profile = $user->studentProfile;

        return view('portal.profile', compact('user', 'profile'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $profile = $user->studentProfile;

        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'nim' => 'nullable|string|max:30',
            'angkatan' => 'required|string|max:10',
            'phone' => 'nullable|string|max:25',
            'instagram_handle' => 'nullable|string|max:50',
            'github_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'bio' => 'nullable|string|max:1000',
            'consent_achievement_public' => 'nullable|boolean',
        ]);

        $user->update([
            'name' => strip_tags($validated['full_name']),
            'phone' => $validated['phone'] ? strip_tags($validated['phone']) : null,
        ]);

        $profile->update([
            'full_name' => strip_tags($validated['full_name']),
            'nim' => $validated['nim'] ? strip_tags($validated['nim']) : null,
            'angkatan' => $validated['angkatan'],
            'phone' => $validated['phone'] ? strip_tags($validated['phone']) : null,
            'instagram_handle' => $validated['instagram_handle'] ? strip_tags($validated['instagram_handle']) : null,
            'github_url' => $validated['github_url'],
            'portfolio_url' => $validated['portfolio_url'],
            'bio' => $validated['bio'] ? strip_tags($validated['bio']) : null,
            'consent_achievement_public' => $request->boolean('consent_achievement_public'),
        ]);

        AuditLog::log('student_update_profile', "Mahasiswa {$user->name} memperbarui data profil pribadinya.", 'student_profiles', $profile->id);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function addSkill(Request $request)
    {
        $profile = auth()->user()->studentProfile;
        if (!$profile) abort(404);

        $validated = $request->validate([
            'skill_name' => 'required|string|max:100',
            'proficiency_level' => 'required|in:beginner,intermediate,advanced',
            'tools' => 'nullable|string|max:255',
        ]);

        StudentSkill::create([
            'student_profile_id' => $profile->id,
            'skill_name' => strip_tags($validated['skill_name']),
            'proficiency_level' => $validated['proficiency_level'],
            'tools' => $validated['tools'] ? strip_tags($validated['tools']) : null,
        ]);

        return back()->with('success', 'Keahlian baru berhasil ditambahkan.');
    }

    public function deleteSkill(int $id)
    {
        $profile = auth()->user()->studentProfile;
        $skill = StudentSkill::where('student_profile_id', $profile->id)->findOrFail($id);
        $skill->delete();

        return back()->with('success', 'Keahlian berhasil dihapus.');
    }

    public function submitAchievement(Request $request)
    {
        $profile = auth()->user()->studentProfile;
        if (!$profile) abort(404);

        $validated = $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'achievement_title' => 'required|string|max:200',
            'rank_position' => 'required|integer|min:1|max:100',
            'level' => 'required|in:internal_kampus,regional,nasional,internasional',
            'official_publication_url' => 'nullable|url|max:255',
            'proof_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
            'notes' => 'nullable|string|max:1000',
        ]);

        $filePath = null;
        if ($request->hasFile('proof_document')) {
            $file = $request->file('proof_document');
            $ext = in_array(strtolower($file->extension()), ['pdf', 'jpg', 'jpeg', 'png'], true) ? $file->extension() : 'pdf';
            $fileName = 'proof_' . date('Ymd_His') . '_' . \Illuminate\Support\Str::random(24) . '.' . $ext;
            $filePath = $file->storeAs('private_proofs', $fileName, 'local');
        }

        $record = AchievementRecord::create([
            'student_profile_id' => $profile->id,
            'competition_id' => $validated['competition_id'],
            'achievement_title' => strip_tags($validated['achievement_title']),
            'rank_position' => $validated['rank_position'],
            'level' => $validated['level'],
            'verification_status' => 'pending',
            'proof_document_path' => $filePath,
            'official_publication_url' => $validated['official_publication_url'],
            'is_public_published' => false,
            'notes' => $validated['notes'] ? strip_tags($validated['notes']) : null,
        ]);

        AuditLog::log('student_submit_achievement', "Mahasiswa mengajukan klaim prestasi {$record->achievement_title} untuk verifikasi.", 'achievement_records', $record->id);

        return back()->with('success', 'Pengajuan prestasi berhasil dikirim! Pengurus divisi akan melakukan verifikasi.');
    }

    public function downloadProof(int $id)
    {
        $profile = auth()->user()->studentProfile;
        if (!$profile) {
            abort(403, 'Profil mahasiswa tidak ditemukan.');
        }

        $achievement = AchievementRecord::findOrFail($id);

        // Strictly verify student ownership to prevent IDOR
        if ($achievement->student_profile_id !== $profile->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh dokumen prestasi ini.');
        }

        if (!$achievement->proof_document_path || !\Illuminate\Support\Facades\Storage::disk('local')->exists($achievement->proof_document_path)) {
            abort(404, 'Dokumen bukti tidak ditemukan.');
        }

        $safeName = 'Bukti_Prestasi_' . \Illuminate\Support\Str::slug($achievement->achievement_title) . '.' . pathinfo($achievement->proof_document_path, PATHINFO_EXTENSION);

        return \Illuminate\Support\Facades\Storage::disk('local')->download(
            $achievement->proof_document_path,
            $safeName,
            [
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
            ]
        );
    }
}
