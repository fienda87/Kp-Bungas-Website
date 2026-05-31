<script setup>
import { ref } from 'vue';
import { useIntersectionObserver } from '@vueuse/core';

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
const isVisible = ref(false);

useIntersectionObserver(
    sectionRef,
    ([{ isIntersecting }]) => {
        if (isIntersecting) {
            isVisible.value = true;
        }
    },
    { threshold: 0.1 }
);
</script>

<template>
    <section ref="sectionRef" id="tentang" class="bg-gradient-to-b from-white to-primary-extralight py-20 sm:py-24 dark:from-gray-900 dark:to-gray-800">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div 
                class="mb-8 flex justify-center transition-all duration-700 ease-out"
                :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'"
            >
                <span class="rounded-full bg-primary/20 px-4 py-1 text-sm font-semibold text-primary-dark dark:bg-primary-light/20 dark:text-primary-light">Tentang Kami</span>
            </div>

            <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-12">
                <div 
                    class="order-2 mx-auto w-full max-w-[584px] lg:order-1 transition-all duration-1000 delay-200 ease-out"
                    :class="isVisible ? 'translate-x-0 opacity-100' : '-translate-x-10 opacity-0'"
                >
                    <img
                        :src="storyImage"
                        alt="Warga Kampung Bungas bergotong royong merawat area hijau"
                        class="h-auto w-full rounded-2xl object-cover shadow-2xl transition-transform duration-500 hover:scale-[1.02]"
                        loading="lazy"
                    />
                </div>

                <div 
                    class="order-1 mx-auto w-full max-w-[584px] lg:order-2 transition-all duration-1000 delay-300 ease-out"
                    :class="isVisible ? 'translate-x-0 opacity-100' : 'translate-x-10 opacity-0'"
                >
                    <h2 class="text-4xl font-bold leading-tight text-gray-900 sm:text-5xl dark:text-white">Dari Beton ke Hijau</h2>
                    <p class="mt-4 text-lg leading-relaxed text-gray-600 dark:text-gray-300">{{ paragraphs[0] }}</p>

                    <div class="mt-6 space-y-4">
                        <p
                            v-for="(paragraph, idx) in paragraphs.slice(1)"
                            :key="idx"
                            class="text-base leading-relaxed text-gray-600 dark:text-gray-400"
                        >
                            {{ paragraph }}
                        </p>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div
                            v-for="(stat, index) in stats"
                            :key="stat.label"
                            class="rounded-2xl bg-white px-4 pb-5 pt-4 text-center shadow-md transition-all duration-500 hover:-translate-y-2 hover:shadow-xl dark:bg-gray-800"
                            :style="`transition-delay: ${400 + index * 100}ms;`"
                            :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'"
                        >
                            <img :src="stat.icon" alt="" class="mx-auto h-8 w-8 dark:invert" aria-hidden="true" loading="lazy" />
                            <p class="mt-2 text-2xl font-bold leading-8 text-gray-900 dark:text-white">{{ stat.value }}</p>
                            <p class="mt-1 text-sm font-medium leading-5 text-gray-500 dark:text-gray-400">{{ stat.label }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
