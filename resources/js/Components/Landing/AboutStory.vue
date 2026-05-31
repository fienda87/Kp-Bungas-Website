<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const storyImage = '/images/impact/about-story-image.png';
const statIconSprout = '/images/impact/icon-sprout.svg';
const statIconPeople = '/images/impact/icon-people.svg';
const statIconHeart = '/images/impact/icon-heart.svg';

const stats = [
    { icon: statIconSprout, value: '2019', label: 'Dimulai' },
    { icon: statIconPeople, value: '500+', label: 'Anggota' },
    { icon: statIconHeart, value: '100%', label: 'Komunitas' },
];

const paragraphs = [
    'Berawal dari tahun 2019, Kampung Bungas adalah transformasi luar biasa dari daerah perkotaan yang padat menjadi oasis hijau yang produktif di jantung Balikpapan.',
    'Kami percaya bahwa setiap rumah tangga dapat berkontribusi untuk keberlanjutan lingkungan. Melalui pertanian perkotaan, hidroponik, dan pengelolaan sampah berbasis komunitas, kami memberdayakan masyarakat untuk merawat bumi dari halaman mereka sendiri.',
    'Hari ini, Kampung Bungas bukan hanya tentang menanam tanaman-ini tentang menumbuhkan komunitas yang peduli, berkelanjutan, dan penuh harapan untuk masa depan yang lebih hijau.',
];

const sectionRef = ref(null);
const imageRef = ref(null);
const textContainerRef = ref(null);
const statsRef = ref([]);

let st = null; // ScrollTrigger instance

onMounted(() => {
    // Wait a brief moment to ensure DOM layout is settled before calculating ScrollTrigger positions
    setTimeout(() => {
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: sectionRef.value,
                start: 'top 80%', // Titik tengah agar tidak terlalu cepat terpicu
                toggleActions: 'play none none reverse'
            }
        });

        // 1. Image slides in from the left
        tl.fromTo(imageRef.value, 
            { x: -40, opacity: 0 },
            { x: 0, opacity: 1, duration: 0.65, ease: 'power2.out' }
        );

        // 2. Text slides up from the bottom (staggered children)
        tl.fromTo(textContainerRef.value.children, 
            { y: 25, opacity: 0 },
            { y: 0, opacity: 1, duration: 0.5, stagger: 0.08, ease: 'power2.out' },
            "-=0.35" 
        );

        // 3. Stats cards pop up one by one
        if (statsRef.value.length > 0) {
            tl.fromTo(statsRef.value,
                { y: 20, opacity: 0, scale: 0.95 },
                { y: 0, opacity: 1, scale: 1, duration: 0.45, stagger: 0.08, ease: 'back.out(1.2)' },
                "-=0.2"
            );
        }
        
        st = tl.scrollTrigger;
    }, 100);
});

onUnmounted(() => {
    if (st) {
        st.kill();
    }
});
</script>

<template>
    <section ref="sectionRef" id="tentang" class="bg-gradient-to-b from-white to-[#f0fdf4] py-20 sm:py-24 overflow-hidden">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-center">
                <span class="rounded-full bg-[#dcfce7] px-4 py-1 text-sm font-semibold text-[#008236]">Tentang Kami</span>
            </div>

            <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-12">
                <div ref="imageRef" class="order-2 mx-auto w-full max-w-[584px] lg:order-1 opacity-0">
                    <img
                        :src="storyImage"
                        alt="Warga Kampung Bungas bergotong royong merawat area hijau"
                        class="h-auto w-full rounded-2xl object-cover shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)] hover:shadow-[0_30px_60px_-12px_rgba(0,0,0,0.3)] hover:scale-[1.015] active:scale-[0.99] transition-all duration-500 transform cursor-pointer"
                        loading="lazy"
                    />
                </div>

                <div class="order-1 mx-auto w-full max-w-[584px] lg:order-2">
                    <div ref="textContainerRef">
                        <h2 class="text-4xl font-bold leading-tight text-[#101828] sm:text-5xl opacity-0">Dari Beton ke Hijau</h2>
                        <p class="mt-4 text-lg leading-[1.625] text-[#4a5565] opacity-0">{{ paragraphs[0] }}</p>

                        <div class="mt-6 space-y-4 opacity-0">
                            <p
                                v-for="(paragraph, idx) in paragraphs.slice(1)"
                                :key="idx"
                                class="text-base leading-[1.625] text-[#364153]"
                            >
                                {{ paragraph }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div
                            v-for="(stat, idx) in stats"
                            :key="stat.label"
                            ref="statsRef"
                            class="rounded-[14px] bg-white px-4 pb-5 pt-4 text-center shadow-[0_4px_6px_rgba(0,0,0,0.05),0_2px_4px_rgba(0,0,0,0.05)] border border-slate-100 hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300 transform active:scale-95 select-none opacity-0"
                        >
                            <img :src="stat.icon" alt="" class="mx-auto h-8 w-8" aria-hidden="true" loading="lazy" />
                            <p class="mt-2 text-2xl font-bold leading-8 text-[#101828]">{{ stat.value }}</p>
                            <p class="mt-1 text-sm leading-5 text-[#4a5565]">{{ stat.label }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
