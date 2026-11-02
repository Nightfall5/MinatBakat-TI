<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\StudentProfile;
use App\Models\InterestCategory;
use App\Models\AuditLog;

class AdminStudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');
        $angkatan = $request->query('angkatan');
        $categorySlug = $request->query('kategori');
        $verified = $request->query('verified');

        $query = StudentProfile::with(['interests.category', 'skills']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($angkatan && $angkatan !== 'all') {
            $query->where('angkatan', $angkatan);
        }

        if ($categorySlug && $categorySlug !== 'all') {
            $query->whereHas('interests.category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($verified !== null && $verified !== 'all') {
            $query->where('is_verified', $verified === '1');
        }

        $students = $query->latest()->paginate(15)->withQueryString();
        $categories = InterestCategory::where('is_active', true)->orderBy('name')->get();
        $angkatanList = StudentProfile::whereNotNull('angkatan')->distinct()->pluck('angkatan')->sortDesc();

        return view('admin.students.index', compact('students', 'categories', 'angkatanList', 'search', 'angkatan', 'categorySlug', 'verified'));
    }

    public function show(int $id)
    {
        $student = StudentProfile::with([
            'user',
            'skills',
            'interests.category',
            'registrations.competition',
            'participations.competition',
            'achievements.competition',
            'surveyResponses.result',
            'surveyResponses.campaign',
        ])->findOrFail($id);

        $categories = InterestCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.students.show', compact('student', 'categories'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'nim' => 'nullable|string|max:30|unique:student_profiles,nim',
            'angkatan' => 'required|string|max:10',
            'email' => 'required|email|max:150|unique:student_profiles,email',
            'phone' => 'nullable|string|max:25',
            'instagram_handle' => 'nullable|string|max:50',
            'portfolio_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'bio' => 'nullable|string|max:1000',
            'is_verified' => 'nullable|boolean',
        ]);

        $student = StudentProfile::create([
            'full_name' => strip_tags($validated['full_name']),
            'nim' => $validated['nim'] ? strip_tags($validated['nim']) : null,
            'angkatan' => $validated['angkatan'],
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ? strip_tags($validated['phone']) : null,
            'instagram_handle' => $validated['instagram_handle'] ? strip_tags($validated['instagram_handle']) : null,
            'portfolio_url' => $validated['portfolio_url'],
            'github_url' => $validated['github_url'],
            'bio' => $validated['bio'] ? strip_tags($validated['bio']) : null,
            'is_verified' => $request->boolean('is_verified'),
            'consent_data_processing' => true,
            'created_by_admin_id' => auth()->id(),
        ]);

        AuditLog::log('admin_create_student', "Staf menambahkan mahasiswa {$student->full_name}", 'student_profiles', $student->id);

        return redirect()->route('admin.students.show', $student->id)->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $student = StudentProfile::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'nim' => 'nullable|string|max:30|unique:student_profiles,nim,' . $student->id,
            'angkatan' => 'required|string|max:10',
            'email' => 'required|email|max:150|unique:student_profiles,email,' . $student->id,
            'phone' => 'nullable|string|max:25',
            'instagram_handle' => 'nullable|string|max:50',
            'portfolio_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'bio' => 'nullable|string|max:1000',
            'is_verified' => 'nullable|boolean',
            'consent_achievement_public' => 'nullable|boolean',
        ]);

        $student->update([
            'full_name' => strip_tags($validated['full_name']),
            'nim' => $validated['nim'] ? strip_tags($validated['nim']) : null,
            'angkatan' => $validated['angkatan'],
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ? strip_tags($validated['phone']) : null,
            'instagram_handle' => $validated['instagram_handle'] ? strip_tags($validated['instagram_handle']) : null,
            'portfolio_url' => $validated['portfolio_url'],
            'github_url' => $validated['github_url'],
            'bio' => $validated['bio'] ? strip_tags($validated['bio']) : null,
            'is_verified' => $request->boolean('is_verified'),
            'consent_achievement_public' => $request->boolean('consent_achievement_public'),
        ]);

        AuditLog::log('admin_update_student', "Staf memperbarui data mahasiswa {$student->full_name}", 'student_profiles', $student->id);

        return back()->with('success', 'Data profil mahasiswa berhasil diperbarui.');
    }

    public function toggleVerification(int $id)
    {
        $student = StudentProfile::findOrFail($id);
        $student->is_verified = !$student->is_verified;
        $student->save();

        AuditLog::log('admin_toggle_verify_student', "Status verifikasi {$student->full_name} diubah menjadi: " . ($student->is_verified ? 'Terverifikasi' : 'Belum Diverifikasi'), 'student_profiles', $student->id);

        return back()->with('success', "Status verifikasi berhasil diubah.");
    }

    public function destroy(int $id)
    {
        $student = StudentProfile::findOrFail($id);
        $name = $student->full_name;
        $student->delete();

        AuditLog::log('admin_delete_student', "Menghapus data mahasiswa {$name}", 'student_profiles', $id);

        return redirect()->route('admin.students.index')->with('success', "Data mahasiswa {$name} berhasil dihapus.");
    }

    /**
     * Authorized CSV Export restricted to Ketua Divisi or Super Admin
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $user = auth()->user();
        if (!$user->isKetuaOrAbove()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk mengekspor data anggota.');
        }

        $query = StudentProfile::with(['interests.category', 'skills']);

        if ($request->filled('angkatan') && $request->angkatan !== 'all') {
            $query->where('angkatan', $request->angkatan);
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->whereHas('interests.category', fn($q) => $q->where('slug', $request->kategori));
        }

        $students = $query->get();

        AuditLog::log('admin_export_students_csv', "Mengekspor data {$students->count()} mahasiswa ke file CSV.", 'student_profiles', null, [
            'angkatan_filter' => $request->angkatan,
            'kategori_filter' => $request->kategori,
        ]);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data_mahasiswa_minbak_' . date('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function() use ($students) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['ID', 'Nama Lengkap', 'NIM', 'Angkatan', 'Email', 'No. WhatsApp', 'Instagram', 'Status Verifikasi', 'Minat Utama', 'Keahlian Terdata']);

            foreach ($students as $s) {
                $interests = $s->interests->map(fn($i) => $i->category?->name)->filter()->implode(', ');
                $skills = $s->skills->pluck('skill_name')->implode(', ');

                fputcsv($handle, [
                    $s->id,
                    $this->sanitizeCsvField($s->full_name),
                    $this->sanitizeCsvField($s->nim ?: '-'),
                    $this->sanitizeCsvField($s->angkatan ?: '-'),
                    $this->sanitizeCsvField($s->email),
                    $this->sanitizeCsvField($s->phone ?: '-'),
                    $this->sanitizeCsvField($s->instagram_handle ?: '-'),
                    $s->is_verified ? 'Terverifikasi' : 'Belum Diverifikasi',
                    $this->sanitizeCsvField($interests ?: '-'),
                    $this->sanitizeCsvField($skills ?: '-'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Prevent CSV Formula Injection by prepending an apostrophe if field starts with formula trigger characters.
     */
    public function sanitizeCsvField(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $triggers = ['=', '+', '-', '@', "\t", "\r", '%'];
        if (in_array($value[0], $triggers, true)) {
            return "'" . $value;
        }

        return $value;
    }
}
