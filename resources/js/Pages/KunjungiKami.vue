<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '../Components/Landing/Navbar.vue';
import ContactSection from '../Components/Contact/ContactSection.vue';
import Footer from '../Components/Landing/Footer.vue';
</script>

<template>
    <Head title="Kunjungi Kami" />

    <div class="min-h-screen bg-white">
        <Navbar />
        <main class="bg-white">
            <ContactSection />
        </main>
        <Footer />
    </div>
</template>
