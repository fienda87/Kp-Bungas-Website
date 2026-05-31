<script setup>
import { ref, onMounted } from 'vue';
import Navbar from '../Components/Landing/Navbar.vue';
import Footer from '../Components/Landing/Footer.vue';
import ErrorBoundary from '../Components/ErrorBoundary.vue';

const isMounted = ref(false);

onMounted(() => {
    setTimeout(() => {
        isMounted.value = true;
    }, 50);
});
</script>

<template>
    <div class="min-h-screen bg-white flex flex-col justify-between">
        <Navbar />
        <main 
            class="flex-1 transition-all duration-700 ease-out transform"
            :class="isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
        >
            <ErrorBoundary>
                <slot />
            </ErrorBoundary>
        </main>
        <Footer />
    </div>
</template>
