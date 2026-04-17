<script setup>
import ImpactStatCard from './ImpactStatCard.vue';
import ChallengeCard from './ChallengeCard.vue';

const stats = [
    {
        id: 'lubang',
        icon: '/images/impact/stat-lubang.svg',
        iconGradient: 'linear-gradient(135deg, rgb(0, 201, 80) 0%, rgb(0, 153, 102) 100%)',
        value: '5,000+',
        label: 'Lubang Tanam',
    },
    {
        id: 'ruang',
        icon: '/images/impact/stat-ruang.svg',
        iconGradient: 'linear-gradient(135deg, rgb(43, 127, 255) 0%, rgb(0, 146, 184) 100%)',
        value: '2,500 m²',
        label: 'Ruang Hijau',
    },
    {
        id: 'anggota',
        icon: '/images/impact/stat-anggota.svg',
        iconGradient: 'linear-gradient(135deg, rgb(173, 70, 255) 0%, rgb(230, 0, 118) 100%)',
        value: '500+',
        label: 'Anggota Aktif',
    },
    {
        id: 'juara',
        icon: '/images/impact/stat-juara.svg',
        iconGradient: 'linear-gradient(135deg, rgb(254, 154, 0) 0%, rgb(245, 73, 0) 100%)',
        value: '2025',
        label: 'Juara 1 lomba Clean, Green, and',
        description: 'Healthy (CGH) tingkat Kota Balikpapan',
    },
];

const environmentalImpact = [
    '🌿 Mengurangi emisi karbon lokal sebesar 30%',
    '♻️ Mendaur ulang 2 ton sampah organik per bulan',
    '💧 Menghemat 40% penggunaan air melalui hidroponik',
    '🦋 Meningkatkan biodiversitas lokal',
];

const socialImpact = [
    '👥 Mempererat hubungan antar warga',
    '🎓 Melatih 200+ peserta dalam workshop',
    '🥗 Menyediakan sayuran segar untuk 100+ keluarga',
    '💼 Menciptakan peluang usaha mikro lokal',
];

const challenges = [
    {
        id: 'partisipasi',
        icon: '/images/impact/challenge-partisipasi.svg',
        title: 'Partisipasi Masyarakat',
        challenge: 'Awalnya sulit mengajak warga untuk aktif terlibat dalam kegiatan hijau.',
        solution: 'Mengadakan acara komunitas rutin, workshop gratis, dan program reward untuk partisipan aktif.',
    },
    {
        id: 'keuangan',
        icon: '/images/impact/challenge-keuangan.svg',
        title: 'Masalah Keuangan',
        challenge: 'Terbatasnya dana untuk infrastruktur dan peralatan pertanian modern.',
        solution: 'Kerjasama dengan CSR perusahaan, crowdfunding komunitas, dan penjualan hasil panen organik.',
    },
    {
        id: 'pemuda',
        icon: '/images/impact/challenge-pemuda.svg',
        title: 'Keterlibatan Pemuda',
        challenge: 'Generasi muda kurang tertarik dengan kegiatan pertanian dan lingkungan.',
        solution: 'Program edukasi kreatif, media sosial aktif, dan event yang menarik untuk kalangan muda.',
    },
];
</script>

<template>
    <section id="dampak" class="bg-linear-to-b from-[#f0fdf4] to-white px-4 pb-20 pt-16 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-7xl">
            <div class="mx-auto max-w-4xl text-center">
                <span class="inline-flex rounded-full bg-[#dcfce7] px-4 py-1 text-sm font-semibold text-[#008236]">Dampak Kami</span>
                <h1 class="mt-3 text-6xl font-bold leading-12 text-[#101828]">Pencapaian yang Membanggakan</h1>
                <p class="mx-auto mt-4 max-w-2xl text-lg leading-7 text-[#4a5565]">
                    Berbagai program inovatif untuk menciptakan komunitas yang berkelanjutan dan ramah lingkungan
                </p>
            </div>

            <div class="mt-16 grid gap-8 md:grid-cols-2 xl:grid-cols-4">
                <ImpactStatCard
                    v-for="item in stats"
                    :key="item.id"
                    :icon="item.icon"
                    :icon-gradient="item.iconGradient"
                    :value="item.value"
                    :label="item.label"
                    :description="item.description"
                />
            </div>

            <div class="mt-8 grid gap-8 lg:grid-cols-2">
                <article class="rounded-2xl bg-linear-to-br from-[#00c950] to-[#009966] p-8 text-white">
                    <h2 class="text-4xl font-bold leading-8">Dampak Lingkungan</h2>
                    <ul class="mt-4 space-y-2 text-base leading-6">
                        <li v-for="point in environmentalImpact" :key="point">{{ point }}</li>
                    </ul>
                </article>
                <article class="rounded-2xl bg-linear-to-br from-[#2b7fff] to-[#0092b8] p-8 text-white">
                    <h2 class="text-4xl font-bold leading-8">Dampak Sosial</h2>
                    <ul class="mt-4 space-y-2 text-base leading-6">
                        <li v-for="point in socialImpact" :key="point">{{ point }}</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-white px-4 pb-20 pt-10 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-7xl">
            <div class="mx-auto max-w-4xl text-center">
                <span class="inline-flex rounded-full bg-[#fef3c6] px-4 py-1 text-sm font-semibold text-[#bb4d00]">Tantangan &amp; Solusi</span>
                <h2 class="mt-3 text-6xl font-bold leading-12 text-[#101828]">Mengatasi Hambatan Bersama</h2>
                <p class="mx-auto mt-4 max-w-2xl text-lg leading-7 text-[#4a5565]">Setiap tantangan adalah kesempatan untuk tumbuh dan berinovasi</p>
            </div>

            <div class="mt-14 grid gap-8 lg:grid-cols-3">
                <ChallengeCard
                    v-for="item in challenges"
                    :key="item.id"
                    :icon="item.icon"
                    :title="item.title"
                    :challenge="item.challenge"
                    :solution="item.solution"
                />
            </div>

            <div class="mx-auto mt-10 max-w-4xl rounded-2xl bg-[#dcfce7] px-8 py-6 text-center text-[#1e2939]">
                <p class="text-xl leading-7">
                    <span class="font-bold text-[#008236]">Kunci kesuksesan kami:</span>
                    Kolaborasi, kreativitas, dan komitmen bersama untuk masa depan yang lebih hijau.
                </p>
            </div>
        </div>
    </section>
</template>
