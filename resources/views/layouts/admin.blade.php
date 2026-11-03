<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Hub') — {{ \App\Models\AppSetting::get('app_short_name', 'MINBAK TI') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <script>
        (function() {
            try {
                const saved = localStorage.getItem('minbak_theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const isLight = saved === 'light' || (!saved && !prefersDark && saved !== 'dark');
                if (isLight) {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.classList.add('light');
                } else {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.remove('light');
                }
            } catch(e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-brand-navy text-brand-light font-sans antialiased min-h-screen flex transition-colors duration-300" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
         style="display:none;"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 w-72 bg-brand-surface border-r border-brand-border flex flex-col transition-transform duration-300 ease-in-out">
        
        <!-- Sidebar Brand -->
        <div class="h-20 px-6 flex items-center justify-between border-b border-brand-border/80">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-purple to-brand-cyan flex items-center justify-center shadow-glow-combined">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-white tracking-tight leading-tight">MINBAK TI</h1>
                    <span class="text-[11px] font-mono uppercase tracking-wider text-brand-cyan">Management Hub</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Sidebar User Badge -->
        <div class="p-4 mx-4 my-3 rounded-xl bg-slate-900/60 border border-brand-border/60 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-brand-purple/20 border border-brand-purple/40 flex items-center justify-center text-brand-purple font-bold">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span class="text-[11px] font-mono uppercase text-brand-muted">
                        {{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Navigation Menus (13 Minimum Required Menus) -->
        <nav class="flex-1 overflow-y-auto px-4 py-2 space-y-1 text-sm">
            <!-- 1. Ringkasan -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-brand-purple/20 text-brand-cyan border border-brand-cyan/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Ringkasan</span>
            </a>

            <!-- 2. Data Mahasiswa -->
            <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.students.*') ? 'bg-brand-purple/20 text-brand-cyan border border-brand-cyan/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Data Mahasiswa</span>
            </a>

            <!-- 3. Operasional Kompetisi -->
            <a href="{{ route('admin.competitions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.competitions.*') ? 'bg-brand-purple/20 text-brand-cyan border border-brand-cyan/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Kompetisi</span>
            </a>

            <!-- 4. Pendaftaran Peserta (Including Manual Staff Registration) -->
            <a href="{{ route('admin.registrations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.registrations.*') ? 'bg-brand-purple/20 text-brand-cyan border border-brand-cyan/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span>Pendaftaran Peserta</span>
            </a>

            <!-- 5. Partisipasi dan Prestasi -->
            <a href="{{ route('admin.achievements.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.achievements.*') ? 'bg-brand-purple/20 text-brand-cyan border border-brand-cyan/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                <span>Verifikasi Prestasi</span>
            </a>

            <!-- 6. Asesmen dan Kampanye -->
            <a href="{{ route('admin.assessments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.assessments.*') ? 'bg-brand-purple/20 text-brand-cyan border border-brand-cyan/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Asesmen & Kampanye</span>
            </a>

            @if(auth()->user()->isKetuaOrAbove())
                <div class="pt-3 pb-1">
                    <span class="px-3 text-[10px] font-mono uppercase tracking-wider text-brand-muted">Ketua & Pengawas</span>
                </div>

                <!-- 7. Laporan dan Ekspor CSV -->
                <a href="{{ route('admin.students.export') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-slate-300 hover:bg-slate-800/60 hover:text-white transition-colors">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Ekspor CSV Anggota</span>
                </a>

                <!-- 8. Audit Log Keamanan -->
                <a href="{{ route('admin.audit_logs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.audit_logs.*') ? 'bg-brand-purple/20 text-brand-cyan border border-brand-cyan/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Log Audit Keamanan</span>
                </a>
            @endif

            @if(auth()->user()->isSuperAdmin())
                <div class="pt-3 pb-1">
                    <span class="px-3 text-[10px] font-mono uppercase tracking-wider text-brand-muted">Super Administrator</span>
                </div>

                <!-- 9. Manajemen Pengurus & Role -->
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-brand-purple/20 text-brand-cyan border border-brand-cyan/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Manajemen Pengurus</span>
                </a>

                <!-- 10. Pengaturan Aplikasi -->
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-brand-purple/20 text-brand-cyan border border-brand-cyan/30' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pengaturan Sistem</span>
                </a>
            @endif

            <div class="pt-4 mt-4 border-t border-brand-border/60">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-brand-cyan hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Website Publik</span>
                </a>
            </div>
        </nav>

        <!-- Sidebar Logout -->
        <div class="p-4 border-t border-brand-border/80">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-brand-border text-slate-400 hover:text-rose-400 hover:border-rose-500/50 hover:bg-rose-950/20 text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Admin Container -->
    <div class="flex-1 lg:pl-72 flex flex-col min-h-screen">
        <!-- Top Navbar -->
        <header class="h-20 bg-brand-navy/90 backdrop-blur-md border-b border-brand-border sticky top-0 z-30 px-4 sm:px-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg border border-brand-border text-slate-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight">@yield('page_title', 'Dashboard')</h2>
                    <p class="text-xs text-brand-muted hidden sm:block">Divisi Minat dan Bakat — Operasional & Pemetaan Talenta</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <!-- Theme Mode Switcher -->
                <button type="button" 
                        onclick="window.handleAdminThemeToggle()"
                        class="p-2 rounded-lg border border-brand-border bg-brand-surface text-brand-muted hover:text-amber-400 transition-colors focus:outline-none"
                        title="Ganti Mode Gelap / Terang">
                    <svg class="w-4 h-4 admin-theme-sun text-amber-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="w-4 h-4 admin-theme-moon text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <div class="hidden sm:flex items-center gap-2 text-xs font-mono px-3 py-1.5 rounded-lg bg-slate-900 border border-brand-border">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-slate-300">SERVER ACTIVE</span>
                </div>
                <a href="{{ route('home') }}" target="_blank" class="text-xs text-brand-cyan hover:underline flex items-center gap-1">
                    <span>Lihat Web</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 sm:px-8 pt-4">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-950/80 border border-emerald-500/50 text-emerald-200 flex items-center justify-between shadow-lg" role="alert">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-200 flex items-center justify-between shadow-lg" role="alert">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white">&times;</button>
                </div>
            @endif
        </div>

        <!-- Content Area -->
        <main class="flex-1 p-4 sm:p-8">
            @yield('content')
        </main>
    </div>

    <script>
        window.handleAdminThemeToggle = function() {
            if (window.toggleMinbakTheme) {
                const newTheme = window.toggleMinbakTheme();
                updateAdminThemeUI(newTheme);
            }
        };

        function updateAdminThemeUI(theme) {
            const isLight = theme === 'light';
            document.querySelectorAll('.admin-theme-sun').forEach(el => el.classList.toggle('hidden', !isLight));
            document.querySelectorAll('.admin-theme-moon').forEach(el => el.classList.toggle('hidden', isLight));
        }

        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = document.documentElement.classList.contains('light') ? 'light' : 'dark';
            updateAdminThemeUI(currentTheme);
        });
    </script>

    @stack('scripts')
</body>
</html>
