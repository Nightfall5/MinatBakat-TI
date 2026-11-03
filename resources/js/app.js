import './bootstrap';
import Alpine from 'alpinejs';
import './page-transition';
import '../css/denty-theme.css';

window.Alpine = Alpine;
Alpine.start();

// Compatibility for older layout handlers. The refreshed interface is light-first.
window.toggleMinbakTheme = function() {
    document.documentElement.classList.remove('dark');
    document.documentElement.classList.add('light');
    localStorage.setItem('minbak_theme', 'light');
    window.dispatchEvent(new CustomEvent('minbak-theme-changed', { detail: { theme: 'light' } }));
    return 'light';
};
window.toggleBackgroundVideo = function() { return false; };

function formatCount(value) {
    return new Intl.NumberFormat('id-ID').format(value);
}

function initDentyInteractions() {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const revealItems = [...document.querySelectorAll(
        'main .glass-panel, main .glass-panel-hover, main .glass-panel-purple-hover, main .denty-reveal'
    )];
    revealItems.forEach((element, index) => {
        element.classList.add('denty-reveal');
        element.style.setProperty('--denty-reveal-delay', String((index % 4) * 65) + 'ms');
    });

    if (reduceMotion || !('IntersectionObserver' in window)) {
        revealItems.forEach(element => element.classList.add('is-visible'));
    } else {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -35px 0px' });
        revealItems.forEach(element => revealObserver.observe(element));
    }

    const counters = document.querySelectorAll('[data-count-up]');
    const runCounter = element => {
        const target = Number(element.dataset.countUp);
        if (!Number.isFinite(target) || target < 0) return;
        if (reduceMotion || !('requestAnimationFrame' in window)) {
            element.textContent = formatCount(target);
            return;
        }
        const duration = 1050;
        const start = performance.now();
        const tick = now => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 4);
            element.textContent = formatCount(Math.round(target * eased));
            if (progress < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    };

    if ('IntersectionObserver' in window && !reduceMotion) {
        const counterObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                runCounter(entry.target);
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.6 });
        counters.forEach(counter => counterObserver.observe(counter));
    } else {
        counters.forEach(runCounter);
    }

    const header = document.querySelector('body > header');
    if (header) {
        const syncHeader = () => header.classList.toggle('is-scrolled', window.scrollY > 12);
        syncHeader();
        window.addEventListener('scroll', syncHeader, { passive: true });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDentyInteractions, { once: true });
} else {
    initDentyInteractions();
}
