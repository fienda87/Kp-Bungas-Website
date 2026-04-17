<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '../Components/Landing/Navbar.vue';
import AboutStory from '../Components/Landing/AboutStory.vue';
import Footer from '../Components/Landing/Footer.vue';
</script>

<template>
    <Head title="Tentang Kami" />

    <div class="min-h-screen bg-white">
        <Navbar />
        <main>
            <AboutStory />
        </main>
        <Footer />
    </div>
</template>
