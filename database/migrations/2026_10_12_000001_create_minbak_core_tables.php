<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add roles and status to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('mahasiswa')->after('password'); // super_admin, ketua_divisi, staff, mahasiswa
            $table->boolean('is_active')->default(true)->after('role');
            $table->string('avatar')->nullable()->after('is_active');
            $table->string('phone')->nullable()->after('avatar');
        });

        // 1. Interest Categories
        Schema::create('interest_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->default('code');
            $table->text('common_skills')->nullable(); // JSON or comma-separated
            $table->text('starter_projects')->nullable(); // JSON or text
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Student Profiles
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('nim')->nullable()->index();
            $table->string('angkatan', 10)->nullable();
            $table->string('email')->index();
            $table->string('phone')->nullable();
            $table->string('instagram_handle')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('github_url')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('consent_data_processing')->default(true);
            $table->boolean('consent_achievement_public')->default(false);
            $table->foreignId('created_by_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Student Skills
        Schema::create('student_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->string('skill_name');
            $table->string('proficiency_level')->default('beginner'); // beginner, intermediate, advanced
            $table->string('tools')->nullable();
            $table->timestamps();
        });

        // 4. Student Interests
        Schema::create('student_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('interest_category_id')->constrained()->cascadeOnDelete();
            $table->integer('interest_level')->default(3); // 1-5
            $table->integer('experience_level')->default(1); // 1-5
            $table->timestamps();
            $table->unique(['student_profile_id', 'interest_category_id']);
        });

        // 5. Competitions
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('organizer');
            $table->text('description');
            $table->foreignId('interest_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('level')->default('nasional'); // internal_kampus, regional, nasional, internasional
            $table->string('format')->default('online'); // online, offline, hybrid
            $table->string('location')->nullable();
            $table->string('team_type')->default('individual'); // individual, team
            $table->integer('min_team_members')->default(1);
            $table->integer('max_team_members')->default(1);
            $table->decimal('fee_amount', 12, 2)->default(0);
            $table->text('prize_summary')->nullable();
            $table->string('official_url')->nullable();
            $table->string('guidebook_url')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('status')->default('open'); // draft, scheduled, open, closed, ongoing, completed, archived
            $table->dateTime('registration_start')->nullable();
            $table->dateTime('registration_deadline')->nullable();
            $table->dateTime('event_start')->nullable();
            $table->dateTime('event_end')->nullable();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 6. Competition Teams
        Schema::create('competition_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('team_name');
            $table->foreignId('leader_student_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('competition_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('competition_teams')->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->string('role_in_team')->nullable();
            $table->timestamps();
            $table->unique(['team_id', 'student_profile_id']);
        });

        // 7. Competition Registrations
        Schema::create('competition_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained('competition_teams')->nullOnDelete();
            $table->string('registration_source')->default('web_self'); // web_self, instagram_dm, offline, whatsapp
            $table->foreignId('registered_by_staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('submitted'); // submitted, under_review, confirmed, rejected, competing, completed, withdrawn
            $table->text('admin_notes')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->timestamps();
            $table->unique(['competition_id', 'student_profile_id']);
        });

        // 8. Participation Records
        Schema::create('participation_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('registration_id')->nullable()->constrained('competition_registrations')->nullOnDelete();
            $table->date('participation_date')->nullable();
            $table->string('role_description')->nullable();
            $table->string('certificate_path')->nullable();
            $table->timestamps();
        });

        // 9. Achievement Records
        Schema::create('achievement_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->nullable()->constrained('competition_teams')->nullOnDelete();
            $table->string('achievement_title'); // Juara 1, Best UI/UX, dsb
            $table->integer('rank_position')->default(1);
            $table->string('level')->default('nasional');
            $table->string('verification_status')->default('pending'); // pending, verified, rejected
            $table->foreignId('verified_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('verified_at')->nullable();
            $table->string('proof_document_path')->nullable();
            $table->string('official_publication_url')->nullable();
            $table->boolean('is_public_published')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 10. Survey Campaigns
        Schema::create('survey_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('academic_year')->default('2026/2027');
            $table->boolean('is_active')->default(true);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('require_login')->default(false);
            $table->string('qr_code_token')->unique();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 11. Survey Questions
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('survey_campaigns')->cascadeOnDelete();
            $table->text('question_text');
            $table->string('question_type')->default('single_choice'); // single_choice, multiple_choice, scale, branch_selector
            $table->integer('step_number')->default(2); // 1: profile, 2: general, 3: specific branch
            $table->string('category_hint')->nullable();
            $table->string('branch_key')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        // 12. Survey Options
        Schema::create('survey_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('survey_questions')->cascadeOnDelete();
            $table->text('option_text');
            $table->text('category_weights')->nullable(); // JSON: {"frontend": 10, "ui_ux": 8}
            $table->string('target_branch')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        // 13. Survey Responses
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('survey_campaigns')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('student_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('respondent_name');
            $table->string('respondent_nim')->nullable();
            $table->string('respondent_angkatan')->nullable();
            $table->string('respondent_email');
            $table->string('access_token')->unique(); // Secure token to view results without login
            $table->boolean('consent_agreed')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->string('ip_hash')->nullable();
            $table->timestamps();
        });

        // 14. Survey Answers
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('survey_responses')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('survey_questions')->cascadeOnDelete();
            $table->foreignId('selected_option_id')->nullable()->constrained('survey_options')->nullOnDelete();
            $table->text('answer_text')->nullable();
            $table->timestamps();
        });

        // 15. Assessment Results
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('survey_responses')->cascadeOnDelete();
            $table->foreignId('student_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('primary_category_id')->nullable()->constrained('interest_categories')->nullOnDelete();
            $table->foreignId('secondary_category_id')->nullable()->constrained('interest_categories')->nullOnDelete();
            $table->foreignId('tertiary_category_id')->nullable()->constrained('interest_categories')->nullOnDelete();
            $table->text('top_recommendations')->nullable(); // JSON list with reason & score
            $table->text('dimension_scores')->nullable(); // JSON: interest, activity_fit, experience, learning_orientation
            $table->text('normalized_scores')->nullable(); // JSON
            $table->text('scoring_config_snapshot')->nullable(); // JSON
            $table->timestamps();
        });

        // 16. Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // login, update_member, register_competition, export_csv, verify_achievement
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->text('description');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('metadata')->nullable(); // JSON
            $table->timestamp('created_at')->useCurrent();
        });

        // 17. Application Settings
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('assessment_results');
        Schema::dropIfExists('survey_answers');
        Schema::dropIfExists('survey_responses');
        Schema::dropIfExists('survey_options');
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('survey_campaigns');
        Schema::dropIfExists('achievement_records');
        Schema::dropIfExists('participation_records');
        Schema::dropIfExists('competition_registrations');
        Schema::dropIfExists('competition_team_members');
        Schema::dropIfExists('competition_teams');
        Schema::dropIfExists('competitions');
        Schema::dropIfExists('student_interests');
        Schema::dropIfExists('student_skills');
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('interest_categories');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'avatar', 'phone']);
        });
    }
};
