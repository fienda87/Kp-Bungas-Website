<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../Layouts/GuestLayout.vue';
import Hero from '../Components/Landing/Hero.vue';
import AboutStory from '../Components/Landing/AboutStory.vue';
import LatestNews from '../Components/Landing/LatestNews.vue';
import ProgramSection from '../Components/Landing/ProgramSection.vue';

defineProps({
    featuredNews: Object,
    programs: Array,
});
</script>

<template>
    <Head>
        <title>Beranda - Kampung Bungas</title>
        <meta head-key="description" name="description" content="Selamat datang di Kampung Bungas. Temukan informasi terbaru, program, dan galeri kegiatan kami." />
    </Head>

    <GuestLayout>
        <Hero />
        <AboutStory />
        <ProgramSection :programs="programs" />
        <LatestNews :news="featuredNews?.data || []" />
    </GuestLayout>
</template>
