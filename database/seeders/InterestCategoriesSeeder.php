<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InterestCategory;

class InterestCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Frontend Development',
                'slug' => 'frontend-development',
                'description' => 'Eksplorasi antarmuka web modern, interaktivitas dinamis, responsivitas multi-device, dan animasi web tingkat lanjut.',
                'icon' => 'layout',
                'common_skills' => json_encode(['HTML5/CSS3', 'JavaScript (ES6+)', 'Tailwind CSS', 'Vue / React', 'Responsive Design', 'Web Animations']),
                'starter_projects' => json_encode(['Website Portofolio Interaktif', 'Dashboard UI Kustom', 'Landing Page Kompetisi Kampus']),
                'order_index' => 1,
            ],
            [
                'name' => 'Backend Development',
                'slug' => 'backend-development',
                'description' => 'Perancangan arsitektur server, RESTful / GraphQL API, optimasi database, keamanan sistem, dan performa backend.',
                'icon' => 'server',
                'common_skills' => json_encode(['PHP / Laravel', 'Node.js / Express', 'Python / Go', 'SQL & Database Indexing', 'API Security & Auth', 'Redis / Caching']),
                'starter_projects' => json_encode(['RESTful API Sistem Informasi', 'Service Autentikasi JWT', 'Background Queue Worker']),
                'order_index' => 2,
            ],
            [
                'name' => 'Full-Stack Development',
                'slug' => 'full-stack-development',
                'description' => 'Membangun aplikasi web end-to-end dari perancangan database dan logic server hingga tampilan antarmuka interaktif.',
                'icon' => 'layers',
                'common_skills' => json_encode(['Full-Stack Frameworks', 'Database Modeling', 'Component-Driven UI', 'State Management', 'Deployment & CI/CD']),
                'starter_projects' => json_encode(['Aplikasi E-Commerce Kampus', 'Platform Manajemen Event Mahasiswa', 'Portal Komunitas Interaktif']),
                'order_index' => 3,
            ],
            [
                'name' => 'Software Engineering',
                'slug' => 'software-engineering',
                'description' => 'Prinsip rekayasa perangkat lunak skala enterprise, pola desain (Design Patterns), Clean Code, dan automated testing.',
                'icon' => 'cpu',
                'common_skills' => json_encode(['OOP & SOLID Principles', 'Design Patterns', 'Unit & Integration Testing', 'Git Flow & Version Control', 'Refactoring']),
                'starter_projects' => json_encode(['Mini ORM Library', 'CLI Tool Manajemen Repository', 'Sistem Antrean Pemrosesan File']),
                'order_index' => 4,
            ],
            [
                'name' => 'Mobile App Development',
                'slug' => 'mobile-app-development',
                'description' => 'Pengembangan aplikasi mobile lintas platform (Flutter, React Native) maupun native Android / iOS yang responsif dan fluid.',
                'icon' => 'smartphone',
                'common_skills' => json_encode(['Flutter / Dart', 'React Native', 'Kotlin / Swift', 'Mobile UI Patterns', 'Offline-First & SQLite']),
                'starter_projects' => json_encode(['Aplikasi Pengingat Jadwal Kuliah', 'Aplikasi Presensi Berbasis QR', 'Mobile Fitness Tracker']),
                'order_index' => 5,
            ],
            [
                'name' => 'UI/UX Design',
                'slug' => 'ui-ux-design',
                'description' => 'Riset kebutuhan pengguna, perancangan kawat gambar (wireframe), desain antarmuka estetis, design system, dan usability testing.',
                'icon' => 'figma',
                'common_skills' => json_encode(['Figma / FigJam', 'Design Systems & Tokens', 'Wireframing & Prototyping', 'User Research & Personas', 'Usability Testing']),
                'starter_projects' => json_encode(['Redesain Aplikasi Akademik Kampus', 'Design System Startup Teknologi', 'Prototipe Interaktif SuperApp Mahasiswa']),
                'order_index' => 6,
            ],
            [
                'name' => 'Graphic Design',
                'slug' => 'graphic-design',
                'description' => 'Komunikasi visual, tipografi digital, identitas brand, ilustrasi vektor, materi promosi media sosial, dan poster lomba.',
                'icon' => 'palette',
                'common_skills' => json_encode(['Adobe Illustrator', 'Photoshop', 'Visual Hierarchy', 'Typography & Layout', 'Color Theory', 'Branding']),
                'starter_projects' => json_encode(['Visual Identity Festival TI', 'Koleksi Poster Kompetisi', 'Brand Guidelines Komunitas']),
                'order_index' => 7,
            ],
            [
                'name' => 'Artificial Intelligence & Machine Learning',
                'slug' => 'ai-ml',
                'description' => 'Eksplorasi model kecerdasan buatan, Deep Learning, Computer Vision, Natural Language Processing (NLP), dan GenAI.',
                'icon' => 'sparkles',
                'common_skills' => json_encode(['Python', 'PyTorch / TensorFlow', 'Scikit-Learn', 'Data Preprocessing', 'LLM Prompt Engineering', 'Model Deployment']),
                'starter_projects' => json_encode(['Klasifikasi Citra Hama Tanaman', 'Chatbot Akademik Berbasis RAG', 'Model Prediksi Prestasi Akademik']),
                'order_index' => 8,
            ],
            [
                'name' => 'Data Science & Analytics',
                'slug' => 'data-science',
                'description' => 'Analisis data kuantitatif, statistik terapan, eksplorasi pola tren, visualisasi data interaktif, dan business intelligence.',
                'icon' => 'bar-chart',
                'common_skills' => json_encode(['Pandas & NumPy', 'SQL for Analytics', 'Power BI / Tableau', 'Matplotlib / Seaborn', 'Exploratory Data Analysis']),
                'starter_projects' => json_encode(['Analisis Sentimen Media Sosial', 'Dashboard Interaktif Minat Mahasiswa', 'Segmentasi Partisipasi Lomba']),
                'order_index' => 9,
            ],
            [
                'name' => 'Cybersecurity',
                'slug' => 'cybersecurity',
                'description' => 'Analisis kerentanan sistem, pengujian penetrasi berizin (Pentesting), investigasi forensik digital, cryptography, dan kompetisi CTF.',
                'icon' => 'shield',
                'common_skills' => json_encode(['Linux Internals & Bash', 'Network Protocols & Wireshark', 'Web Security & OWASP Top 10', 'Reverse Engineering', 'Cryptography', 'CTF Tools']),
                'starter_projects' => json_encode(['Audit Keamanan Aplikasi Demo', 'Writeup Skenario CTF Web/Forensics', 'Script Otomatisasi Vulnerability Scanner']),
                'order_index' => 10,
            ],
            [
                'name' => 'Cloud Computing & DevOps',
                'slug' => 'cloud-devops',
                'description' => 'Infrastruktur cloud modern, kontainerisasi Docker, Kubernetes, otomatisasi CI/CD pipeline, dan monitoring sistem berdaya tahan tinggi.',
                'icon' => 'cloud',
                'common_skills' => json_encode(['Docker & Containers', 'CI/CD (GitHub Actions)', 'Linux Server Admin', 'AWS / GCP Basics', 'Nginx & Reverse Proxy', 'Terraform / IaC']),
                'starter_projects' => json_encode(['Pipeline CI/CD Aplikasi Web Otomatis', 'Multi-Container Deployment dengan Docker Compose', 'Setup Cloud VPS Termonitor']),
                'order_index' => 11,
            ],
            [
                'name' => 'Game Development',
                'slug' => 'game-development',
                'description' => 'Perancangan mekanika gameplay, arsitektur game engine (Unity / Godot), animasi 2D/3D, dan partisipasi Game Jam.',
                'icon' => 'gamepad',
                'common_skills' => json_encode(['Unity / C#', 'Godot / GDScript', 'Game Mechanics & Physics', 'Level Design', 'Sprite Animation', 'Game Jam Prototyping']),
                'starter_projects' => json_encode(['2D Platformer Puzzle', 'Top-Down Roguelike Prototype', 'Game Edukasi Algoritma']),
                'order_index' => 12,
            ],
            [
                'name' => 'Penulisan Esai & Karya Ilmiah',
                'slug' => 'penulisan-esai-karya-ilmiah',
                'description' => 'Riset akademik, penyusunan gagasan inovatif teknologi, metodologi penelitian, sistematika penulisan PKM (Program Kreativitas Mahasiswa).',
                'icon' => 'file-text',
                'common_skills' => json_encode(['Metodologi Riset Ilmiah', 'Literatur Review & Mendeley', 'Sistematika Proposal PKM', 'Tata Bahasa Akademis', 'Presentasi Karya Ilmiah']),
                'starter_projects' => json_encode(['Proposal PKM Gagasan Futuristik', 'Artikel Ilmiah Review Teknologi Terkini', 'Esai Gagasan Transformasi Digital']),
                'order_index' => 13,
            ],
            [
                'name' => 'Olahraga: Futsal',
                'slug' => 'olahraga-futsal',
                'description' => 'Cabang olahraga futsal divisi minat dan bakat: latihan terstruktur, pembinaan fisik, strategi permainan tim, dan turnamen antarkampus.',
                'icon' => 'activity',
                'common_skills' => json_encode(['Teknik Dasar Passing & Shooting', 'Kerjasama Tim & Komunikasi Lapangan', 'Fisik & Stamina', 'Taktik Formasi Futsal']),
                'starter_projects' => json_encode(['Latihan Rutin Mingguan Tim TI', 'Uji Coba Tanding Persahabatan', 'Turnamen Piala Dekan']),
                'order_index' => 14,
            ],
            [
                'name' => 'Olahraga: Voli',
                'slug' => 'olahraga-voli',
                'description' => 'Cabang olahraga bola voli divisi minat dan bakat: penguasaan servis, passing atas/bawah, smash, koordinasi pertahanan, dan kompetisi olahraga.',
                'icon' => 'circle',
                'common_skills' => json_encode(['Servis & Passing Akurat', 'Smash & Blocking', 'Koordinasi Pertahanan & Rotasi', 'Stamina & Kelincahan']),
                'starter_projects' => json_encode(['Latihan Fundamental Voli TI', 'Spurring Tim Fakultas', 'Liga Olahraga Mahasiswa']),
                'order_index' => 15,
            ],
        ];

        foreach ($categories as $data) {
            InterestCategory::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
