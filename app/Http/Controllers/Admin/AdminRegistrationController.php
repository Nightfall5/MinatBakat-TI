<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompetitionRegistration;
use App\Models\Competition;
use App\Models\StudentProfile;
use App\Models\AuditLog;

class AdminRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $source = $request->query('source');
        $competitionId = $request->query('competition_id');
        $search = $request->query('q');

        $query = CompetitionRegistration::with(['competition', 'profile', 'staffRegistrar']);

        if ($search) {
            $query->whereHas('profile', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($source && $source !== 'all') {
            $query->where('registration_source', $source);
        }

        if ($competitionId && $competitionId !== 'all') {
            $query->where('competition_id', $competitionId);
        }

        $registrations = $query->latest()->paginate(15)->withQueryString();
        $competitions = Competition::orderBy('title')->get();

        return view('admin.registrations.index', compact('registrations', 'competitions', 'status', 'source', 'competitionId', 'search'));
    }

    public function create()
    {
        $competitions = Competition::whereIn('status', ['open', 'scheduled'])->orderBy('title')->get();
        $students = StudentProfile::orderBy('full_name')->get();

        return view('admin.registrations.create', compact('competitions', 'students'));
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'student_profile_id' => 'required|exists:student_profiles,id',
            'registration_source' => 'required|in:instagram_dm,whatsapp,offline,web_self',
            'status' => 'required|in:submitted,under_review,confirmed,competing,completed,rejected,withdrawn',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $competition = Competition::findOrFail($validated['competition_id']);
        $student = StudentProfile::findOrFail($validated['student_profile_id']);

        // Prevent duplicate registration for the same competition
        $existing = CompetitionRegistration::where('competition_id', $competition->id)
            ->where('student_profile_id', $student->id)
            ->first();

        if ($existing) {
            return back()->with('error', "Mahasiswa {$student->full_name} sudah terdaftar pada kompetisi ini dengan status: " . strtoupper($existing->status));
        }

        $confirmedAt = in_array($validated['status'], ['confirmed', 'competing', 'completed']) ? now() : null;

        try {
            $reg = CompetitionRegistration::create([
                'competition_id' => $competition->id,
                'student_profile_id' => $student->id,
                'user_id' => $student->user_id,
                'registration_source' => $validated['registration_source'],
                'registered_by_staff_id' => auth()->id(),
                'status' => $validated['status'],
                'admin_notes' => $validated['admin_notes'] ? strip_tags($validated['admin_notes']) : null,
                'confirmed_at' => $confirmedAt,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', "Mahasiswa {$student->full_name} sudah terdaftar pada kompetisi ini.");
        }

        AuditLog::log(
            'admin_manual_registration',
            "Staf " . auth()->user()->name . " mendaftarkan mahasiswa {$student->full_name} ke kompetisi {$competition->title} via {$validated['registration_source']}",
            'competition_registrations',
            $reg->id
        );

        return redirect()->route('admin.registrations.index')->with('success', "Pendaftaran manual untuk {$student->full_name} berhasil dicatat.");
    }

    public function updateStatus(Request $request, int $id)
    {
        $reg = CompetitionRegistration::with(['competition', 'profile'])->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:submitted,under_review,confirmed,competing,completed,rejected,withdrawn',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $reg->status;
        $newStatus = $validated['status'];

        $allowedTransitions = [
            'submitted' => ['under_review', 'rejected', 'withdrawn', 'confirmed'],
            'under_review' => ['confirmed', 'rejected', 'withdrawn'],
            'confirmed' => ['competing', 'rejected', 'withdrawn', 'under_review'],
            'competing' => ['completed', 'withdrawn'],
            'completed' => [], // completed cannot be reopened directly
            'rejected' => ['under_review'],
            'withdrawn' => [],
        ];

        if ($oldStatus !== $newStatus && !in_array($newStatus, $allowedTransitions[$oldStatus] ?? [], true)) {
            return back()->with('error', "Transisi status dari " . strtoupper($oldStatus) . " ke " . strtoupper($newStatus) . " tidak diizinkan dalam alur kerja kompetisi.");
        }

        $confirmedAt = $reg->confirmed_at;
        if (in_array($newStatus, ['confirmed', 'competing', 'completed']) && !$confirmedAt) {
            $confirmedAt = now();
        }

        $reg->update([
            'status' => $newStatus,
            'admin_notes' => $validated['admin_notes'] ? strip_tags($validated['admin_notes']) : $reg->admin_notes,
            'confirmed_at' => $confirmedAt,
        ]);

        AuditLog::log(
            'admin_update_registration_status',
            "Mengubah status pendaftaran {$reg->profile?->full_name} pada {$reg->competition?->title} dari {$oldStatus} menjadi {$newStatus}",
            'competition_registrations',
            $reg->id
        );

        return back()->with('success', "Status pendaftaran berhasil diubah menjadi " . strtoupper($newStatus));
    }

    public function destroy(int $id)
    {
        $reg = CompetitionRegistration::findOrFail($id);
        $studentName = $reg->profile?->full_name;
        $compTitle = $reg->competition?->title;
        $reg->delete();

        AuditLog::log('admin_delete_registration', "Menghapus pendaftaran {$studentName} pada {$compTitle}", 'competition_registrations', $id);

        return back()->with('success', "Pendaftaran peserta berhasil dihapus.");
    }
}
