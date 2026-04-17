<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '../Components/Landing/Navbar.vue';
import ImpactSection from '../Components/Impact/ImpactSection.vue';
import Footer from '../Components/Landing/Footer.vue';
</script>

<template>
    <Head title="Dampak" />

    <div class="min-h-screen bg-white">
        <Navbar />
        <main class="bg-white">
            <ImpactSection />
        </main>
        <Footer />
    </div>
</template>
