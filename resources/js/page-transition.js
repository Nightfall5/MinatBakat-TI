/**
 * MINBAK TI — Smooth Page Transitions Controller
 * Features:
 * - Native View Transitions API support (cross-document navigation)
 * - Cosmic glowing top progress bar with quantum trickle animation
 * - Smooth content fade/scale exit & enter transitions
 * - Smart link interception (skips downloads, external links, anchor links, and modifier keys)
 * - BFCache (back/forward cache) restoration handling
 * - Accessibility (respects prefers-reduced-motion)
 */

(function() {
    'use strict';

    let progressBar = null;
    let trickleTimer = null;
    let currentProgress = 0;

    function getOrCreateProgressBar() {
        if (!progressBar) {
            progressBar = document.getElementById('cosmic-page-progress');
            if (!progressBar) {
                progressBar = document.createElement('div');
                progressBar.id = 'cosmic-page-progress';
                document.body.prepend(progressBar);
            }
        }
        return progressBar;
    }

    function setProgress(percent) {
        const bar = getOrCreateProgressBar();
        currentProgress = percent;
        bar.style.width = percent + '%';
        if (percent > 0 && percent < 100) {
            bar.classList.add('active');
        }
    }

    function startProgress() {
        if (trickleTimer) clearInterval(trickleTimer);
        setProgress(15);

        trickleTimer = setInterval(() => {
            if (currentProgress < 65) {
                setProgress(currentProgress + Math.random() * 12 + 6);
            } else if (currentProgress < 85) {
                setProgress(currentProgress + Math.random() * 4 + 2);
            }
        }, 180);
    }

    function completeProgress() {
        if (trickleTimer) clearInterval(trickleTimer);
        const bar = getOrCreateProgressBar();
        setProgress(100);
        setTimeout(() => {
            bar.classList.remove('active');
            setTimeout(() => {
                bar.style.width = '0%';
                currentProgress = 0;
            }, 300);
        }, 150);
    }

    function resetTransitionState() {
        document.body.classList.remove('page-transitioning-out');
        if (trickleTimer) clearInterval(trickleTimer);
        const bar = getOrCreateProgressBar();
        bar.classList.remove('active');
        bar.style.width = '0%';
        currentProgress = 0;
    }

    // Intercept clicks on links for smooth exit transition
    document.addEventListener('click', (e) => {
        if (e.defaultPrevented) return;

        const link = e.target.closest('a');
        if (!link || !link.href) return;

        // Skip non-navigational links
        if (link.target === '_blank' || link.hasAttribute('download')) return;
        if (link.getAttribute('rel') === 'external') return;
        if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey || e.button !== 0) return;

        try {
            const destUrl = new URL(link.href, window.location.href);

            // Skip external origins
            if (destUrl.origin !== window.location.origin) return;

            // Skip hash jumps on the same page
            if (destUrl.pathname === window.location.pathname && 
                destUrl.search === window.location.search && 
                destUrl.hash !== '') {
                return;
            }

            // Skip clicking the exact same URL
            if (destUrl.href === window.location.href) {
                e.preventDefault();
                return;
            }

            // Skip javascript: or mailto: links
            if (destUrl.protocol !== 'http:' && destUrl.protocol !== 'https:') return;

            // Trigger smooth transition exit & progress bar
            startProgress();
            document.body.classList.add('page-transitioning-out');

        } catch (err) {
            // Fallback to standard browser behavior
        }
    });

    // Form submission transition feedback
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (!form) return;
        if (form.target === '_blank') return;
        
        startProgress();
        document.body.classList.add('page-transitioning-out');
    });

    // Handle initial page load and back-forward cache (bfcache)
    window.addEventListener('pageshow', (e) => {
        // If loaded from cache (e.g. Back button), reset leaving classes immediately
        resetTransitionState();

        // Apply smooth entrance to main content
        const main = document.querySelector('main');
        if (main) {
            main.classList.remove('page-enter-active');
            void main.offsetWidth; // Trigger reflow
            main.classList.add('page-enter-active');
        }

        completeProgress();
    });

    // Expose helpers globally if needed
    window.PageTransitions = {
        start: startProgress,
        complete: completeProgress,
        reset: resetTransitionState
    };
})();

