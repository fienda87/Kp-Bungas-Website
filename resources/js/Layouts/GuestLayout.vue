<script setup>
import Navbar from '../Components/Landing/Navbar.vue';
import Footer from '../Components/Landing/Footer.vue';
import ErrorBoundary from '../Components/ErrorBoundary.vue';
</script>

<template>
    <div class="min-h-screen bg-white">
        <Navbar />
        <main>
            <ErrorBoundary>
                <slot />
            </ErrorBoundary>
        </main>
        <Footer />
    </div>
</template>
