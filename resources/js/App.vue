<template>
    <div class="min-h-screen flex flex-col font-sans antialiased">
        <Navbar />
        <main class="flex-1">
            <Hero />
            <About />
            <Programs />
            <Activities />
            <Contact />
        </main>
        <Footer />
    </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue';
import Hero from './components/Hero.vue';
import About from './components/About.vue';
import Programs from './components/Programs.vue';
import Activities from './components/Activities.vue';
import Contact from './components/Contact.vue';
import Footer from './components/Footer.vue';
</script>
