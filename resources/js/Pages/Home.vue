<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '../Components/Landing/Navbar.vue';
import Hero from '../Components/Landing/Hero.vue';
import AboutStory from '../Components/Landing/AboutStory.vue';
import Features from '../Components/Landing/Features.vue';
import ProgramSection from '../Components/Landing/ProgramSection.vue';
import Footer from '../Components/Landing/Footer.vue';
</script>

<template>
    <Head title="Home" />

    <div class="min-h-screen bg-white">
        <Navbar />
        <main>
            <Hero />
            <AboutStory />
            <Features />
            <ProgramSection />
        </main>
        <Footer />
    </div>
</template>
