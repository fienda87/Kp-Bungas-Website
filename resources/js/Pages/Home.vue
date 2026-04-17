<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '../Components/Landing/Navbar.vue';
import Hero from '../Components/Landing/Hero.vue';
import Features from '../Components/Landing/Features.vue';
import Footer from '../Components/Landing/Footer.vue';
</script>

<template>
    <Head title="Home" />

    <div class="min-h-screen bg-white">
        <Navbar />
        <main>
            <Hero />
            <Features />
        </main>
        <Footer />
    </div>
</template>
