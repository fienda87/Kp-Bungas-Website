<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../Layouts/GuestLayout.vue';
import Hero from '../Components/Landing/Hero.vue';
import AboutStory from '../Components/Landing/AboutStory.vue';
import Features from '../Components/Landing/Features.vue';
import ProgramSection from '../Components/Landing/ProgramSection.vue';
</script>

<template>
    <Head title="Home" />

    <GuestLayout>
        <Hero />
        <AboutStory />
        <Features />
        <ProgramSection />
    </GuestLayout>
</template>
