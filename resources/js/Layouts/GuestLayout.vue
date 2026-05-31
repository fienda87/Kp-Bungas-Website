<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Navbar from '../Components/Landing/Navbar.vue';
import Footer from '../Components/Landing/Footer.vue';
import ErrorBoundary from '../Components/ErrorBoundary.vue';
import SkeletonLoader from '../Components/SkeletonLoader.vue';

const isLoading = ref(false);

let removeStartEventListener = null;
let removeFinishEventListener = null;

onMounted(() => {
    removeStartEventListener = router.on('start', () => isLoading.value = true);
    removeFinishEventListener = router.on('finish', () => isLoading.value = false);
});

onUnmounted(() => {
    if (removeStartEventListener) removeStartEventListener();
    if (removeFinishEventListener) removeFinishEventListener();
});
</script>

<template>
    <div class="min-h-screen bg-white">
        <Navbar />
        <main>
            <ErrorBoundary>
                <div v-if="isLoading" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
                    <!-- Skeleton layout for guest pages -->
                    <SkeletonLoader type="card" class="h-64 rounded-2xl" />
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <SkeletonLoader type="card" class="h-48" />
                        <SkeletonLoader type="card" class="h-48" />
                        <SkeletonLoader type="card" class="h-48" />
                    </div>
                </div>
                <div v-else>
                    <slot />
                </div>
            </ErrorBoundary>
        </main>
        <Footer />
    </div>
</template>
