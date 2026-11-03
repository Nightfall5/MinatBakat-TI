import './bootstrap';
import Alpine from 'alpinejs';
import './page-transition';
import '../css/denty-theme.css';
import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

window.Alpine = Alpine;
Alpine.start();

// Keep legacy theme controls harmless while the new interface remains light-first.
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

function initRevealAndCounters(reduceMotion) {
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
        const observer = new IntersectionObserver((entries, current) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                current.unobserve(entry.target);
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -35px 0px' });
        revealItems.forEach(element => observer.observe(element));
    }

    const counters = document.querySelectorAll('[data-count-up]');
    const runCounter = element => {
        const target = Number(element.dataset.countUp);
        if (!Number.isFinite(target) || target < 0) return;
        if (reduceMotion) {
            element.textContent = formatCount(target);
            return;
        }
        const start = performance.now();
        const tick = now => {
            const progress = Math.min((now - start) / 1050, 1);
            element.textContent = formatCount(Math.round(target * (1 - Math.pow(1 - progress, 4))));
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
}

function initTypingEffect(reduceMotion) {
    const target = document.querySelector('#minbak-typed-text');
    if (!target) return;
    const phrases = ['Eksplorasi minatmu', 'Kembangkan kemampuanmu', 'Raih prestasi bersama'];
    if (reduceMotion) {
        target.textContent = phrases[0];
        return;
    }

    let phraseIndex = 0;
    let charIndex = phrases[0].length;
    let deleting = false;
    let timer = null;
    const type = () => {
        const phrase = phrases[phraseIndex];
        if (!deleting) {
            charIndex += 1;
            target.textContent = phrase.slice(0, charIndex);
            if (charIndex >= phrase.length) {
                deleting = true;
                timer = window.setTimeout(type, 1550);
                return;
            }
        } else {
            charIndex -= 1;
            target.textContent = phrase.slice(0, charIndex);
            if (charIndex <= 0) {
                deleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
            }
        }
        timer = window.setTimeout(type, deleting ? 32 : 58);
    };
    timer = window.setTimeout(type, 1250);
    document.addEventListener('visibilitychange', () => {
        if (document.hidden && timer) window.clearTimeout(timer);
        else if (!document.hidden) timer = window.setTimeout(type, 400);
    });
}

function initTalentOrbit(reduceMotion) {
    const canvas = document.querySelector('#minbak-three-canvas');
    const stage = document.querySelector('#minbak-scene-stage');
    const energyButton = document.querySelector('#minbak-energy-toggle');
    const status = document.querySelector('#minbak-scene-status');
    if (!canvas || !stage) return;

    const setFallback = (message) => {
        if (status) status.textContent = message;
        canvas.style.display = 'none';
        stage.classList.add('minbak-scene-fallback');
    };

    if (!window.WebGLRenderingContext) {
        setFallback('STATIC PREVIEW');
        return;
    }

    let renderer;
    let animationFrame = 0;
    let energyMode = false;
    let controls;
    let clock;
    let elapsed = 0;
    let visible = !document.hidden;
    let resizeObserver;

    try {
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(37, 1, 0.1, 80);
        camera.position.set(0, 0.1, 7.2);

        renderer = new THREE.WebGLRenderer({
            canvas,
            alpha: true,
            antialias: !window.matchMedia('(max-width: 720px)').matches,
            powerPreference: 'low-power'
        });
        const smallScreen = window.matchMedia('(max-width: 720px)').matches;
        renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, smallScreen ? 1.2 : 1.55));
        renderer.outputColorSpace = THREE.SRGBColorSpace;
        renderer.toneMapping = THREE.ACESFilmicToneMapping;
        renderer.toneMappingExposure = 1.15;

        scene.add(new THREE.HemisphereLight(0xffffff, 0x9abac7, 2.0));
        const keyLight = new THREE.PointLight(0x9be2f3, 24, 16);
        keyLight.position.set(3, 2.5, 3);
        scene.add(keyLight);
        const fillLight = new THREE.PointLight(0x5fa2be, 16, 14);
        fillLight.position.set(-3, -2, 1);
        scene.add(fillLight);

        const talentGroup = new THREE.Group();
        scene.add(talentGroup);

        const coreMaterial = new THREE.MeshPhysicalMaterial({
            color: 0xb9f0f7,
            metalness: 0.55,
            roughness: 0.2,
            clearcoat: 1,
            clearcoatRoughness: 0.12,
            emissive: 0x3d8198,
            emissiveIntensity: 0.28
        });
        const core = new THREE.Mesh(
            new THREE.TorusKnotGeometry(0.86, 0.2, 180, 24, 2, 3),
            coreMaterial
        );
        talentGroup.add(core);

        const innerCore = new THREE.Mesh(
            new THREE.IcosahedronGeometry(0.43, 2),
            new THREE.MeshPhysicalMaterial({
                color: 0xffffff,
                metalness: 0.16,
                roughness: 0.12,
                clearcoat: 1,
                emissive: 0x80c8db,
                emissiveIntensity: 0.22
            })
        );
        talentGroup.add(innerCore);

        const wire = new THREE.Mesh(
            new THREE.IcosahedronGeometry(1.42, 1),
            new THREE.MeshBasicMaterial({ color: 0x5fa2be, wireframe: true, transparent: true, opacity: 0.17 })
        );
        talentGroup.add(wire);

        const ringColors = [0x5fa2be, 0x9be2f3, 0x8baecb];
        const rings = [];
        [
            { radius: 1.75, rotation: [1.12, 0.25, 0.1] },
            { radius: 1.98, rotation: [0.34, 1.05, 0.7] },
            { radius: 2.12, rotation: [1.48, 0.7, 1.2] }
        ].forEach((config, index) => {
            const ring = new THREE.Mesh(
                new THREE.TorusGeometry(config.radius, 0.008, 8, 180),
                new THREE.MeshBasicMaterial({ color: ringColors[index], transparent: true, opacity: 0.52 - index * 0.08 })
            );
            ring.rotation.set(...config.rotation);
            talentGroup.add(ring);
            rings.push(ring);
        });

        const satelliteGeometry = new THREE.IcosahedronGeometry(0.085, 1);
        const satellites = [];
        [
            { radius: 1.88, speed: 0.31, phase: 0.4, tilt: 0.25, color: 0x5fa2be },
            { radius: 2.05, speed: -0.23, phase: 2.6, tilt: 1.1, color: 0x3d6c97 },
            { radius: 1.68, speed: 0.38, phase: 4.4, tilt: 2.1, color: 0x9be2f3 },
            { radius: 2.17, speed: -0.17, phase: 5.6, tilt: 0.8, color: 0x78b6c9 }
        ].forEach((config, index) => {
            const material = new THREE.MeshStandardMaterial({
                color: config.color,
                metalness: 0.36,
                roughness: 0.2,
                emissive: config.color,
                emissiveIntensity: 0.18
            });
            const mesh = new THREE.Mesh(satelliteGeometry, material);
            talentGroup.add(mesh);
            satellites.push({ mesh, ...config, index });
        });

        const starCount = smallScreen ? 180 : 360;
        const positions = new Float32Array(starCount * 3);
        for (let i = 0; i < starCount; i += 1) {
            const radius = 2.9 + Math.random() * 5.5;
            const theta = Math.random() * Math.PI * 2;
            const phi = Math.acos(Math.random() * 2 - 1);
            positions[i * 3] = radius * Math.sin(phi) * Math.cos(theta);
            positions[i * 3 + 1] = radius * Math.cos(phi);
            positions[i * 3 + 2] = radius * Math.sin(phi) * Math.sin(theta);
        }
        const starGeometry = new THREE.BufferGeometry();
        starGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        const stars = new THREE.Points(
            starGeometry,
            new THREE.PointsMaterial({ color: 0x5d9db5, size: smallScreen ? 0.022 : 0.026, transparent: true, opacity: 0.58 })
        );
        scene.add(stars);

        controls = new OrbitControls(camera, canvas);
        controls.enableDamping = true;
        controls.dampingFactor = 0.06;
        controls.enablePan = false;
        controls.enableZoom = false;
        controls.rotateSpeed = 0.55;
        controls.autoRotate = !reduceMotion;
        controls.autoRotateSpeed = 0.58;
        controls.minPolarAngle = 0.7;
        controls.maxPolarAngle = 2.4;

        const resize = () => {
            const rect = stage.getBoundingClientRect();
            const width = Math.max(1, rect.width);
            const height = Math.max(1, rect.height);
            camera.aspect = width / height;
            camera.fov = width < 420 ? 43 : 37;
            camera.updateProjectionMatrix();
            renderer.setSize(width, height, false);
        };
        resizeObserver = new ResizeObserver(resize);
        resizeObserver.observe(stage);
        resize();

        const raycaster = new THREE.Raycaster();
        const pointer = new THREE.Vector2();
        let pointerDown = null;
        canvas.addEventListener('pointerdown', event => {
            pointerDown = { x: event.clientX, y: event.clientY };
        });
        canvas.addEventListener('pointerup', event => {
            if (!pointerDown) return;
            const moved = Math.hypot(event.clientX - pointerDown.x, event.clientY - pointerDown.y);
            pointerDown = null;
            if (moved > 7) return;
            const bounds = canvas.getBoundingClientRect();
            pointer.x = ((event.clientX - bounds.left) / bounds.width) * 2 - 1;
            pointer.y = -(((event.clientY - bounds.top) / bounds.height) * 2 - 1);
            raycaster.setFromCamera(pointer, camera);
            if (raycaster.intersectObjects([core, innerCore], false).length) toggleEnergy();
        });

        const toggleEnergy = () => {
            energyMode = !energyMode;
            if (energyButton) {
                energyButton.setAttribute('aria-pressed', String(energyMode));
                energyButton.innerHTML = energyMode
                    ? '<span aria-hidden="true">✦</span> MODE ENERGI AKTIF'
                    : '<span aria-hidden="true">✳</span> AKTIFKAN MODE ENERGI';
            }
            if (controls) controls.autoRotate = !reduceMotion && !energyMode;
            coreMaterial.emissiveIntensity = energyMode ? 0.85 : 0.28;
            if (status) status.textContent = energyMode ? 'ENERGY MODE' : 'SCENE READY';
        };
        energyButton?.addEventListener('click', toggleEnergy);

        clock = new THREE.Clock();
        const animate = () => {
            if (!visible) return;
            animationFrame = window.requestAnimationFrame(animate);
            const delta = Math.min(clock.getDelta(), 0.04);
            const motionDelta = (!reduceMotion || energyMode) ? delta : 0;
            elapsed += motionDelta;

            const pulse = 1 + Math.sin(elapsed * (energyMode ? 3 : 1.45)) * (energyMode ? 0.055 : 0.022);
            talentGroup.scale.setScalar(pulse);
            core.rotation.x += motionDelta * (energyMode ? 0.42 : 0.18);
            core.rotation.y += motionDelta * (energyMode ? 0.58 : 0.28);
            innerCore.rotation.x -= motionDelta * 0.16;
            innerCore.rotation.y += motionDelta * 0.21;
            wire.rotation.y -= motionDelta * 0.035;
            wire.rotation.x += motionDelta * 0.018;
            rings[0].rotation.z += motionDelta * 0.035;
            rings[1].rotation.x -= motionDelta * 0.024;
            rings[2].rotation.y += motionDelta * 0.02;
            stars.rotation.y += motionDelta * 0.003;

            satellites.forEach(satellite => {
                const angle = elapsed * satellite.speed + satellite.phase;
                satellite.mesh.position.set(
                    Math.cos(angle) * satellite.radius,
                    Math.sin(angle * 1.12 + satellite.tilt) * satellite.radius * 0.42,
                    Math.sin(angle) * satellite.radius * 0.75
                );
                satellite.mesh.rotation.x += motionDelta * (0.45 + satellite.index * 0.08);
                satellite.mesh.rotation.y += motionDelta * 0.4;
            });

            controls.update();
            renderer.render(scene, camera);
        };

        if (status) status.textContent = 'SCENE READY';
        document.addEventListener('visibilitychange', () => {
            visible = !document.hidden;
            if (!renderer) return;
            if (visible) {
                clock.start();
                animate();
            } else {
                window.cancelAnimationFrame(animationFrame);
                clock.stop();
            }
        });
        animate();
    } catch (error) {
        console.error('MINBAK 3D scene failed:', error);
        setFallback('STATIC PREVIEW');
    }
}

function initHeaderAndScrollEffects() {
    const header = document.querySelector('body > header');
    if (header) {
        const syncHeader = () => header.classList.toggle('is-scrolled', window.scrollY > 12);
        syncHeader();
        window.addEventListener('scroll', syncHeader, { passive: true });
    }
    const progress = document.querySelector('#minbak-scroll-progress');
    const updateProgress = () => {
        if (!progress) return;
        const scrollable = document.documentElement.scrollHeight - window.innerHeight;
        progress.style.transform = 'scaleX(' + (scrollable > 0 ? window.scrollY / scrollable : 0) + ')';
    };
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
}

function initMinbakInteractions() {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    initRevealAndCounters(reduceMotion);
    initTypingEffect(reduceMotion);
    initTalentOrbit(reduceMotion);
    initHeaderAndScrollEffects();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMinbakInteractions, { once: true });
} else {
    initMinbakInteractions();
}
