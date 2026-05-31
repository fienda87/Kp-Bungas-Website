<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const heroImage = '/images/impact/hero-image.png';

const bgImageRef = ref(null);
const heroSectionRef = ref(null);
let gsapCtx = null;

onMounted(() => {
    // Delay sedikit agar Lenis siap
    setTimeout(() => {
        gsapCtx = gsap.context(() => {
            // Text reveal animation
            gsap.from('.hero-element', {
                y: 60,
                opacity: 0,
                duration: 1.2,
                stagger: 0.2,
                ease: 'power3.out',
                delay: 0.1
            });

            // Parallax image animation (Bergerak lebih lambat dari scroll)
            gsap.to(bgImageRef.value, {
                yPercent: 30, // Geser 30% ke bawah
                ease: 'none',
                scrollTrigger: {
                    trigger: heroSectionRef.value,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: true // Sinkronisasi penuh dengan scroll
                }
            });
        }, heroSectionRef.value);
    }, 100);
});

onUnmounted(() => {
    if (gsapCtx) gsapCtx.revert();
});
</script>

<template>
    <section ref="heroSectionRef" id="home" class="relative isolate min-h-[calc(100vh-5rem)] overflow-hidden bg-black">
        <!-- Scale dan top offset agar saat diparallax tidak ada ruang putih -->
        <img
            ref="bgImageRef"
            :src="heroImage"
            alt="Kebun Kampung Bungas"
            class="absolute inset-0 h-[130%] w-full -top-[15%] object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/80" />

        <div class="relative mx-auto flex min-h-[calc(100vh-5rem)] w-full max-w-7xl items-center px-4 py-16 text-white sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <p class="hero-element mx-auto mb-6 inline-flex items-center rounded-full bg-white/20 px-4 py-2 text-sm font-medium backdrop-blur">
                    Balikpapan, Indonesia
                </p>

                <h1 class="hero-element text-4xl font-extrabold leading-tight sm:text-5xl lg:text-7xl">
                    Merawat Pertiwi dari
                    <span class="block text-emerald-400">Halaman Rumah Sendiri</span>
                </h1>

                <p class="hero-element mx-auto mt-6 max-w-3xl text-base leading-relaxed text-slate-200 sm:text-lg lg:text-2xl">
                    Kampung hijau berbasis komunitas yang mengubah daerah perkotaan menjadi ruang hijau produktif melalui
                    pertanian perkotaan dan hidroponik.
                </p>

                <div class="hero-element mt-10 flex flex-wrap items-center justify-center gap-4">
                    <Link
                        href="/program"
                        class="rounded-full bg-emerald-600 px-8 py-3 text-base font-semibold text-white shadow-[0_0_20px_rgba(5,150,105,0.4)] transition hover:bg-emerald-700 hover:-translate-y-1 hover:shadow-[0_0_25px_rgba(5,150,105,0.6)] active:scale-95"
                    >
                        Jelajahi Kampung
                    </Link>
                    <a
                        id="kunjungi"
                        href="#footer"
                        class="rounded-full border-2 border-white/60 bg-white/10 px-8 py-3 text-base font-semibold text-white backdrop-blur transition hover:bg-white/20 hover:-translate-y-1 active:scale-95"
                    >
                        Kunjungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
