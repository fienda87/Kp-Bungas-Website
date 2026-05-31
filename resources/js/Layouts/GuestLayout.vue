<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Navbar from '../Components/Landing/Navbar.vue';
import Footer from '../Components/Landing/Footer.vue';
import ErrorBoundary from '../Components/ErrorBoundary.vue';
import SkeletonLoader from '../Components/SkeletonLoader.vue';

// GSAP and Lenis
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';

gsap.registerPlugin(ScrollTrigger);

const isMounted = ref(false);
const isLoading = ref(false);

let removeStartEventListener = null;
let removeFinishEventListener = null;
let lenis = null;

onMounted(() => {
    // Mount effect from main
    setTimeout(() => {
        isMounted.value = true;
    }, 50);

    // Setup Lenis Smooth Scrolling
    lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        direction: 'vertical',
        gestureDirection: 'vertical',
        smooth: true,
        mouseMultiplier: 1,
        smoothTouch: false,
        touchMultiplier: 2,
        infinite: false,
    });

    lenis.on('scroll', ScrollTrigger.update);

    gsap.ticker.add((time) => {
        lenis.raf(time * 1000);
    });

    gsap.ticker.lagSmoothing(0);

    // Inertia events for Skeleton
    removeStartEventListener = router.on('start', () => isLoading.value = true);
    removeFinishEventListener = router.on('finish', () => isLoading.value = false);
});

onUnmounted(() => {
    if (removeStartEventListener) removeStartEventListener();
    if (removeFinishEventListener) removeFinishEventListener();
    if (lenis) {
        lenis.destroy();
        gsap.ticker.remove((time) => lenis.raf(time * 1000));
    }
});
</script>

<template>
    <div class="min-h-screen bg-white flex flex-col justify-between">
        <Navbar />
        <main 
            class="flex-1 transition-all duration-700 ease-out transform"
            :class="isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
        >
            <ErrorBoundary>
                <div :class="isLoading ? 'opacity-50 pointer-events-none transition-opacity duration-300' : 'transition-opacity duration-300'">
                    <slot />
                </div>
            </ErrorBoundary>
        </main>
        <Footer />
    </div>
</template>
