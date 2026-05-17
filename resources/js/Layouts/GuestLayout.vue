<script setup>
import Navbar from '../Components/Landing/Navbar.vue';
import Footer from '../Components/Landing/Footer.vue';
</script>

<template>
    <div class="min-h-screen bg-white">
        <Navbar />
        <main>
            <slot />
        </main>
        <Footer />
    </div>
</template>
