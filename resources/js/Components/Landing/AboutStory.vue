<script setup>
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
</script>

<template>
    <section id="tentang" class="bg-gradient-to-b from-white to-[#f0fdf4] py-20 sm:py-24">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-center">
                <span class="rounded-full bg-[#dcfce7] px-4 py-1 text-sm font-semibold text-[#008236]">Tentang Kami</span>
            </div>

            <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-12">
                <div class="order-2 mx-auto w-full max-w-[584px] lg:order-1">
                    <img
                        :src="storyImage"
                        alt="Warga Kampung Bungas bergotong royong merawat area hijau"
                        class="h-auto w-full rounded-2xl object-cover shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)]"
                    />
                </div>

                <div class="order-1 mx-auto w-full max-w-[584px] lg:order-2">
                    <h2 class="text-4xl font-bold leading-tight text-[#101828] sm:text-5xl">Dari Beton ke Hijau</h2>
                    <p class="mt-4 text-lg leading-[1.625] text-[#4a5565]">{{ paragraphs[0] }}</p>

                    <div class="mt-6 space-y-4">
                        <p
                            v-for="(paragraph, idx) in paragraphs.slice(1)"
                            :key="idx"
                            class="text-base leading-[1.625] text-[#364153]"
                        >
                            {{ paragraph }}
                        </p>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div
                            v-for="stat in stats"
                            :key="stat.label"
                            class="rounded-[14px] bg-white px-4 pb-5 pt-4 text-center shadow-[0_4px_6px_rgba(0,0,0,0.1),0_2px_4px_rgba(0,0,0,0.1)]"
                        >
                            <img :src="stat.icon" alt="" class="mx-auto h-8 w-8" aria-hidden="true" />
                            <p class="mt-2 text-2xl font-bold leading-8 text-[#101828]">{{ stat.value }}</p>
                            <p class="mt-1 text-sm leading-5 text-[#4a5565]">{{ stat.label }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
