<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '../Components/Landing/Navbar.vue';
import TimelineSection from '../Components/Journey/TimelineSection.vue';
import Footer from '../Components/Landing/Footer.vue';
</script>

<template>
    <Head title="Perjalanan" />

    <div class="min-h-screen bg-white">
        <Navbar />
        <main class="bg-white">
            <TimelineSection />
        </main>
        <Footer />
    </div>
</template>
