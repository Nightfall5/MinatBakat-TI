/**
 * MINBAK TI — Animated Digital Network Canvas
 * Renders interactive glowing particle constellation with mouse gravity.
 */
(function() {
    'use strict';

    // Check accessibility & preferences
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    window.initDigitalNetwork = function(canvasId = 'network-canvas') {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        // Check if user disabled visual effects
        if (localStorage.getItem('minbak_fx_disabled') === 'true') {
            canvas.style.display = 'none';
            return;
        }

        const ctx = canvas.getContext('2d');
        let width = canvas.width = canvas.parentElement.offsetWidth || window.innerWidth;
        let height = canvas.height = canvas.parentElement.offsetHeight || window.innerHeight;

        const particles = [];
        const particleCount = Math.min(65, Math.floor((width * height) / 14000));
        const maxDistance = 140;

        const mouse = {
            x: null,
            y: null,
            radius: 120
        };

        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.vx = (Math.random() - 0.5) * 0.75;
                this.vy = (Math.random() - 0.5) * 0.75;
                this.radius = Math.random() * 1.8 + 1.2;
                this.baseAlpha = Math.random() * 0.5 + 0.3;
                this.isPurple = Math.random() > 0.45;
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;

                // Bounce off edges smoothly
                if (this.x < 0 || this.x > width) this.vx *= -1;
                if (this.y < 0 || this.y > height) this.vy *= -1;

                // Mouse gravity interaction
                if (mouse.x !== null && mouse.y !== null) {
                    const dx = mouse.x - this.x;
                    const dy = mouse.y - this.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist < mouse.radius) {
                        const force = (mouse.radius - dist) / mouse.radius;
                        this.x -= (dx / dist) * force * 1.8;
                        this.y -= (dy / dist) * force * 1.8;
                    }
                }
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = this.isPurple 
                    ? `rgba(139, 92, 246, ${this.baseAlpha})` 
                    : `rgba(34, 211, 238, ${this.baseAlpha})`;
                ctx.shadowBlur = 8;
                ctx.shadowColor = this.isPurple ? '#8B5CF6' : '#22D3EE';
                ctx.fill();
                ctx.shadowBlur = 0;
            }
        }

        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }

        function drawConnections() {
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist < maxDistance) {
                        const alpha = (1 - dist / maxDistance) * 0.28;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);

                        // Dynamic gradient between purple and cyan
                        const grad = ctx.createLinearGradient(particles[i].x, particles[i].y, particles[j].x, particles[j].y);
                        grad.addColorStop(0, `rgba(139, 92, 246, ${alpha})`);
                        grad.addColorStop(1, `rgba(34, 211, 238, ${alpha})`);

                        ctx.strokeStyle = grad;
                        ctx.lineWidth = 1;
                        ctx.stroke();
                    }
                }
            }
        }

        let animationFrameId;

        function animate() {
            ctx.clearRect(0, 0, width, height);

            for (let i = 0; i < particles.length; i++) {
                particles[i].update();
                particles[i].draw();
            }

            drawConnections();
            animationFrameId = requestAnimationFrame(animate);
        }

        animate();

        // Event listeners
        const handleMouseMove = (e) => {
            const rect = canvas.getBoundingClientRect();
            mouse.x = e.clientX - rect.left;
            mouse.y = e.clientY - rect.top;
        };

        const handleMouseLeave = () => {
            mouse.x = null;
            mouse.y = null;
        };

        const handleResize = () => {
            width = canvas.width = canvas.parentElement.offsetWidth || window.innerWidth;
            height = canvas.height = canvas.parentElement.offsetHeight || window.innerHeight;
        };

        window.addEventListener('mousemove', handleMouseMove, { passive: true });
        document.addEventListener('mouseleave', handleMouseLeave);
        window.addEventListener('resize', handleResize, { passive: true });

        // Cleanup handler
        window._cleanupDigitalNetwork = function() {
            cancelAnimationFrame(animationFrameId);
            window.removeEventListener('mousemove', handleMouseMove);
            document.removeEventListener('mouseleave', handleMouseLeave);
            window.removeEventListener('resize', handleResize);
        };
    };

    // Auto-init on DOMContentLoaded if canvas exists
    document.addEventListener('DOMContentLoaded', () => {
        window.initDigitalNetwork('network-canvas');
    });
})();
