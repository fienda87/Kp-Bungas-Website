<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const originalSlides = [
    { 
        image: '/images/impact/hero-image.png', 
        alt: 'Kebun Kampung Bungas',
        tagline: 'Balikpapan, Indonesia',
        titlePart1: 'Merawat Pertiwi dari',
        titleHighlight: 'Halaman Rumah Sendiri',
        description: 'Kampung hijau berbasis komunitas yang mengubah daerah perkotaan menjadi ruang hijau produktif melalui pertanian perkotaan dan hidroponik.',
        ctaText: 'Jelajahi Kampung',
        ctaLink: '/program'
    },
    { 
        image: '/images/program/berkebun.jpg', 
        alt: 'Kegiatan Berkebun Komunitas',
        tagline: 'Aktivitas Warga Kampung',
        titlePart1: 'Membangun Kebersamaan',
        titleHighlight: 'Lewat Gotong Royong',
        description: 'Setiap sudut RT di Kampung Bungas dikelola bersama oleh warga untuk menghasilkan bahan pangan sehat dan lingkungan yang asri.',
        ctaText: 'Lihat Perjalanan',
        ctaLink: '/perjalanan'
    },
    { 
        image: '/images/program/hidroponik.jpg', 
        alt: 'Pertanian Hidroponik Modern',
        tagline: 'Teknologi Pertanian Kota',
        titlePart1: 'Pertanian Hidroponik',
        titleHighlight: 'Modern & Berkelanjutan',
        description: 'Memaksimalkan lahan sempit perkotaan dengan sistem hidroponik efisien, menghasilkan sayuran segar berkualitas tinggi bebas pestisida.',
        ctaText: 'Program Hidroponik',
        ctaLink: '/program'
    },
    { 
        image: '/images/program/pendidikan.jpg', 
        alt: 'Edukasi Pertanian Perkotaan',
        tagline: 'Edukasi Warga & Pemuda',
        titlePart1: 'Menanam Benih Kebaikan',
        titleHighlight: 'Bagi Generasi Penerus',
        description: 'Menjadi pusat pembelajaran lingkungan bagi anak-anak dan pemuda untuk menumbuhkan cinta lingkungan sejak usia dini.',
        ctaText: 'Dampak Sosial Kami',
        ctaLink: '/dampak'
    },
    { 
        image: '/images/program/sampah.jpg', 
        alt: 'Pengelolaan Sampah Terpadu',
        tagline: 'Eco-Friendly & Zero Waste',
        titlePart1: 'Mengolah Sampah Menjadi',
        titleHighlight: 'Berkah Lingkungan',
        description: 'Sistem pengelolaan sampah organik terpadu yang didaur ulang menjadi pupuk alami penyubur aneka tanaman buah dan sayur.',
        ctaText: 'Dampak Lingkungan',
        ctaLink: '/dampak'
    },
];

// Load slides dynamically from global shared props settings if available
const slides = (() => {
    try {
        const rawSlidesSetting = page.props.settings?.hero_slides;
        if (rawSlidesSetting) {
            return JSON.parse(rawSlidesSetting);
        }
    } catch (e) {
        console.error("Error parsing dynamic hero slides", e);
    }
    return originalSlides;
})();

// Append a duplicate of the first slide at the end for seamless infinite scrolling
const displaySlides = [
    ...slides,
    slides[0]
];

const currentSlide = ref(0);
const activeTextSlide = ref(0);
const transitioning = ref(true);
const isSliding = ref(false);
const textVisible = ref(true);
const slideTransitionDuration = 1000;
const snapDelay = 50;
let intervalId = null;
let textSwapTimeout = null;
let slideTimeouts = [];

const scheduleSlideTimeout = (callback, delay) => {
    const timeoutId = setTimeout(() => {
        slideTimeouts = slideTimeouts.filter((id) => id !== timeoutId);
        callback();
    }, delay);

    slideTimeouts.push(timeoutId);
};

const startAutoplay = () => {
    stopAutoplay();
    intervalId = setInterval(() => {
        nextSlide();
    }, 5000);
};

const stopAutoplay = () => {
    if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
    }
};

const nextSlide = () => {
    if (!transitioning.value || isSliding.value) return;

    isSliding.value = true;

    currentSlide.value++;

    if (currentSlide.value === displaySlides.length - 1) {
        // Once we slide smoothly to the cloned slide (taking 1000ms), 
        // instantly snap back to slide 0 without any transition
        scheduleSlideTimeout(() => {
            transitioning.value = false;
            currentSlide.value = 0;
            
            // Re-enable transition in the next tick
            scheduleSlideTimeout(() => {
                transitioning.value = true;
                isSliding.value = false;
            }, snapDelay);
        }, slideTransitionDuration); // Matches transition-transform duration-1000
    } else {
        scheduleSlideTimeout(() => {
            isSliding.value = false;
        }, slideTransitionDuration);
    }
};

const prevSlide = () => {
    if (!transitioning.value || isSliding.value) return;

    isSliding.value = true;

    if (currentSlide.value === 0) {
        // Instantly jump to the cloned slide at the end without transition
        transitioning.value = false;
        currentSlide.value = displaySlides.length - 1;

        // In the next tick, slide smoothly backwards to the last real slide (index 4)
        scheduleSlideTimeout(() => {
            transitioning.value = true;
            currentSlide.value = slides.length - 1;
        }, snapDelay);

        scheduleSlideTimeout(() => {
            isSliding.value = false;
        }, slideTransitionDuration + snapDelay);
    } else {
        currentSlide.value--;
        scheduleSlideTimeout(() => {
            isSliding.value = false;
        }, slideTransitionDuration);
    }
};

const setSlide = (index) => {
    if (!transitioning.value || isSliding.value || index === (currentSlide.value % slides.length)) return;

    isSliding.value = true;
    currentSlide.value = index;
    startAutoplay();

    scheduleSlideTimeout(() => {
        isSliding.value = false;
    }, slideTransitionDuration);
};

const goToPreviousSlide = () => {
    prevSlide();
    startAutoplay();
};

const goToNextSlide = () => {
    nextSlide();
    startAutoplay();
};

// Smooth text swap watcher
watch(currentSlide, (newVal) => {
    const nextTextSlide = newVal % slides.length;

    if (nextTextSlide === activeTextSlide.value) {
        return;
    }

    if (textSwapTimeout) {
        clearTimeout(textSwapTimeout);
    }

    textVisible.value = false;
    textSwapTimeout = setTimeout(() => {
        activeTextSlide.value = nextTextSlide;
        textVisible.value = true;
        textSwapTimeout = null;
    }, 300); // 300ms matches smooth opacity transition
});

onMounted(() => {
    startAutoplay();
});

onUnmounted(() => {
    stopAutoplay();

    if (textSwapTimeout) {
        clearTimeout(textSwapTimeout);
    }

    slideTimeouts.forEach((timeoutId) => clearTimeout(timeoutId));
    slideTimeouts = [];
});
</script>

<template>
    <section id="home" class="relative isolate min-h-[calc(100vh-5rem)] overflow-hidden bg-slate-900">
        <!-- Horizontal Infinite Slide Showcase -->
        <div 
            class="absolute inset-0 z-0 flex"
            :class="transitioning ? 'transition-transform duration-1000 ease-in-out' : ''"
            :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
        >
            <div
                v-for="(slide, index) in displaySlides"
                :key="index"
                class="w-full h-full shrink-0 relative"
            >
                <img
                    :src="slide.image"
                    :alt="slide.alt"
                    class="absolute inset-0 h-full w-full object-cover"
                />
            </div>
        </div>
        
        <!-- Premium Gradient Overlay -->
        <div class="absolute inset-0 bg-linear-to-b from-black/60 via-black/45 to-black/75 z-[1]" />

        <!-- Side Navigation Arrows -->
        <button
            type="button"
            @click.stop="goToPreviousSlide"
            :disabled="isSliding"
            class="absolute left-4 top-1/2 z-30 -translate-y-1/2 rounded-full bg-black/20 p-3 text-white backdrop-blur-xs transition hover:bg-black/45 hover:scale-105 active:scale-95 disabled:cursor-wait disabled:opacity-45 disabled:hover:scale-100 md:left-8 cursor-pointer"
            aria-label="Previous Slide"
        >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button
            type="button"
            @click.stop="goToNextSlide"
            :disabled="isSliding"
            class="absolute right-4 top-1/2 z-30 -translate-y-1/2 rounded-full bg-black/20 p-3 text-white backdrop-blur-xs transition hover:bg-black/45 hover:scale-105 active:scale-95 disabled:cursor-wait disabled:opacity-45 disabled:hover:scale-100 md:right-8 cursor-pointer"
            aria-label="Next Slide"
        >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Slide Stretched Pill Indicators -->
        <div class="absolute bottom-6 left-1/2 z-30 flex -translate-x-1/2 gap-2">
            <button
                v-for="(_, index) in slides"
                :key="index"
                type="button"
                @click.stop="setSlide(index)"
                :disabled="isSliding"
                class="h-2 rounded-full transition-all duration-300 cursor-pointer disabled:cursor-wait disabled:opacity-60"
                :class="(currentSlide % slides.length) === index ? 'w-8 bg-emerald-400 shadow-sm' : 'w-2 bg-white/40 hover:bg-white/70'"
                :aria-label="`Slide ${index + 1}`"
            />
        </div>

        <div class="relative z-10 mx-auto flex min-h-[calc(100vh-5rem)] w-full max-w-7xl items-center px-4 py-16 text-white sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <!-- Staggered Animated Content Wrapper -->
                <div 
                    class="transition-all duration-300 ease-out transform"
                    :class="textVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                >
                    <p class="mx-auto mb-6 inline-flex items-center rounded-full bg-white/20 px-4 py-2 text-sm font-medium backdrop-blur">
                        {{ displaySlides[activeTextSlide].tagline }}
                    </p>

                    <h1 class="text-4xl font-extrabold leading-tight sm:text-5xl lg:text-7xl">
                        {{ displaySlides[activeTextSlide].titlePart1 }}
                        <span class="block text-emerald-400 mt-2">{{ displaySlides[activeTextSlide].titleHighlight }}</span>
                    </h1>

                    <p class="mx-auto mt-6 max-w-3xl text-base leading-relaxed text-slate-200 sm:text-lg lg:text-xl">
                        {{ displaySlides[activeTextSlide].description }}
                    </p>

                    <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                        <Link
                            :href="displaySlides[activeTextSlide].ctaLink"
                            class="rounded-full bg-emerald-600 px-8 py-3 text-base font-semibold text-white shadow-lg shadow-black/25 transition-all duration-200 hover:bg-emerald-700 hover:scale-105 active:scale-95 cursor-pointer"
                        >
                            {{ displaySlides[activeTextSlide].ctaText }}
                        </Link>
                        <a
                            id="kunjungi"
                            href="#footer"
                            class="rounded-full border-2 border-white/60 bg-white/20 px-8 py-3 text-base font-semibold text-white backdrop-blur transition-all duration-200 hover:bg-white/30 hover:scale-105 active:scale-95"
                        >
                            Kunjungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
