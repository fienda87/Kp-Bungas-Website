<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '../Components/Landing/Navbar.vue';
import Footer from '../Components/Landing/Footer.vue';
import ProgramActivitiesSection from '../Components/Program/ProgramActivitiesSection.vue';
</script>

<template>
    <Head title="Program" />

    <div class="min-h-screen bg-white">
        <Navbar />
        <main class="bg-white">
            <ProgramActivitiesSection />
        </main>
        <Footer />
    </div>
</template>
