import './bootstrap';
import Alpine from 'alpinejs';
import './page-transition';
import '../css/denty-theme.css';

window.Alpine = Alpine;
Alpine.start();

// Keep the existing preference toggle API for compatibility with the layouts.
window.toggleMinbakTheme = function() {
    const isDark = document.documentElement.classList.contains('dark');
    const newTheme = isDark ? 'light' : 'dark';
    document.documentElement.classList.toggle('dark', newTheme === 'dark');
    document.documentElement.classList.toggle('light', newTheme === 'light');
    localStorage.setItem('minbak_theme', newTheme);
    window.dispatchEvent(new CustomEvent('minbak-theme-changed', { detail: { theme: newTheme } }));
    return newTheme;
};

// Background video is intentionally retired by the new light visual direction.
window.toggleBackgroundVideo = function() { return false; };
