<script setup>
import { ref, onErrorCaptured } from 'vue';

const error = ref(null);

onErrorCaptured((err, instance, info) => {
    error.value = err;
    console.error('Error captured by ErrorBoundary:', err, instance, info);
    return false; // prevents the error from propagating further
});
</script>

<template>
    <div v-if="error" class="min-h-[400px] flex items-center justify-center bg-gray-50 dark:bg-gray-900 rounded-lg border border-red-200 p-8 m-4">
        <div class="text-center max-w-lg">
            <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Maaf, terjadi kesalahan saat memuat komponen ini.</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Tim teknis kami telah mencatat masalah ini. Silakan muat ulang halaman.
            </p>
            <button @click="() => { error = null; window.location.reload(); }" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                Muat Ulang
            </button>
        </div>
    </div>
    <slot v-else />
</template>
