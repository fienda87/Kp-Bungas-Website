<script setup>
import TimelineItem from './TimelineItem.vue';

const milestones = [
    {
        id: '2019',
        year: '2019',
        title: 'Awal Perjalanan',
        description: 'Inisiatif dimulai oleh sekelompok warga yang peduli lingkungan. Memulai dengan lahan kecil dan semangat besar.',
        icon: '/images/journey/icon-2019.svg',
        iconGradient: 'linear-gradient(135deg, rgb(43, 127, 255) 0%, rgb(0, 184, 219) 100%)',
        position: 'left',
    },
    {
        id: '2021',
        year: '2021',
        title: 'Gerakan Lingkungan',
        description: 'Kesadaran pasca pandemi mendorong Tim Kreatif menggerakkan warga melalui pembersihan lingkungan, penataan ruang, dan perawatan kampung.',
        icon: '/images/journey/icon-2021.svg',
        iconGradient: 'linear-gradient(135deg, rgb(0, 201, 80) 0%, rgb(0, 188, 125) 100%)',
        position: 'right',
    },
    {
        id: '2023',
        year: '2023',
        title: 'Ekspansi & Gotong Royong',
        description: 'Sosialisasi semakin luas dan warga mulai membangun infrastruktur media tanam. Gotong royong dilakukan di setiap RT.',
        icon: '/images/journey/icon-2023.svg',
        iconGradient: 'linear-gradient(135deg, rgb(254, 154, 0) 0%, rgb(255, 105, 0) 100%)',
        position: 'left',
    },
    {
        id: '2024',
        year: '2024',
        title: 'Rutinitas & Panen',
        description: 'Kebun hidroponik berkembang dan kegiatan perawatan menjadi rutinitas warga. Hasil panen mulai dinikmati bersama.',
        icon: '/images/journey/icon-2024.svg',
        iconGradient: 'linear-gradient(135deg, rgb(173, 70, 255) 0%, rgb(246, 51, 154) 100%)',
        position: 'right',
    },
    {
        id: '2027',
        year: '2027',
        title: 'Visi Masa Depan',
        description: 'Kampung Bungas diarahkan menjadi kampung mandiri dengan ketahanan pangan, pengelolaan sampah berkelanjutan, dan pusat pembelajaran lingkungan kota.',
        icon: '/images/journey/icon-2019.svg',
        iconGradient: 'linear-gradient(135deg, rgb(0, 166, 62) 0%, rgb(0, 150, 137) 100%)',
        position: 'left',
    },
];
</script>

<template>
    <section id="perjalanan" class="relative bg-linear-to-b from-[#f0fdf4] to-white px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-7xl">
            <!-- Header -->
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex rounded-full bg-[#dcfce7] px-4 py-1 text-sm font-semibold text-[#008236]">
                    Perjalanan Kami
                </span>
                <h1 class="mt-3 text-4xl font-bold leading-12 text-[#101828] sm:text-5xl sm:leading-12">
                    Dari 2019 hingga Masa Depan
                </h1>
                <p class="mx-auto mt-4 text-base leading-7 text-[#4a5565] sm:text-lg sm:leading-8">
                    Menyaksikan transformasi dari mimpi menjadi kenyataan
                </p>
            </div>

            <!-- Timeline -->
            <div class="relative mx-auto mt-20 max-w-6xl">
                <!-- Vertical line -->
                <div class="absolute left-1/2 top-0 h-full w-1 -translate-x-1/2 bg-linear-to-b from-[#7bf1a8] via-[#00c950] to-[#008236]"></div>

                <!-- Timeline items -->
                <div class="relative space-y-12 pb-4 pt-4">
                    <TimelineItem
                        v-for="milestone in milestones"
                        :key="milestone.id"
                        :year="milestone.year"
                        :title="milestone.title"
                        :description="milestone.description"
                        :icon="milestone.icon"
                        :icon-gradient="milestone.iconGradient"
                        :position="milestone.position"
                    />
                </div>
            </div>
        </div>
    </section>
</template>
