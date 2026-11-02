/**
 * MINBAK TI — Custom Cyber Spider Cursor
 * Real-time 8-legged inverse-kinematics neon spider following mouse.
 * Features:
 * - Spring interpolation physics
 * - Dynamic 8-legged crawl gait with stepping threshold
 * - Idle micro-motion
 * - Click digital web pulse
 * - Element hover reaction
 * - Touch & reduced-motion accessibility bypass
 * - Toggleable via UI
 */
(function () {
    'use strict';

    // 1. Accessibility Checks
    const isTouch = window.matchMedia('(pointer: coarse)').matches;
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (isTouch || prefersReducedMotion) {
        return; // Disabled on touchscreen and reduced motion
    }

    // Check user preference
    if (localStorage.getItem('minbak_spider_disabled') === 'true') {
        return;
    }

    // 2. Setup Canvas Overlay
    const canvas = document.createElement('canvas');
    canvas.id = 'spider-cursor-canvas';
    canvas.style.position = 'fixed';
    canvas.style.top = '0';
    canvas.style.left = '0';
    canvas.style.width = '100vw';
    canvas.style.height = '100vh';
    canvas.style.pointerEvents = 'none';
    canvas.style.zIndex = '99999';
    document.body.appendChild(canvas);

    // Dynamic reduced-motion and touch cancellation
    window.addEventListener('touchstart', () => {
        canvas.style.display = 'none';
    }, { passive: true, once: true });

    if (window.matchMedia) {
        window.matchMedia('(prefers-reduced-motion: reduce)').addEventListener('change', (e) => {
            canvas.style.display = e.matches ? 'none' : 'block';
        });
    }

    const ctx = canvas.getContext('2d');
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;

    window.addEventListener('resize', () => {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    }, { passive: true });

    // 3. Pointer & Spider State
    const mouse = {
        x: width / 2,
        y: height / 2,
        prevX: width / 2,
        prevY: height / 2,
        speed: 0,
        isHoveringInteractive: false,
        isInside: false,
    };

    let spiderOpacity = 0.0;

    const spider = {
        x: width / 2,
        y: height / 2,
        vx: 0,
        vy: 0,
        angle: 0,
        targetAngle: 0,
        size: 11, // Base body scale
        isIdle: true,
        idleTimer: 0,
    };

    // Digital ripples array
    const ripples = [];

    // 4. Leg Configuration (8 Legs: 4 Left, 4 Right)
    // Angles relative to body facing direction
    const legConfigs = [
        // Left side (Angles: -45°, -85°, -125°, -155°)
        { side: -1, shoulderAngle: -0.65, reachDist: 26, stepDist: 18, length1: 14, length2: 17, gaitGroup: 0 },
        { side: -1, shoulderAngle: -1.20, reachDist: 32, stepDist: 22, length1: 18, length2: 20, gaitGroup: 1 },
        { side: -1, shoulderAngle: -1.85, reachDist: 34, stepDist: 22, length1: 19, length2: 22, gaitGroup: 0 },
        { side: -1, shoulderAngle: -2.45, reachDist: 28, stepDist: 20, length1: 16, length2: 19, gaitGroup: 1 },
        // Right side (Angles: 45°, 85°, 125°, 155°)
        { side: 1, shoulderAngle: 0.65, reachDist: 26, stepDist: 18, length1: 14, length2: 17, gaitGroup: 1 },
        { side: 1, shoulderAngle: 1.20, reachDist: 32, stepDist: 22, length1: 18, length2: 20, gaitGroup: 0 },
        { side: 1, shoulderAngle: 1.85, reachDist: 34, stepDist: 22, length1: 19, length2: 22, gaitGroup: 1 },
        { side: 1, shoulderAngle: 2.45, reachDist: 28, stepDist: 20, length1: 16, length2: 19, gaitGroup: 0 },
    ];

    const legs = legConfigs.map(cfg => ({
        cfg,
        footX: spider.x + Math.cos(spider.angle + cfg.shoulderAngle) * cfg.reachDist,
        footY: spider.y + Math.sin(spider.angle + cfg.shoulderAngle) * cfg.reachDist,
        targetFootX: 0,
        targetFootY: 0,
        isStepping: false,
        stepProgress: 0,
        stepStart: { x: 0, y: 0 },
    }));

    // 5. Event Listeners
    window.addEventListener('mousemove', (e) => {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
        mouse.isInside = true;

        // Check if cursor hovers interactive element
        const target = e.target;
        if (target && target.closest) {
            mouse.isHoveringInteractive = !!target.closest('a, button, input, select, textarea, [role="button"], .glass-panel-hover');
        }
    }, { passive: true });

    document.addEventListener('mouseleave', () => {
        mouse.isInside = false;
    });

    window.addEventListener('click', (e) => {
        // Create digital web ripple pulse
        ripples.push({
            x: e.clientX,
            y: e.clientY,
            radius: 0,
            maxRadius: 65,
            opacity: 0.85,
            color: mouse.isHoveringInteractive ? '#8B5CF6' : '#22D3EE',
        });
    });

    // 6. Kinematic Calculations & Update Loop
    function updateSpider() {
        if (mouse.isInside) {
            spiderOpacity = Math.min(1.0, spiderOpacity + 0.08);
        } else {
            spiderOpacity = Math.max(0.0, spiderOpacity - 0.05);
            if (spiderOpacity <= 0) return;
        }

        // Distance & velocity
        const dx = mouse.x - spider.x;
        const dy = mouse.y - spider.y;
        const dist = Math.sqrt(dx * dx + dy * dy);

        // Calculate mouse movement speed
        const mdx = mouse.x - mouse.prevX;
        const mdy = mouse.y - mouse.prevY;
        mouse.speed = Math.sqrt(mdx * mdx + mdy * mdy);
        mouse.prevX = mouse.x;
        mouse.prevY = mouse.y;

        // Spring follow interpolation
        const spring = mouse.isHoveringInteractive ? 0.12 : 0.085;
        const friction = 0.72;

        spider.vx += dx * spring;
        spider.vy += dy * spring;
        spider.vx *= friction;
        spider.vy *= friction;

        spider.x += spider.vx;
        spider.y += spider.vy;

        // Update angle towards pointer if moving
        if (dist > 3) {
            spider.targetAngle = Math.atan2(dy, dx);
            let diffAngle = spider.targetAngle - spider.angle;

            // Normalize angle difference to [-PI, PI]
            while (diffAngle < -Math.PI) diffAngle += Math.PI * 2;
            while (diffAngle > Math.PI) diffAngle -= Math.PI * 2;

            spider.angle += diffAngle * 0.18;
            spider.isIdle = false;
            spider.idleTimer = 0;
        } else {
            spider.isIdle = true;
            spider.idleTimer += 0.04;
        }

        // Update legs with alternating stepping gait
        legs.forEach((leg, idx) => {
            const cfg = leg.cfg;
            // Desired shoulder base position
            const shoulderBaseAngle = spider.angle + cfg.shoulderAngle;
            // Idle breathing variation
            const idleOffset = spider.isIdle ? Math.sin(spider.idleTimer + idx * 0.8) * 1.5 : 0;
            const currentReach = cfg.reachDist + idleOffset;

            // Ideal foot position on the ground
            const idealFootX = spider.x + Math.cos(shoulderBaseAngle) * currentReach;
            const idealFootY = spider.y + Math.sin(shoulderBaseAngle) * currentReach;

            const distToIdeal = Math.hypot(idealFootX - leg.footX, idealFootY - leg.footY);

            // Step if foot is too far from ideal resting spot
            if (!leg.isStepping && distToIdeal > cfg.stepDist) {
                leg.isStepping = true;
                leg.stepProgress = 0;
                leg.stepStart = { x: leg.footX, y: leg.footY };
                // Lead the step slightly in the direction of movement
                const leadFactor = Math.min(12, mouse.speed * 0.6);
                leg.targetFootX = idealFootX + Math.cos(spider.angle) * leadFactor;
                leg.targetFootY = idealFootY + Math.sin(spider.angle) * leadFactor;
            }

            // Animate stepping motion (lift arc)
            if (leg.isStepping) {
                // Leg stepping speed scales with mouse velocity
                const stepSpeed = spider.isIdle ? 0.08 : Math.min(0.22, 0.12 + mouse.speed * 0.008);
                leg.stepProgress += stepSpeed;

                if (leg.stepProgress >= 1) {
                    leg.isStepping = false;
                    leg.footX = leg.targetFootX;
                    leg.footY = leg.targetFootY;
                } else {
                    // Smooth cubic step interpolation
                    const t = leg.stepProgress;
                    const smoothT = t * t * (3 - 2 * t);
                    leg.footX = leg.stepStart.x + (leg.targetFootX - leg.stepStart.x) * smoothT;
                    leg.footY = leg.stepStart.y + (leg.targetFootY - leg.stepStart.y) * smoothT;
                }
            }
        });
    }

    // 7. Render Spider
    function renderSpider() {
        if (spiderOpacity <= 0.01) return;

        ctx.save();
        ctx.globalAlpha = spiderOpacity;

        const cyanColor = '#22D3EE';
        const purpleColor = '#8B5CF6';
        const activeColor = mouse.isHoveringInteractive ? purpleColor : cyanColor;

        // A. Draw Legs using 2-segment Inverse Kinematics
        legs.forEach(leg => {
            const cfg = leg.cfg;
            // Hip attachment point on body
            const hipAngle = spider.angle + (cfg.shoulderAngle * 0.65);
            const hipDist = spider.size * 0.55;
            const hipX = spider.x + Math.cos(hipAngle) * hipDist;
            const hipY = spider.y + Math.sin(hipAngle) * hipDist;

            const footX = leg.footX;
            const footY = leg.footY;

            // Two-segment joint calculation
            const legDx = footX - hipX;
            const legDy = footY - hipY;
            const directDist = Math.hypot(legDx, legDy);
            const angleToFoot = Math.atan2(legDy, legDx);

            // Arc lift during step
            const liftArc = leg.isStepping ? Math.sin(leg.stepProgress * Math.PI) * 5 : 0;

            // Joint bend angle (law of cosines)
            const l1 = cfg.length1;
            const l2 = cfg.length2;
            const clampedDist = Math.min(l1 + l2 - 0.5, Math.max(Math.abs(l1 - l2) + 0.5, directDist));
            const kneeAngleOffset = Math.acos(
                Math.max(-1, Math.min(1, (l1 * l1 + clampedDist * clampedDist - l2 * l2) / (2 * l1 * clampedDist)))
            ) * cfg.side;

            const kneeX = hipX + Math.cos(angleToFoot + kneeAngleOffset) * l1;
            const kneeY = hipY + Math.sin(angleToFoot + kneeAngleOffset) * l1 - liftArc;

            // Draw leg segments
            ctx.beginPath();
            ctx.moveTo(hipX, hipY);
            ctx.lineTo(kneeX, kneeY);
            ctx.lineTo(footX, footY);

            ctx.strokeStyle = activeColor;
            ctx.lineWidth = 1.35;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.shadowBlur = 6;
            ctx.shadowColor = activeColor;
            ctx.stroke();

            // Tiny glowing foot tip
            ctx.beginPath();
            ctx.arc(footX, footY, 1.2, 0, Math.PI * 2);
            ctx.fillStyle = activeColor;
            ctx.fill();
        });

        // B. Draw Body (Cephalothorax + Abdomen)
        // Abdomen (Rear)
        const rearAngle = spider.angle + Math.PI;
        const abdoDist = spider.size * 0.65;
        const abdoX = spider.x + Math.cos(rearAngle) * abdoDist;
        const abdoY = spider.y + Math.sin(rearAngle) * abdoDist;

        ctx.beginPath();
        ctx.ellipse(abdoX, abdoY, spider.size * 0.75, spider.size * 0.55, spider.angle, 0, Math.PI * 2);
        ctx.fillStyle = '#0B1020';
        ctx.fill();
        ctx.strokeStyle = activeColor;
        ctx.lineWidth = 1.6;
        ctx.shadowBlur = 8;
        ctx.shadowColor = activeColor;
        ctx.stroke();

        // Digital core line on abdomen
        ctx.beginPath();
        ctx.moveTo(abdoX - Math.cos(spider.angle) * 3, abdoY - Math.sin(spider.angle) * 3);
        ctx.lineTo(abdoX + Math.cos(spider.angle) * 3, abdoY + Math.sin(spider.angle) * 3);
        ctx.strokeStyle = mouse.isHoveringInteractive ? '#67E8F9' : '#A78BFA';
        ctx.lineWidth = 1.5;
        ctx.stroke();

        // Cephalothorax (Front head & thorax)
        ctx.beginPath();
        ctx.ellipse(spider.x, spider.y, spider.size * 0.55, spider.size * 0.45, spider.angle, 0, Math.PI * 2);
        ctx.fillStyle = '#121A2B';
        ctx.fill();
        ctx.strokeStyle = activeColor;
        ctx.lineWidth = 1.6;
        ctx.stroke();

        // Glowing Spider Eyes (4 digital sensors)
        const eyeOffset = spider.size * 0.35;
        const eyeAngleOffset = 0.35;
        const eyeColor = mouse.isHoveringInteractive ? '#22D3EE' : '#F8FAFC';

        [
            { angle: spider.angle - eyeAngleOffset, dist: eyeOffset, r: 1.2 },
            { angle: spider.angle + eyeAngleOffset, dist: eyeOffset, r: 1.2 },
            { angle: spider.angle - (eyeAngleOffset * 0.5), dist: eyeOffset * 1.2, r: 1.0 },
            { angle: spider.angle + (eyeAngleOffset * 0.5), dist: eyeOffset * 1.2, r: 1.0 },
        ].forEach(eye => {
            const ex = spider.x + Math.cos(eye.angle) * eye.dist;
            const ey = spider.y + Math.sin(eye.angle) * eye.dist;
            ctx.beginPath();
            ctx.arc(ex, ey, eye.r, 0, Math.PI * 2);
            ctx.fillStyle = eyeColor;
            ctx.shadowBlur = 5;
            ctx.shadowColor = eyeColor;
            ctx.fill();
        });

        ctx.restore();
    }

    // 8. Render Click Digital Web Ripples
    function renderRipples() {
        for (let i = ripples.length - 1; i >= 0; i--) {
            const r = ripples[i];
            r.radius += 3.2;
            r.opacity -= 0.042;

            if (r.opacity <= 0 || r.radius >= r.maxRadius) {
                ripples.splice(i, 1);
                continue;
            }

            ctx.save();
            ctx.beginPath();
            ctx.arc(r.x, r.y, r.radius, 0, Math.PI * 2);
            ctx.strokeStyle = r.color;
            ctx.globalAlpha = Math.max(0, r.opacity);
            ctx.lineWidth = 1.8;
            ctx.shadowBlur = 10;
            ctx.shadowColor = r.color;
            ctx.stroke();

            // Inner digital web polygon spokes
            const spokes = 8;
            ctx.lineWidth = 0.9;
            for (let s = 0; s < spokes; s++) {
                const spokeAngle = (Math.PI * 2 / spokes) * s;
                ctx.beginPath();
                ctx.moveTo(r.x, r.y);
                ctx.lineTo(r.x + Math.cos(spokeAngle) * r.radius, r.y + Math.sin(spokeAngle) * r.radius);
                ctx.stroke();
            }

            ctx.restore();
        }
    }

    // 9. Main Animation Loop
    function loop() {
        ctx.clearRect(0, 0, width, height);

        renderRipples();
        updateSpider();
        renderSpider();

        requestAnimationFrame(loop);
    }

    loop();

    // 10. Global Toggle Helper
    window.toggleSpiderCursor = function () {
        const currentlyDisabled = localStorage.getItem('minbak_spider_disabled') === 'true';
        if (currentlyDisabled) {
            localStorage.setItem('minbak_spider_disabled', 'false');
            canvas.style.display = 'block';
        } else {
            localStorage.setItem('minbak_spider_disabled', 'true');
            canvas.style.display = 'none';
        }
        return !currentlyDisabled;
    };
})();
