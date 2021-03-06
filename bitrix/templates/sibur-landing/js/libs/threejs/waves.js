import * as THREE from '/bitrix/templates/sibur-landing/js/libs/threejs/three.module.js';

if ($('div').hasClass('waves')) {
    const SEPARATION = 100,
        AMOUNTX = 80,
        AMOUNTY = 20;

    let container;
    let camera, scene, renderer;

    let particles, count = 0;

    let mouseX = 85,
        mouseY = -342;

    // let mouseX = 0, mouseY = 0;

    let windowHalfX = window.innerWidth / 2;
    let windowHalfY = window.innerHeight / 2;

    init();
    animate();



    function init() {

        container = document.querySelector('.waves');

        camera = new THREE.PerspectiveCamera(120, window.innerWidth / window.innerHeight, 1, 10000);
        camera.position.z = 1000;

        scene = new THREE.Scene();

        //

        const numParticles = AMOUNTX * AMOUNTY;

        const positions = new Float32Array(numParticles * 3);
        const scales = new Float32Array(numParticles);

        let i = 0,
            j = 0;

        for (let ix = 0; ix < AMOUNTX; ix++) {

            for (let iy = 0; iy < AMOUNTY; iy++) {

                positions[i] = ix * SEPARATION - ((AMOUNTX * SEPARATION) / 2); // x
                positions[i + 1] = 0; // y
                positions[i + 2] = iy * SEPARATION - ((AMOUNTY * SEPARATION) / 2); // z

                scales[j] = 1;

                i += 3;
                j++;

            }

        }

        const geometry = new THREE.BufferGeometry();
        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        geometry.setAttribute('scale', new THREE.BufferAttribute(scales, 1));

        //

        particles = new THREE.Points(geometry);
        scene.add(particles);

        //

        renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: true
        });
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(window.innerWidth, window.innerHeight);
        container.appendChild(renderer.domElement);

        scene.background = null;

        container.style.touchAction = 'none';

        //

        window.addEventListener('resize', onWindowResize);

    }

    function onWindowResize() {

        windowHalfX = window.innerWidth / 2;
        windowHalfY = window.innerHeight / 2;

        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();

        renderer.setSize(window.innerWidth, window.innerHeight);

    }

    //

    function animate() {

        requestAnimationFrame(animate);

        render();

    }

    function render() {

        camera.position.x += (mouseX - camera.position.x) * .05;
        camera.position.y += (-mouseY - camera.position.y) * .05;
        camera.lookAt(scene.position);



        const positions = particles.geometry.attributes.position.array;
        const scales = particles.geometry.attributes.scale.array;

        let i = 0,
            j = 0;

        for (let ix = 0; ix < AMOUNTX; ix++) {

            for (let iy = 0; iy < AMOUNTY; iy++) {

                positions[i + 1] = (Math.sin((ix + count) * 0.3) * 50) +
                    (Math.sin((iy + count) * 0.5) * 50);

                scales[j] = (Math.sin((ix + count) * 2) + 1) * 20 +
                    (Math.sin((iy + count) * 0.5) + 1);

                i += 3;
                j++;

            }

        }

        particles.geometry.attributes.position.needsUpdate = true;
        particles.geometry.attributes.scale.needsUpdate = true;

        renderer.render(scene, camera);

        count += 0.04;

    }
}