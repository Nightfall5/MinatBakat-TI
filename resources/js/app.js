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

function initTalentNetwork(reduceMotion) {
    const canvas = document.querySelector('#minbak-three-canvas');
    const stage = document.querySelector('#minbak-scene-stage');
    const flowButton = document.querySelector('#minbak-energy-toggle');
    const status = document.querySelector('#minbak-scene-status');
    const selectedLabel = document.querySelector('#minbak-selected-field');
    const chips = [...document.querySelectorAll('.minbak-talent-chip')];
    if (!canvas || !stage) return;

    const fallback = message => {
        if (status) status.textContent = message;
        canvas.style.display = 'none';
        stage.classList.add('minbak-network-fallback-active');
    };
    if (!window.WebGLRenderingContext) {
        fallback('NETWORK PREVIEW');
        return;
    }

    let renderer;
    let controls;
    let frame = 0;
    let visible = !document.hidden;
    let flowActive = false;
    let selectedIndex = -1;
    let elapsed = 0;
    const smallScreen = window.matchMedia('(max-width: 720px)').matches;

    try {
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(38, 1, 0.1, 80);
        camera.position.set(0, 0.15, 8.4);
        renderer = new THREE.WebGLRenderer({
            canvas, alpha: true, antialias: !smallScreen, powerPreference: 'low-power'
        });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, smallScreen ? 1.15 : 1.45));
        renderer.outputColorSpace = THREE.SRGBColorSpace;
        renderer.toneMapping = THREE.ACESFilmicToneMapping;
        renderer.toneMappingExposure = 1.12;

        scene.add(new THREE.HemisphereLight(0xffffff, 0x9abac7, 2.1));
        const keyLight = new THREE.PointLight(0x9be2f3, 25, 18);
        keyLight.position.set(2.8, 3.2, 4);
        scene.add(keyLight);
        const fillLight = new THREE.PointLight(0x5fa2be, 18, 16);
        fillLight.position.set(-3.5, -2.2, 2);
        scene.add(fillLight);

        const network = new THREE.Group();
        scene.add(network);

        const branches = [
            { end: [-2.75, 1.55, -0.35], mid: [-1.25, 0.82, 0.18], color: 0x5fa2be },
            { end: [-0.45, 2.55, 0.42], mid: [-0.3, 1.2, -0.12], color: 0x80b8cf },
            { end: [2.65, 1.75, -0.2], mid: [1.2, 0.95, 0.22], color: 0x3d6c97 },
            { end: [2.85, -1.45, 0.4], mid: [1.3, -0.72, -0.18], color: 0x79b7b2 },
            { end: [0.8, -2.55, -0.25], mid: [0.42, -1.15, 0.2], color: 0x8baecb },
            { end: [-2.45, -1.85, 0.35], mid: [-1.15, -0.9, -0.2], color: 0x6aa9c0 },
            { end: [-3.0, -0.15, -0.3], mid: [-1.35, -0.08, 0.2], color: 0x91c9d2 }
        ];

        const hub = new THREE.Mesh(
            new THREE.IcosahedronGeometry(0.53, 2),
            new THREE.MeshPhysicalMaterial({
                color: 0xe8fbff, metalness: 0.18, roughness: 0.16,
                clearcoat: 1, emissive: 0x4b9eb7, emissiveIntensity: 0.26
            })
        );
        network.add(hub);
        const hubShell = new THREE.Mesh(
            new THREE.IcosahedronGeometry(0.72, 1),
            new THREE.MeshBasicMaterial({ color: 0x5fa2be, wireframe: true, transparent: true, opacity: 0.2 })
        );
        network.add(hubShell);

        const branchObjects = [];
        const nodeMeshes = [];
        const movingPackets = [];
        const subNodes = [];
        const hubPosition = new THREE.Vector3(0, 0, 0);

        branches.forEach((branch, index) => {
            const end = new THREE.Vector3(...branch.end);
            const mid = new THREE.Vector3(...branch.mid);
            const curve = new THREE.QuadraticBezierCurve3(hubPosition, mid, end);
            const tube = new THREE.Mesh(
                new THREE.TubeGeometry(curve, 48, 0.012, 6, false),
                new THREE.MeshBasicMaterial({ color: branch.color, transparent: true, opacity: 0.64 })
            );
            network.add(tube);

            const node = new THREE.Group();
            node.position.copy(end);
            const material = new THREE.MeshPhysicalMaterial({
                color: branch.color, metalness: 0.2, roughness: 0.2,
                clearcoat: 1, emissive: branch.color, emissiveIntensity: 0.2
            });
            const sphere = new THREE.Mesh(new THREE.IcosahedronGeometry(0.17, 2), material);
            const halo = new THREE.Mesh(
                new THREE.SphereGeometry(0.28, 18, 14),
                new THREE.MeshBasicMaterial({ color: branch.color, transparent: true, opacity: 0.12, side: THREE.DoubleSide })
            );
            node.add(sphere, halo);
            network.add(node);
            nodeMeshes.push({ group: node, sphere, halo, material, index });

            const packet = new THREE.Mesh(
                new THREE.SphereGeometry(0.045, 10, 8),
                new THREE.MeshBasicMaterial({ color: 0xffffff })
            );
            network.add(packet);
            movingPackets.push({ mesh: packet, curve, offset: index / branches.length });

            // A small secondary connection makes each branch feel like a pathway, not an orbit.
            const split = curve.getPointAt(0.64);
            const subEnd = split.clone().add(new THREE.Vector3((index % 2 ? -0.45 : 0.45), index % 2 ? -0.34 : 0.34, 0.16));
            const subCurve = new THREE.QuadraticBezierCurve3(hubPosition, split.clone().lerp(subEnd, 0.48), subEnd);
            const subLine = new THREE.Mesh(
                new THREE.TubeGeometry(subCurve, 24, 0.006, 5, false),
                new THREE.MeshBasicMaterial({ color: branch.color, transparent: true, opacity: 0.28 })
            );
            network.add(subLine);
            const subNode = new THREE.Mesh(
                new THREE.SphereGeometry(0.055, 10, 8),
                new THREE.MeshBasicMaterial({ color: branch.color, transparent: true, opacity: 0.78 })
            );
            subNode.position.copy(subEnd);
            network.add(subNode);
            subNodes.push(subNode);
            branchObjects.push({ tube, material: tube.material, subLine, curve });
        });

        // Small ambient points are scattered in a rectangular field, not arranged in rings.
        const dustCount = smallScreen ? 70 : 125;
        const dustPositions = new Float32Array(dustCount * 3);
        for (let i = 0; i < dustCount; i += 1) {
            dustPositions[i * 3] = (Math.random() - 0.5) * 8;
            dustPositions[i * 3 + 1] = (Math.random() - 0.5) * 5.6;
            dustPositions[i * 3 + 2] = (Math.random() - 0.5) * 2.4 - 1;
        }
        const dustGeometry = new THREE.BufferGeometry();
        dustGeometry.setAttribute('position', new THREE.BufferAttribute(dustPositions, 3));
        const dust = new THREE.Points(
            dustGeometry,
            new THREE.PointsMaterial({ color: 0x78aebe, size: 0.025, transparent: true, opacity: 0.48 })
        );
        scene.add(dust);

        controls = new OrbitControls(camera, canvas);
        controls.enableDamping = true;
        controls.dampingFactor = 0.065;
        controls.enablePan = false;
        controls.enableZoom = false;
        controls.rotateSpeed = 0.48;
        controls.minPolarAngle = 0.86;
        controls.maxPolarAngle = 2.28;

        const resize = () => {
            const rect = stage.getBoundingClientRect();
            const width = Math.max(1, rect.width);
            const height = Math.max(1, rect.height);
            camera.aspect = width / height;
            camera.fov = width < 420 ? 44 : 38;
            camera.updateProjectionMatrix();
            renderer.setSize(width, height, false);
        };
        const resizeObserver = new ResizeObserver(resize);
        resizeObserver.observe(stage);
        resize();

        const setSelected = index => {
            selectedIndex = Number.isInteger(index) && index >= 0 && index < branches.length ? index : -1;
            nodeMeshes.forEach(({ group, sphere, halo, material, index: nodeIndex }) => {
                const active = selectedIndex === -1 || selectedIndex === nodeIndex;
                material.emissiveIntensity = active ? (selectedIndex === nodeIndex ? 0.7 : 0.24) : 0.06;
                halo.material.opacity = active ? (selectedIndex === nodeIndex ? 0.3 : 0.1) : 0.025;
                group.scale.setScalar(selectedIndex === nodeIndex ? 1.35 : (active ? 1 : 0.82));
                sphere.material.color.setHex(active ? branches[nodeIndex].color : 0xb8cbd2);
            });
            branchObjects.forEach(({ material, subLine }, branchIndex) => {
                const active = selectedIndex === -1 || selectedIndex === branchIndex;
                material.opacity = active ? (selectedIndex === branchIndex ? 1 : 0.58) : 0.09;
                subLine.material.opacity = active ? 0.28 : 0.025;
            });
            movingPackets.forEach(({ mesh }, packetIndex) => {
                mesh.visible = selectedIndex === -1 || selectedIndex === packetIndex;
            });
            chips.forEach(chip => {
                const active = Number(chip.dataset.talentIndex) === selectedIndex;
                chip.setAttribute('aria-pressed', String(active));
            });
            const selectedChip = chips.find(chip => Number(chip.dataset.talentIndex) === selectedIndex);
            if (selectedLabel) selectedLabel.textContent = selectedChip ? selectedChip.textContent.trim() : 'Pilih salah satu bidang minat';
            if (status) status.textContent = selectedIndex === -1 ? 'NETWORK ACTIVE' : 'FIELD ' + String(selectedIndex + 1).padStart(2, '0') + ' SELECTED';
        };

        chips.forEach(chip => {
            chip.addEventListener('click', () => {
                const index = Number(chip.dataset.talentIndex);
                setSelected(selectedIndex === index ? -1 : index);
            });
        });

        const toggleFlow = () => {
            flowActive = !flowActive;
            if (flowButton) {
                flowButton.setAttribute('aria-pressed', String(flowActive));
                flowButton.innerHTML = flowActive
                    ? '<span aria-hidden="true">✦</span> ALIRAN AKTIF'
                    : '<span aria-hidden="true">⌁</span> AKTIFKAN ALIRAN';
            }
            if (status) status.textContent = flowActive ? 'SIGNALS FLOWING' : (selectedIndex < 0 ? 'NETWORK ACTIVE' : 'FIELD SELECTED');
        };
        flowButton?.addEventListener('click', toggleFlow);

        const raycaster = new THREE.Raycaster();
        const pointer = new THREE.Vector2();
        let down = null;
        canvas.addEventListener('pointerdown', event => { down = { x: event.clientX, y: event.clientY }; });
        canvas.addEventListener('pointerup', event => {
            if (!down) return;
            const distance = Math.hypot(event.clientX - down.x, event.clientY - down.y);
            down = null;
            if (distance > 6) return;
            const rect = canvas.getBoundingClientRect();
            pointer.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
            pointer.y = -(((event.clientY - rect.top) / rect.height) * 2 - 1);
            raycaster.setFromCamera(pointer, camera);
            const hit = raycaster.intersectObjects(nodeMeshes.map(node => node.sphere), false)[0];
            if (hit) {
                const target = nodeMeshes.find(node => node.sphere === hit.object);
                setSelected(selectedIndex === target.index ? -1 : target.index);
            }
        });
        canvas.addEventListener('pointermove', event => {
            const rect = canvas.getBoundingClientRect();
            const x = (event.clientX - rect.left) / rect.width - 0.5;
            const y = (event.clientY - rect.top) / rect.height - 0.5;
            keyLight.position.x = 2.8 + x * 1.5;
            keyLight.position.y = 3.2 - y * 1.2;
        });

        const clock = new THREE.Clock();
        const animate = () => {
            if (!visible) return;
            frame = window.requestAnimationFrame(animate);
            const delta = Math.min(clock.getDelta(), 0.04);
            const motion = reduceMotion ? 0 : delta;
            elapsed += motion * (flowActive ? 1.9 : 1);
            hub.rotation.y += motion * (flowActive ? 0.42 : 0.13);
            hub.rotation.x = Math.sin(elapsed * 0.42) * 0.12;
            hubShell.rotation.y -= motion * 0.08;
            hubShell.rotation.z += motion * 0.035;
            hub.scale.setScalar(1 + (reduceMotion ? 0 : Math.sin(elapsed * (flowActive ? 3.1 : 1.35)) * (flowActive ? 0.075 : 0.025)));
            dust.rotation.y += motion * 0.004;
            nodeMeshes.forEach(({ group, index }) => {
                group.position.y = branches[index].end[1] + (reduceMotion ? 0 : Math.sin(elapsed * 1.15 + index) * 0.035);
            });
            movingPackets.forEach(({ mesh, curve, offset }, index) => {
                const t = (elapsed * (flowActive ? 0.23 : 0.105) + offset) % 1;
                mesh.position.copy(curve.getPointAt(t));
                mesh.scale.setScalar(flowActive ? 1.35 : 1);
            });
            controls.update();
            renderer.render(scene, camera);
        };

        setSelected(-1);
        if (status) status.textContent = 'NETWORK ACTIVE';
        document.addEventListener('visibilitychange', () => {
            visible = !document.hidden;
            if (visible) {
                clock.start();
                animate();
            } else {
                window.cancelAnimationFrame(frame);
                clock.stop();
            }
        });
        animate();
    } catch (error) {
        console.error('MINBAK talent network failed:', error);
        fallback('NETWORK PREVIEW');
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
    initTalentNetwork(reduceMotion);
    initHeaderAndScrollEffects();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMinbakInteractions, { once: true });
} else {
    initMinbakInteractions();
}
