<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MINBAK TI — Talent & Competition Hub')</title>
    <meta name="description" content="@yield('meta_description', 'Sistem Informasi Minat, Bakat, Kompetisi, dan Prestasi Mahasiswa Teknik Informatika. Eksplorasi skill, rekomendasi talenta, dan rekam jejak prestasi.')">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

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
<body class="bg-brand-navy text-brand-light min-h-screen flex flex-col relative selection:bg-brand-purple selection:text-white transition-colors duration-300" x-data="{ mobileMenuOpen: false }">

    <!-- Real Galaxy Big Bang Video Background Loop -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0 select-none">
        <video id="bigbang-bg-video" 
               autoplay 
               loop 
               muted 
               playsinline 
               class="w-full h-full object-cover transition-opacity duration-1000 scale-[1.02]">
            <source src="{{ asset('videos/bigbang-formation.webm') }}" type="video/webm">
            <source src="{{ asset('videos/bigbang-galaxy.webm') }}" type="video/webm">
        </video>
        <!-- Dynamic Contrast & Vignette Overlay (Adapts to Dark / Light Mode) -->
        <div id="video-overlay" class="absolute inset-0 bg-brand-navy/65 backdrop-brightness-[0.88] transition-all duration-500"></div>
    </div>

    <!-- Navigation Header with Cosmic Horizon Beam & Aurora Mist -->
    <header class="sticky top-0 z-40 backdrop-blur-xl bg-brand-navy/85 border-b border-brand-border/60 transition-colors duration-300 relative overflow-hidden">
        <!-- Ambient Header Cosmic Nebula Glow -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-45">
            <div class="absolute -top-10 left-1/4 w-96 h-20 bg-gradient-to-r from-brand-cyan/20 via-brand-purple/20 to-transparent blur-2xl animate-cosmic-aurora"></div>
            <div class="absolute -top-10 right-1/4 w-96 h-20 bg-gradient-to-r from-brand-purple/20 via-pink-500/20 to-transparent blur-2xl animate-cosmic-aurora delay-1000"></div>
        </div>

        <!-- Cosmic Quantum Horizon Energy Beam (Flowing Bottom Laser Horizon) -->
        <div class="absolute bottom-0 inset-x-0 h-[2px] overflow-hidden pointer-events-none z-20">
            <div class="cosmic-quantum-beam w-full h-full"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex items-center justify-between h-20">
                <!-- Brand / Logo with Singularity Photon Corona -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group focus:outline-none focus:ring-2 focus:ring-brand-cyan rounded-lg p-1">
                    <div class="relative">
                        <!-- Singularity Photon Corona Ring -->
                        <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-brand-cyan via-brand-purple to-pink-500 opacity-60 blur-sm group-hover:opacity-100 animate-spin-slow transition-opacity"></div>
                        <div class="relative w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-purple to-brand-cyan p-0.5 shadow-glow-combined group-hover:scale-105 transition-transform duration-300">
                            <div class="w-full h-full bg-brand-surface rounded-[10px] flex items-center justify-center">
                                <svg class="w-5 h-5 text-brand-cyan group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-white via-slate-100 to-brand-cyan bg-clip-text text-transparent">
                            {{ \App\Models\AppSetting::get('app_short_name', 'MINBAK TI') }}
                        </span>
                        <span class="hidden sm:block text-[11px] font-mono tracking-wider text-brand-muted uppercase">
                            Talent & Competition Hub
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-brand-cyan bg-brand-surface border border-brand-cyan/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                        Beranda
                    </a>
                    <a href="{{ route('exploration.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('exploration.*') ? 'text-brand-cyan bg-brand-surface border border-brand-cyan/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                        Eksplorasi Minat
                    </a>
                    <a href="{{ route('competitions.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('competitions.*') ? 'text-brand-cyan bg-brand-surface border border-brand-cyan/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                        Kompetisi
                    </a>
                    <a href="{{ route('assessment.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('assessment.*') ? 'text-brand-cyan bg-brand-surface border border-brand-cyan/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                        Asesmen
                    </a>
                    <a href="{{ route('achievements.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('achievements.*') ? 'text-brand-cyan bg-brand-surface border border-brand-cyan/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                        Prestasi
                    </a>
                    <a href="{{ route('about') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'text-brand-cyan bg-brand-surface border border-brand-cyan/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                        Tentang Kami
                    </a>
                </nav>

                <!-- Actions: Big Bang, Theme Toggle, Spider FX & Auth Buttons -->
                <div class="hidden md:flex items-center gap-2.5">
                    <!-- Background Video Pause/Play Toggle Button -->
                    <button type="button" 
                            id="toggle-video-btn"
                            onclick="const isPlaying = window.toggleBackgroundVideo(); document.getElementById('video-btn-icon').textContent = isPlaying ? '⏸️' : '▶️'; this.title = isPlaying ? 'Jeda Video Latar' : 'Putar Video Latar';"
                            class="px-3 py-2 rounded-xl border border-brand-border bg-brand-surface/60 text-brand-muted hover:text-white hover:border-brand-cyan/60 hover:shadow-glow-cyan transition-all focus:outline-none focus:ring-2 focus:ring-brand-cyan flex items-center gap-1.5 text-xs font-mono group"
                            title="Jeda / Putar Video Latar Belakang">
                        <span id="video-btn-icon" class="text-sm">⏸️</span>
                        <span class="hidden lg:inline text-[11px]">Video Latar</span>
                    </button>

                    <!-- Theme Mode Switcher (Dark 🌙 / Light ☀️) -->
                    <button type="button" 
                            id="desktop-theme-toggle-btn"
                            onclick="window.handleThemeToggle()"
                            class="p-2.5 rounded-xl border border-brand-border bg-brand-surface/60 text-brand-muted hover:text-amber-400 hover:border-amber-400/50 transition-all focus:outline-none focus:ring-2 focus:ring-brand-cyan shadow-sm"
                            title="Ganti Mode Gelap / Terang">
                        <svg class="w-4 h-4 theme-icon-sun text-amber-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg class="w-4 h-4 theme-icon-moon text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- Spider Cursor FX Toggle Button -->
                    <button type="button" 
                            id="toggle-spider-btn"
                            onclick="if(window.toggleSpiderCursor){ const state = window.toggleSpiderCursor(); this.title = state ? 'Spider Cursor Aktif' : 'Spider Cursor Non-aktif'; }"
                            class="p-2.5 rounded-xl border border-brand-border bg-brand-surface/60 text-brand-muted hover:text-brand-cyan hover:border-brand-cyan/50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-cyan"
                            title="Toggle Efek Spider Cursor">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4a3 3 0 00-3 3v2a3 3 0 006 0V7a3 3 0 00-3-3zM4 9h2m12 0h2M4 15h2m12 0h2M6 19l2-2m8 0l2 2M6 5l2 2m8-2l2-2"/>
                        </svg>
                    </button>

                    @auth
                        @if(auth()->user()->isStaffOrAbove())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-brand-purple hover:bg-purple-600 text-white shadow-glow-purple transition-all transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                Dashboard Admin
                            </a>
                        @else
                            <a href="{{ route('portal.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-brand-surface border border-brand-cyan/40 hover:border-brand-cyan text-brand-cyan shadow-glow-cyan transition-all transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Portal Mahasiswa
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 transition-colors" title="Keluar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-brand-purple to-brand-cyan hover:opacity-90 text-white shadow-glow-combined transition-all transform hover:-translate-y-0.5">
                            Daftar Mahasiswa
                        </a>
                    @endauth
                </div>

                <!-- Mobile Header Right Actions (Theme Switcher & Hamburger Button) -->
                <div class="flex items-center gap-2 md:hidden">
                    <button type="button" 
                            onclick="window.handleThemeToggle()"
                            class="p-2 rounded-lg border border-brand-border bg-brand-surface/60 text-brand-muted hover:text-amber-400 transition-colors focus:outline-none"
                            title="Ganti Mode Gelap / Terang">
                        <svg class="w-5 h-5 theme-icon-sun text-amber-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg class="w-5 h-5 theme-icon-moon text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <button type="button" 
                            @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="p-2 rounded-lg border border-brand-border text-slate-300 hover:text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-cyan"
                            aria-label="Buka Menu Navigasi">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenuOpen">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenuOpen" style="display:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden border-b border-brand-border bg-brand-surface/95 backdrop-blur-xl px-4 pt-3 pb-6 space-y-2"
             style="display:none;">
            
            <!-- Mobile Mode & Big Bang Controls -->
            <div class="grid grid-cols-2 gap-2 pb-2">
                <button type="button" 
                        onclick="window.handleThemeToggle()"
                        class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl border border-brand-border bg-brand-surface text-xs font-medium text-slate-300 hover:text-white">
                    <svg class="w-4 h-4 theme-icon-sun text-amber-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="w-4 h-4 theme-icon-moon text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <span id="mobile-theme-mode-text">Ganti Tema</span>
                </button>
                <button type="button" 
                        onclick="const isPlaying = window.toggleBackgroundVideo(); this.querySelector('span:first-child').textContent = isPlaying ? '⏸️' : '▶️';"
                        class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl border border-brand-border bg-brand-surface text-xs font-medium text-slate-300 hover:text-white">
                    <span>⏸️</span>
                    <span>Video Latar</span>
                </button>
            </div>

            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('home') ? 'text-brand-cyan bg-slate-800' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">Beranda</a>
            <a href="{{ route('exploration.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('exploration.*') ? 'text-brand-cyan bg-slate-800' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">Eksplorasi Minat</a>
            <a href="{{ route('competitions.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('competitions.*') ? 'text-brand-cyan bg-slate-800' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">Kompetisi</a>
            <a href="{{ route('assessment.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('assessment.*') ? 'text-brand-cyan bg-slate-800' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">Asesmen Bakat</a>
            <a href="{{ route('achievements.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('achievements.*') ? 'text-brand-cyan bg-slate-800' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">Prestasi</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('about') ? 'text-brand-cyan bg-slate-800' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">Tentang Kami</a>

            <div class="pt-4 border-t border-brand-border flex flex-col gap-2">
                @auth
                    @if(auth()->user()->isStaffOrAbove())
                        <a href="{{ route('admin.dashboard') }}" class="w-full text-center px-4 py-2.5 rounded-lg font-semibold bg-brand-purple text-white shadow-glow-purple">
                            Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('portal.dashboard') }}" class="w-full text-center px-4 py-2.5 rounded-lg font-semibold bg-brand-surface border border-brand-cyan text-brand-cyan shadow-glow-cyan">
                            Portal Mahasiswa
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center px-4 py-2 rounded-lg text-rose-400 hover:bg-rose-950/30">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center px-4 py-2 rounded-lg border border-brand-border text-slate-200 hover:bg-slate-800">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="w-full text-center px-4 py-2.5 rounded-lg bg-gradient-to-r from-brand-purple to-brand-cyan text-white font-semibold shadow-glow-combined">
                        Daftar Mahasiswa
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Global Flash Notification Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4 z-30">
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

        @if(session('warning'))
            <div class="p-4 rounded-xl bg-amber-950/80 border border-amber-500/50 text-amber-200 flex items-center justify-between shadow-lg" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('warning') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-amber-400 hover:text-white">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main Content Body -->
    <main class="flex-grow z-10 relative">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-brand-surface/90 border-t border-brand-border/80 mt-20 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Brand & Purpose -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-brand-purple to-brand-cyan flex items-center justify-center shadow-glow-combined">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="text-lg font-bold text-white tracking-tight">
                            {{ \App\Models\AppSetting::get('app_name', 'MINBAK TI — Talent & Competition Hub') }}
                        </span>
                    </div>
                    <p class="text-sm text-brand-muted max-w-md leading-relaxed">
                        Pusat pengelolaan minat, bakat, eksplorasi skill teknologi, dan delegasi kompetisi mahasiswa {{ \App\Models\AppSetting::get('org_name', 'Himpunan Mahasiswa Teknik Informatika') }} — {{ \App\Models\AppSetting::get('univ_name', 'Universitas Teknologi Indonesia') }}.
                    </p>
                    <div class="flex items-center gap-4 pt-2">
                        @if(\App\Models\AppSetting::get('instagram_handle'))
                            <a href="{{ \App\Models\AppSetting::get('instagram_url', 'https://instagram.com') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-xs font-mono text-brand-cyan hover:underline">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                {{ \App\Models\AppSetting::get('instagram_handle') }}
                            </a>
                        @endif
                        <span class="text-xs text-brand-muted font-mono">
                            {{ \App\Models\AppSetting::get('contact_email', 'minbak.ti@example.ac.id') }}
                        </span>
                    </div>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-200 mb-4">Navigasi Utama</h3>
                    <ul class="space-y-2 text-sm text-brand-muted">
                        <li><a href="{{ route('home') }}" class="hover:text-brand-cyan transition-colors">Beranda</a></li>
                        <li><a href="{{ route('exploration.index') }}" class="hover:text-brand-cyan transition-colors">Eksplorasi Bidang Minat</a></li>
                        <li><a href="{{ route('competitions.index') }}" class="hover:text-brand-cyan transition-colors">Katalog Kompetisi</a></li>
                        <li><a href="{{ route('assessment.index') }}" class="hover:text-brand-cyan transition-colors">Pemetaan & Asesmen Bakat</a></li>
                        <li><a href="{{ route('achievements.index') }}" class="hover:text-brand-cyan transition-colors">Rekam Jejak Prestasi</a></li>
                    </ul>
                </div>

                <!-- Col 3: Legal & Kebijakan -->
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-200 mb-4">Privasi & Organisasi</h3>
                    <ul class="space-y-2 text-sm text-brand-muted">
                        <li><a href="{{ route('about') }}" class="hover:text-brand-cyan transition-colors">Visi & Program Divisi</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-brand-cyan transition-colors">Kebijakan Privasi Mahasiswa</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-brand-cyan transition-colors">Portal Pengurus & Anggota</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-brand-border/60 flex flex-col sm:flex-row items-center justify-between text-xs text-brand-muted gap-4">
                <p>&copy; {{ date('Y') }} {{ \App\Models\AppSetting::get('org_name', 'Divisi Minat dan Bakat') }}. Dilindungi Hak Cipta.</p>
                <div class="flex items-center gap-4">
                    <span>Dikembangkan untuk Ekosistem Talenta Informatika</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400" title="Sistem Aktif"></span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts: Spider Cursor & Theme Controller -->
    <script src="{{ asset('js/spider-cursor.js') }}"></script>
    <script>
        window.handleThemeToggle = function() {
            if (window.toggleMinbakTheme) {
                const newTheme = window.toggleMinbakTheme();
                updateThemeUI(newTheme);
            }
        };

        function updateThemeUI(theme) {
            const isLight = theme === 'light';
            document.querySelectorAll('.theme-icon-sun').forEach(el => el.classList.toggle('hidden', !isLight));
            document.querySelectorAll('.theme-icon-moon').forEach(el => el.classList.toggle('hidden', isLight));
            const mobileText = document.getElementById('mobile-theme-mode-text');
            if (mobileText) {
                mobileText.textContent = isLight ? 'Mode Terang' : 'Mode Gelap';
            }
            const desktopBtn = document.getElementById('desktop-theme-toggle-btn');
            if (desktopBtn) {
                desktopBtn.title = isLight ? 'Beralih ke Mode Gelap' : 'Beralih ke Mode Terang';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = document.documentElement.classList.contains('light') ? 'light' : 'dark';
            updateThemeUI(currentTheme);
        });
    </script>

    @stack('scripts')
</body>
</html>
