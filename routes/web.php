<?php

use Illuminate\Support\Facades\Route;

// Public Website Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InterestExplorationController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentPortalController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminCompetitionController;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\Admin\AdminAchievementController;
use App\Http\Controllers\Admin\AdminAssessmentController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\AdminSettingController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('eksplorasi')->name('exploration.')->group(function () {
    Route::get('/', [InterestExplorationController::class, 'index'])->name('index');
    Route::get('/{slug}', [InterestExplorationController::class, 'show'])->name('show');
});

Route::prefix('kompetisi')->name('competitions.')->group(function () {
    Route::get('/', [CompetitionController::class, 'index'])->name('index');
    Route::get('/{slug}', [CompetitionController::class, 'show'])->name('show');
    Route::post('/{id}/register', [CompetitionController::class, 'registerSelf'])->name('register')->middleware('throttle:competition_register');
});

Route::get('/prestasi', [AchievementController::class, 'index'])->name('achievements.index');
Route::get('/tentang', [AboutController::class, 'index'])->name('about');
Route::get('/kebijakan-privasi', [AboutController::class, 'privacy'])->name('privacy');

Route::prefix('asesmen')->name('assessment.')->group(function () {
    Route::get('/', [AssessmentController::class, 'index'])->name('index');
    Route::get('/hasil/{token}', [AssessmentController::class, 'result'])->name('result');
    Route::get('/{slug}', [AssessmentController::class, 'show'])->name('show');
    Route::post('/{slug}/submit', [AssessmentController::class, 'submit'])->name('submit')->middleware('throttle:assessment_submission');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('throttle:6,1');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Mahasiswa Private Portal
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', [StudentPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [StudentPortalController::class, 'editProfile'])->name('profile');
    Route::post('/profil', [StudentPortalController::class, 'updateProfile'])->name('profile.update');
    Route::post('/skill', [StudentPortalController::class, 'addSkill'])->name('skill.add');
    Route::delete('/skill/{id}', [StudentPortalController::class, 'deleteSkill'])->name('skill.delete');
    Route::post('/prestasi', [StudentPortalController::class, 'submitAchievement'])->name('achievement.submit');
    Route::get('/prestasi/{id}/bukti', [StudentPortalController::class, 'downloadProof'])->name('achievement.download_proof');
});

/*
|--------------------------------------------------------------------------
| Private Management System (Admin & Staff)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'staff_or_above'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Student Database & Talent Mapping
    Route::prefix('mahasiswa')->name('students.')->group(function () {
        Route::get('/', [AdminStudentController::class, 'index'])->name('index');
        Route::get('/tambah', [AdminStudentController::class, 'create'])->name('create');
        Route::post('/', [AdminStudentController::class, 'store'])->name('store');
        Route::get('/export-csv', [AdminStudentController::class, 'exportCsv'])->name('export')->middleware('ketua_or_above');
        Route::get('/{id}', [AdminStudentController::class, 'show'])->name('show');
        Route::put('/{id}', [AdminStudentController::class, 'update'])->name('update');
        Route::post('/{id}/toggle-verify', [AdminStudentController::class, 'toggleVerification'])->name('toggle_verify');
        Route::delete('/{id}', [AdminStudentController::class, 'destroy'])->name('destroy');
    });

    // Competition Operations
    Route::prefix('kompetisi')->name('competitions.')->group(function () {
        Route::get('/', [AdminCompetitionController::class, 'index'])->name('index');
        Route::get('/tambah', [AdminCompetitionController::class, 'create'])->name('create');
        Route::post('/', [AdminCompetitionController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminCompetitionController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminCompetitionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminCompetitionController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminCompetitionController::class, 'destroy'])->name('destroy');
    });

    // Registrations & Manual Staff Entry (Instagram DM / WhatsApp / Offline)
    Route::prefix('pendaftaran')->name('registrations.')->group(function () {
        Route::get('/', [AdminRegistrationController::class, 'index'])->name('index');
        Route::get('/manual', [AdminRegistrationController::class, 'create'])->name('create');
        Route::post('/manual', [AdminRegistrationController::class, 'storeManual'])->name('store');
        Route::post('/{id}/status', [AdminRegistrationController::class, 'updateStatus'])->name('update_status');
        Route::delete('/{id}', [AdminRegistrationController::class, 'destroy'])->name('destroy');
    });

    // Achievements & Verification
    Route::prefix('prestasi')->name('achievements.')->group(function () {
        Route::get('/', [AdminAchievementController::class, 'index'])->name('index');
        Route::get('/tambah', [AdminAchievementController::class, 'create'])->name('create');
        Route::post('/', [AdminAchievementController::class, 'store'])->name('store');
        Route::get('/{id}/bukti', [AdminAchievementController::class, 'downloadProof'])->name('download_proof');
        Route::post('/{id}/status', [AdminAchievementController::class, 'updateStatus'])->name('update_status');
        Route::delete('/{id}', [AdminAchievementController::class, 'destroy'])->name('destroy');
    });

    // Assessments & Question Bank
    Route::prefix('asesmen')->name('assessments.')->group(function () {
        Route::get('/', [AdminAssessmentController::class, 'index'])->name('index');
        Route::get('/tambah', [AdminAssessmentController::class, 'create'])->name('create');
        Route::post('/', [AdminAssessmentController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminAssessmentController::class, 'show'])->name('show');
        Route::post('/{id}/toggle-active', [AdminAssessmentController::class, 'toggleActive'])->name('toggle_active');
    });

    // Security Audit Logs (Ketua Divisi & Super Admin)
    Route::get('/audit-log', [AdminAuditLogController::class, 'index'])->name('audit_logs.index')->middleware('ketua_or_above');

    // User & Staff Management (Super Admin only)
    Route::prefix('pengguna')->name('users.')->middleware('super_admin')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/tambah', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::put('/{id}', [AdminUserController::class, 'update'])->name('update');
        Route::post('/{id}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('toggle_active');
    });

    // Application Settings & Scoring Weights (Super Admin only)
    Route::prefix('pengaturan')->name('settings.')->middleware('super_admin')->group(function () {
        Route::get('/', [AdminSettingController::class, 'index'])->name('index');
        Route::post('/', [AdminSettingController::class, 'update'])->name('update');
    });
});
