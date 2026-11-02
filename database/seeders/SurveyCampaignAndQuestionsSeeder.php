<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\SurveyCampaign;
use App\Models\SurveyQuestion;
use App\Models\SurveyOption;
use App\Models\User;

class SurveyCampaignAndQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->orWhere('role', 'staff')->first();

        $campaign = SurveyCampaign::updateOrCreate(
            ['slug' => 'pemetaan-minat-bakat-ti-2026'],
            [
                'title' => 'Pemetaan Minat dan Bakat Mahasiswa Teknik Informatika 2026',
                'description' => 'Asesmen komprehensif Divisi Minat dan Bakat HMIF untuk memetakan aspirasi skill, preferensi aktivitas kolaboratif, kesiapan kompetisi, serta rekomendasi jalur eksplorasi talenta.',
                'academic_year' => '2026/2027',
                'is_active' => true,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(60),
                'require_login' => false,
                'qr_code_token' => 'CAMP-MINBAK-TI-2026-LIVE',
                'created_by_id' => $admin?->id,
            ]
        );

        // Delete existing questions to re-seed cleanly
        SurveyQuestion::where('campaign_id', $campaign->id)->delete();

        // ------------------ STEP 1: PENGALAMAN & LATAR BELAKANG ------------------
        $q1 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => 'Berapa lama pengalamanmu dalam mempelajari pemrograman, desain digital, riset, atau olahraga?',
            'question_type' => 'single_choice',
            'step_number' => 1,
            'category_hint' => 'experience_level',
            'order_index' => 1,
        ]);
        SurveyOption::create(['question_id' => $q1->id, 'option_text' => 'Baru memulai (pemula murni di perkuliahan)', 'category_weights' => json_encode(['_experience' => 1]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $q1->id, 'option_text' => 'Kurang dari 1 tahun (sudah pernah membuat proyek kecil/latihan mandiri)', 'category_weights' => json_encode(['_experience' => 2]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $q1->id, 'option_text' => '1 – 2 tahun (pernah mengerjakan tugas besar, mini project, atau freelance)', 'category_weights' => json_encode(['_experience' => 3]), 'order_index' => 3]);
        SurveyOption::create(['question_id' => $q1->id, 'option_text' => 'Lebih dari 2 tahun (aktif berkompetisi, berkarya rutin, atau berkontribusi proyek tim)', 'category_weights' => json_encode(['_experience' => 4]), 'order_index' => 4]);

        $q2 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => 'Apakah kamu pernah mengikuti kompetisi (lomba teknologi, riset ilmiah, desain, atau turnamen olahraga)?',
            'question_type' => 'single_choice',
            'step_number' => 1,
            'category_hint' => 'competition_experience',
            'order_index' => 2,
        ]);
        SurveyOption::create(['question_id' => $q2->id, 'option_text' => 'Belum pernah, tetapi sangat tertarik untuk mencoba', 'category_weights' => json_encode(['_interest' => 4, '_experience' => 1]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $q2->id, 'option_text' => 'Pernah mengikuti kompetisi tingkat internal kampus / regional', 'category_weights' => json_encode(['_interest' => 4, '_experience' => 3]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $q2->id, 'option_text' => 'Pernah berpartisipasi dan meraih penghargaan di tingkat nasional / internasional', 'category_weights' => json_encode(['_interest' => 5, '_experience' => 5]), 'order_index' => 3]);
        SurveyOption::create(['question_id' => $q2->id, 'option_text' => 'Belum yakin, ingin fokus belajar skill terlebih dahulu', 'category_weights' => json_encode(['_interest' => 2, '_experience' => 1]), 'order_index' => 4]);

        // ------------------ STEP 2: MINAT UMUM & PREFERENSI AKTIVITAS ------------------
        $q3 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => 'Aktivitas eksplorasi mana yang secara spontan paling membuatmu antusias dan bersemangat?',
            'question_type' => 'branch_selector',
            'step_number' => 2,
            'category_hint' => 'core_interest',
            'order_index' => 3,
        ]);
        SurveyOption::create(['question_id' => $q3->id, 'option_text' => 'Membangun antarmuka web yang interaktif, estetik, dan responsif (Frontend & UI)', 'category_weights' => json_encode(['frontend-development' => 20, 'ui-ux-design' => 12]), 'target_branch' => 'frontend_branch', 'order_index' => 1]);
        SurveyOption::create(['question_id' => $q3->id, 'option_text' => 'Merancang logika server, database, arsitektur API, dan performa sistem (Backend & SE)', 'category_weights' => json_encode(['backend-development' => 20, 'software-engineering' => 14, 'cloud-devops' => 10]), 'target_branch' => 'backend_branch', 'order_index' => 2]);
        SurveyOption::create(['question_id' => $q3->id, 'option_text' => 'Membuat aplikasi smartphone (Android/iOS) yang mudah dipakai kapan saja (Mobile Dev)', 'category_weights' => json_encode(['mobile-app-development' => 20, 'frontend-development' => 8]), 'target_branch' => 'mobile_branch', 'order_index' => 3]);
        SurveyOption::create(['question_id' => $q3->id, 'option_text' => 'Mengolah dataset besar, melatih model Machine Learning, dan bereksperimen dengan AI (AI & Data Science)', 'category_weights' => json_encode(['ai-ml' => 20, 'data-science' => 18]), 'target_branch' => 'ai_data_branch', 'order_index' => 4]);
        SurveyOption::create(['question_id' => $q3->id, 'option_text' => 'Menganalisis celah keamanan, teknik pengujian penetrasi etis, dan tantangan CTF (Cybersecurity)', 'category_weights' => json_encode(['cybersecurity' => 22, 'software-engineering' => 8]), 'target_branch' => 'cyber_branch', 'order_index' => 5]);
        SurveyOption::create(['question_id' => $q3->id, 'option_text' => 'Riset kebutuhan pengguna, wireframing, prototipe Figma, dan identitas visual (UI/UX & Desain Grafis)', 'category_weights' => json_encode(['ui-ux-design' => 20, 'graphic-design' => 18]), 'target_branch' => 'design_branch', 'order_index' => 6]);
        SurveyOption::create(['question_id' => $q3->id, 'option_text' => 'Merancang gameplay seru, mekanika karakter, dan dunia interaktif (Game Development)', 'category_weights' => json_encode(['game-development' => 22, 'software-engineering' => 10]), 'target_branch' => 'game_branch', 'order_index' => 7]);
        SurveyOption::create(['question_id' => $q3->id, 'option_text' => 'Menulis gagasan inovatif teknologi, telaah riset kritis, dan penyusunan proposal PKM (Esai & Ilmiah)', 'category_weights' => json_encode(['penulisan-esai-karya-ilmiah' => 22]), 'target_branch' => 'essay_branch', 'order_index' => 8]);
        SurveyOption::create(['question_id' => $q3->id, 'option_text' => 'Berkompetisi olahraga, melatih fisik, dan soliditas tim lapangan (Futsal / Voli)', 'category_weights' => json_encode(['olahraga-futsal' => 18, 'olahraga-voli' => 18]), 'target_branch' => 'sports_branch', 'order_index' => 9]);
        SurveyOption::create(['question_id' => $q3->id, 'option_text' => 'Belum tahu pasti / Ingin mencoba bidang baru yang direkomendasikan sistem', 'category_weights' => json_encode(['frontend-development' => 8, 'ui-ux-design' => 8, 'backend-development' => 8]), 'target_branch' => 'explore_branch', 'order_index' => 10]);

        $q4 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => 'Bagaimana gaya kerja dan dinamika kolaborasi yang paling nyaman untukmu?',
            'question_type' => 'single_choice',
            'step_number' => 2,
            'category_hint' => 'work_style',
            'order_index' => 4,
        ]);
        SurveyOption::create(['question_id' => $q4->id, 'option_text' => 'Bekerja dalam tim multidisiplin dengan pembagian peran yang terstruktur', 'category_weights' => json_encode(['_activity_team' => 5, 'software-engineering' => 5, 'full-stack-development' => 5, 'olahraga-futsal' => 5]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $q4->id, 'option_text' => 'Bekerja berpasangan (peer-programming atau duet desain/karya)', 'category_weights' => json_encode(['_activity_duo' => 5, 'ui-ux-design' => 5, 'penulisan-esai-karya-ilmiah' => 5]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $q4->id, 'option_text' => 'Bekerja mandiri secara fokus mendalam pada problem analitik/teknis', 'category_weights' => json_encode(['_activity_solo' => 5, 'cybersecurity' => 5, 'data-science' => 5]), 'order_index' => 3]);
        SurveyOption::create(['question_id' => $q4->id, 'option_text' => 'Fleksibel, siap beradaptasi dengan kebutuhan proyek lomba', 'category_weights' => json_encode(['_activity_flex' => 5]), 'order_index' => 4]);

        $q5 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => 'Metode pembelajaran apa yang membuatmu paling cepat memahami hal baru dan tidak mudah jenuh?',
            'question_type' => 'single_choice',
            'step_number' => 2,
            'category_hint' => 'learning_style',
            'order_index' => 5,
        ]);
        SurveyOption::create(['question_id' => $q5->id, 'option_text' => 'Praktik langsung membuat proyek nyata (Learning by Doing)', 'category_weights' => json_encode(['_learning_practice' => 5, 'frontend-development' => 4, 'mobile-app-development' => 4]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $q5->id, 'option_text' => 'Mempelajari konsep dan teori fundamental terlebih dahulu sebelum koding', 'category_weights' => json_encode(['_learning_theory' => 5, 'software-engineering' => 4, 'cybersecurity' => 4]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $q5->id, 'option_text' => 'Eksperimen trial-and-error, membedah kode sumber, atau menganalisis dataset', 'category_weights' => json_encode(['_learning_experiment' => 5, 'ai-ml' => 4, 'game-development' => 4]), 'order_index' => 3]);
        SurveyOption::create(['question_id' => $q5->id, 'option_text' => 'Diskusi kelompok, brainstorming ide, dan bedah studi kasus bersama rekan', 'category_weights' => json_encode(['_learning_discussion' => 5, 'ui-ux-design' => 4, 'penulisan-esai-karya-ilmiah' => 4]), 'order_index' => 4]);

        // ------------------ STEP 3: BRANCHING LOGIC / PERTANYAAN MENDALAM ------------------
        
        // Branch: Frontend & UI
        $qF1 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => '[Fokus Web & UI] Ketika mengunjungi sebuah website, aspek apa yang paling sering menarik perhatianmu?',
            'question_type' => 'single_choice',
            'step_number' => 3,
            'branch_key' => 'frontend_branch',
            'order_index' => 6,
        ]);
        SurveyOption::create(['question_id' => $qF1->id, 'option_text' => 'Animasi halus, microinteraction, dan efek visual yang terasa hidup', 'category_weights' => json_encode(['frontend-development' => 15, 'ui-ux-design' => 10]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $qF1->id, 'option_text' => 'Kecepatan loading dan kemudahan navigasi saat dibuka di smartphone', 'category_weights' => json_encode(['frontend-development' => 12, 'software-engineering' => 8]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $qF1->id, 'option_text' => 'Kombinasi warna, tipografi, dan kerapian layout desain antarmuka', 'category_weights' => json_encode(['ui-ux-design' => 15, 'graphic-design' => 10]), 'order_index' => 3]);
        SurveyOption::create(['question_id' => $qF1->id, 'option_text' => 'Belum tahu, ingin mempelajari dasar-dasar HTML/CSS/JS terlebih dahulu', 'category_weights' => json_encode(['frontend-development' => 8]), 'order_index' => 4]);

        // Branch: Backend & Software Eng
        $qB1 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => '[Fokus Backend & Arsitektur] Tantangan teknis mana yang paling membuatmu penasaran untuk dipecahkan?',
            'question_type' => 'single_choice',
            'step_number' => 3,
            'branch_key' => 'backend_branch',
            'order_index' => 7,
        ]);
        SurveyOption::create(['question_id' => $qB1->id, 'option_text' => 'Bagaimana database menangani jutaan kueri tanpa melambat (Optimasi & Indexing)', 'category_weights' => json_encode(['backend-development' => 15, 'software-engineering' => 10]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $qB1->id, 'option_text' => 'Membangun API yang aman dengan enkripsi, otentikasi role, dan rate limiting', 'category_weights' => json_encode(['backend-development' => 14, 'cybersecurity' => 8]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $qB1->id, 'option_text' => 'Menerapkan Clean Architecture dan automated unit testing agar kode bebas bug', 'category_weights' => json_encode(['software-engineering' => 16, 'full-stack-development' => 8]), 'order_index' => 3]);
        SurveyOption::create(['question_id' => $qB1->id, 'option_text' => 'Menyusun kontainer Docker dan otomatisasi deployment cloud', 'category_weights' => json_encode(['cloud-devops' => 16, 'backend-development' => 10]), 'order_index' => 4]);

        // Branch: AI & Data Science
        $qA1 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => '[Fokus AI & Data Science] Skenario pengolahan data mana yang paling menarik minatmu?',
            'question_type' => 'single_choice',
            'step_number' => 3,
            'branch_key' => 'ai_data_branch',
            'order_index' => 8,
        ]);
        SurveyOption::create(['question_id' => $qA1->id, 'option_text' => 'Melatih model klasifikasi citra atau Computer Vision untuk deteksi objek otomatis', 'category_weights' => json_encode(['ai-ml' => 16, 'data-science' => 8]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $qA1->id, 'option_text' => 'Mengeksplorasi Large Language Model (LLM) untuk asisten percakapan cerdas (NLP/RAG)', 'category_weights' => json_encode(['ai-ml' => 16, 'software-engineering' => 6]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $qA1->id, 'option_text' => 'Menemukan pola tersembunyi dari dataset besar dan menyajikannya dalam grafik interaktif', 'category_weights' => json_encode(['data-science' => 18, 'ai-ml' => 8]), 'order_index' => 3]);
        SurveyOption::create(['question_id' => $qA1->id, 'option_text' => 'Baru ingin mencoba belajar Python dan pustaka pengolahan data dasar', 'category_weights' => json_encode(['data-science' => 10, 'ai-ml' => 8]), 'order_index' => 4]);

        // Branch: Cybersecurity
        $qC1 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => '[Fokus Cybersecurity] Bidang pengujian keamanan mana yang paling ingin kamu eksplorasi?',
            'question_type' => 'single_choice',
            'step_number' => 3,
            'branch_key' => 'cyber_branch',
            'order_index' => 9,
        ]);
        SurveyOption::create(['question_id' => $qC1->id, 'option_text' => 'Web Application Security & OWASP Top 10 (SQL Injection, XSS, IDOR testing)', 'category_weights' => json_encode(['cybersecurity' => 18, 'backend-development' => 8]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $qC1->id, 'option_text' => 'Tantangan CTF kategori Cryptography & Reverse Engineering', 'category_weights' => json_encode(['cybersecurity' => 18, 'software-engineering' => 8]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $qC1->id, 'option_text' => 'Network Forensics dan analisis paket lalu lintas data', 'category_weights' => json_encode(['cybersecurity' => 15, 'cloud-devops' => 6]), 'order_index' => 3]);
        SurveyOption::create(['question_id' => $qC1->id, 'option_text' => 'Ingin memulai dari pemahaman etika hacking dan konfigurasi Linux dasar', 'category_weights' => json_encode(['cybersecurity' => 10]), 'order_index' => 4]);

        // Branch: Design & UI/UX
        $qD1 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => '[Fokus Desain & Kreatif] Karya desain apa yang paling memicu kreativitasmu?',
            'question_type' => 'single_choice',
            'step_number' => 3,
            'branch_key' => 'design_branch',
            'order_index' => 10,
        ]);
        SurveyOption::create(['question_id' => $qD1->id, 'option_text' => 'Membuat prototipe aplikasi mobile/web lengkap dengan user journey dan flow di Figma', 'category_weights' => json_encode(['ui-ux-design' => 18, 'frontend-development' => 6]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $qD1->id, 'option_text' => 'Merancang identitas visual, poster lomba yang memukau, dan ilustrasi digital', 'category_weights' => json_encode(['graphic-design' => 18, 'ui-ux-design' => 6]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $qD1->id, 'option_text' => 'Melakukan wawancara pengguna dan usability testing untuk menemukan pain point produk', 'category_weights' => json_encode(['ui-ux-design' => 16]), 'order_index' => 3]);
        SurveyOption::create(['question_id' => $qD1->id, 'option_text' => 'Ingin mencoba software desain digital pertama kali untuk menyalurkan minat seni', 'category_weights' => json_encode(['graphic-design' => 10, 'ui-ux-design' => 10]), 'order_index' => 4]);

        // Branch: Game Dev
        $qG1 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => '[Fokus Game Dev] Komponen pembuatan game mana yang paling ingin kamu tekuni?',
            'question_type' => 'single_choice',
            'step_number' => 3,
            'branch_key' => 'game_branch',
            'order_index' => 11,
        ]);
        SurveyOption::create(['question_id' => $qG1->id, 'option_text' => 'Koding mekanika pergerakan karakter, fisika, dan collision detection', 'category_weights' => json_encode(['game-development' => 18, 'software-engineering' => 8]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $qG1->id, 'option_text' => 'Level design, perancangan puzzle seru, dan cerita interaktif', 'category_weights' => json_encode(['game-development' => 16, 'ui-ux-design' => 6]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $qG1->id, 'option_text' => 'Mengikuti kompetisi Game Jam 48 jam bersama tim', 'category_weights' => json_encode(['game-development' => 16]), 'order_index' => 3]);

        // Branch: Essay & PKM
        $qE1 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => '[Fokus Esai & Karya Ilmiah] Topik penulisan gagasan seperti apa yang paling kamu sukai?',
            'question_type' => 'single_choice',
            'step_number' => 3,
            'branch_key' => 'essay_branch',
            'order_index' => 12,
        ]);
        SurveyOption::create(['question_id' => $qE1->id, 'option_text' => 'Gagasan futuristik teknologi solutif untuk SDGs dan masyarakat (PKM-GFK / PKM-GT)', 'category_weights' => json_encode(['penulisan-esai-karya-ilmiah' => 18]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $qE1->id, 'option_text' => 'Riset empiris penerapan algoritma atau analisis komparatif sistem teknologi', 'category_weights' => json_encode(['penulisan-esai-karya-ilmiah' => 16, 'data-science' => 8]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $qE1->id, 'option_text' => 'Esai kritis mengenai etika kecerdasan buatan dan transformasi digital bangsa', 'category_weights' => json_encode(['penulisan-esai-karya-ilmiah' => 16, 'ai-ml' => 6]), 'order_index' => 3]);

        // Branch: Sports
        $qS1 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => '[Fokus Olahraga] Cabang olahraga mana yang ingin kamu ikuti latihannya secara rutin bersama divisi?',
            'question_type' => 'single_choice',
            'step_number' => 3,
            'branch_key' => 'sports_branch',
            'order_index' => 13,
        ]);
        SurveyOption::create(['question_id' => $qS1->id, 'option_text' => 'Futsal — siap bergabung latihan mingguan dan turnamen antarfakultas', 'category_weights' => json_encode(['olahraga-futsal' => 22]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $qS1->id, 'option_text' => 'Voli — siap bergabung latihan teknik passing, smash, dan turnamen voli', 'category_weights' => json_encode(['olahraga-voli' => 22]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $qS1->id, 'option_text' => 'Tertarik mengikuti keduanya (Futsal & Voli) untuk menjaga kebugaran', 'category_weights' => json_encode(['olahraga-futsal' => 14, 'olahraga-voli' => 14]), 'order_index' => 3]);

        // Branch: Explore
        $qX1 = SurveyQuestion::create([
            'campaign_id' => $campaign->id,
            'question_text' => '[Eksplorasi Bebas] Di antara pilihan berikut, hasil karya mana yang paling membuatmu bangga jika berhasil kamu buat?',
            'question_type' => 'single_choice',
            'step_number' => 3,
            'branch_key' => 'explore_branch',
            'order_index' => 14,
        ]);
        SurveyOption::create(['question_id' => $qX1->id, 'option_text' => 'Sebuah website interaktif yang dapat diakses dan digunakan oleh teman-temanku', 'category_weights' => json_encode(['frontend-development' => 16, 'full-stack-development' => 12]), 'order_index' => 1]);
        SurveyOption::create(['question_id' => $qX1->id, 'option_text' => 'Desain tampilan aplikasi mobile yang estetik dan disukai banyak orang', 'category_weights' => json_encode(['ui-ux-design' => 16, 'graphic-design' => 10]), 'order_index' => 2]);
        SurveyOption::create(['question_id' => $qX1->id, 'option_text' => 'Sistem bot pintar atau analisis grafik data yang akurat', 'category_weights' => json_encode(['ai-ml' => 14, 'data-science' => 14]), 'order_index' => 3]);
        SurveyOption::create(['question_id' => $qX1->id, 'option_text' => 'Sebuah tim olahraga yang solid dan kompak di lapangan', 'category_weights' => json_encode(['olahraga-futsal' => 14, 'olahraga-voli' => 14]), 'order_index' => 4]);
    }
}
