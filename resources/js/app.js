import './bootstrap';
import Alpine from 'alpinejs';
import './page-transition';

window.Alpine = Alpine;
Alpine.start();

// Global Theme Management (Dark Mode <-> Light Mode)
window.toggleMinbakTheme = function() {
    const isDark = document.documentElement.classList.contains('dark');
    const newTheme = isDark ? 'light' : 'dark';
    
    if (newTheme === 'dark') {
        document.documentElement.classList.add('dark');
        document.documentElement.classList.remove('light');
    } else {
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
    }
    
    localStorage.setItem('minbak_theme', newTheme);
    window.dispatchEvent(new CustomEvent('minbak-theme-changed', { detail: { theme: newTheme } }));
    return newTheme;
};

// Global Background Video Controller
window.toggleBackgroundVideo = function() {
    const video = document.getElementById('bigbang-bg-video');
    if (!video) return false;
    if (video.paused) {
        video.play();
        return true;
    } else {
        video.pause();
        return false;
    }
};


