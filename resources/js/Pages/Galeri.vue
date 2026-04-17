<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '../Components/Landing/Navbar.vue';
import GallerySection from '../Components/Gallery/GallerySection.vue';
import Footer from '../Components/Landing/Footer.vue';
</script>

<template>
    <Head title="Galeri" />

    <div class="min-h-screen bg-white">
        <Navbar />
        <main class="bg-white">
            <GallerySection />
        </main>
        <Footer />
    </div>
</template>
