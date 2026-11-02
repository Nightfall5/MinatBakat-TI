<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AchievementRecord;
use App\Models\ParticipationRecord;
use App\Models\Competition;
use App\Models\StudentProfile;
use App\Models\AuditLog;

class AdminAchievementController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $level = $request->query('level');
        $search = $request->query('q');

        $query = AchievementRecord::with(['profile', 'competition', 'verifiedBy']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('achievement_title', 'like', "%{$search}%")
                  ->orWhereHas('profile', fn($pq) => $pq->where('full_name', 'like', "%{$search}%"))
                  ->orWhereHas('competition', fn($cq) => $cq->where('title', 'like', "%{$search}%"));
            });
        }

        if ($status && $status !== 'all') {
            $query->where('verification_status', $status);
        }

        if ($level && $level !== 'all') {
            $query->where('level', $level);
        }

        $achievements = $query->latest()->paginate(15)->withQueryString();
        $competitions = Competition::orderBy('title')->get();
        $students = StudentProfile::orderBy('full_name')->get();

        return view('admin.achievements.index', compact('achievements', 'competitions', 'students', 'status', 'level', 'search'));
    }

    public function create()
    {
        $competitions = Competition::orderBy('title')->get();
        $students = StudentProfile::orderBy('full_name')->get();

        return view('admin.achievements.create', compact('competitions', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_profile_id' => 'required|exists:student_profiles,id',
            'competition_id' => 'required|exists:competitions,id',
            'achievement_title' => 'required|string|max:200',
            'rank_position' => 'required|integer|min:1|max:100',
            'level' => 'required|in:internal_kampus,regional,nasional,internasional',
            'verification_status' => 'required|in:pending,verified,rejected',
            'official_publication_url' => 'nullable|url|max:255',
            'is_public_published' => 'nullable|boolean',
            'proof_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $filePath = null;
        if ($request->hasFile('proof_document')) {
            $file = $request->file('proof_document');
            $ext = in_array(strtolower($file->extension()), ['pdf', 'jpg', 'jpeg', 'png'], true) ? $file->extension() : 'pdf';
            $fileName = 'proof_' . date('Ymd_His') . '_' . Str::random(24) . '.' . $ext;
            $filePath = $file->storeAs('private_proofs', $fileName, 'local');
        }

        $student = StudentProfile::findOrFail($validated['student_profile_id']);
        $competition = Competition::findOrFail($validated['competition_id']);

        $verifiedAt = $validated['verification_status'] === 'verified' ? now() : null;
        $verifiedById = $validated['verification_status'] === 'verified' ? auth()->id() : null;

        // Ensure student has consented before public publishing
        $isPublic = $request->boolean('is_public_published') && $student->consent_achievement_public;

        $achievement = AchievementRecord::create([
            'student_profile_id' => $student->id,
            'competition_id' => $competition->id,
            'achievement_title' => strip_tags($validated['achievement_title']),
            'rank_position' => $validated['rank_position'],
            'level' => $validated['level'],
            'verification_status' => $validated['verification_status'],
            'verified_by_id' => $verifiedById,
            'verified_at' => $verifiedAt,
            'proof_document_path' => $filePath,
            'official_publication_url' => $validated['official_publication_url'],
            'is_public_published' => $isPublic,
            'notes' => $validated['notes'] ? strip_tags($validated['notes']) : null,
        ]);

        // Also record participation if not already exists
        ParticipationRecord::firstOrCreate(
            ['student_profile_id' => $student->id, 'competition_id' => $competition->id],
            ['participation_date' => now(), 'role_description' => "Pemenang {$achievement->achievement_title}"]
        );

        AuditLog::log('admin_record_achievement', "Mencatat prestasi {$achievement->achievement_title} untuk {$student->full_name}", 'achievement_records', $achievement->id);

        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi mahasiswa berhasil dicatat.');
    }

    public function updateStatus(Request $request, int $id)
    {
        $achievement = AchievementRecord::with('profile')->findOrFail($id);

        $validated = $request->validate([
            'verification_status' => 'required|in:pending,verified,rejected',
            'is_public_published' => 'nullable|boolean',
        ]);

        $verifiedAt = $validated['verification_status'] === 'verified' ? now() : null;
        $verifiedById = $validated['verification_status'] === 'verified' ? auth()->id() : null;

        $isPublic = $request->boolean('is_public_published');
        // If publishing, ensure student consented
        if ($isPublic && !$achievement->profile?->consent_achievement_public) {
            $isPublic = false;
        }

        $achievement->update([
            'verification_status' => $validated['verification_status'],
            'verified_by_id' => $verifiedById,
            'verified_at' => $verifiedAt,
            'is_public_published' => $isPublic,
        ]);

        AuditLog::log('admin_verify_achievement', "Mengubah status verifikasi prestasi #{$id} menjadi {$validated['verification_status']}", 'achievement_records', $id);

        return back()->with('success', 'Status prestasi berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $achievement = AchievementRecord::findOrFail($id);
        $title = $achievement->achievement_title;
        $achievement->delete();

        AuditLog::log('admin_delete_achievement', "Menghapus catatan prestasi {$title}", 'achievement_records', $id);

        return back()->with('success', "Catatan prestasi berhasil dihapus.");
    }

    public function downloadProof(int $id)
    {
        $achievement = AchievementRecord::with('profile')->findOrFail($id);

        if (!$achievement->proof_document_path) {
            abort(404, 'Dokumen bukti tidak ditemukan.');
        }

        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($achievement->proof_document_path)) {
            abort(404, 'Berkas fisik bukti tidak ditemukan pada media penyimpanan privat.');
        }

        AuditLog::log('admin_download_achievement_proof', "Pengurus mengunduh dokumen bukti prestasi #{$id} ({$achievement->achievement_title})", 'achievement_records', $id);

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
