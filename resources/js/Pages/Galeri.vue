<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../Layouts/GuestLayout.vue';
import GallerySection from '../Components/Gallery/GallerySection.vue';

defineProps({
    galleries: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Galeri" />

    <GuestLayout>
        <GallerySection :galleries="galleries" />
    </GuestLayout>
</template>
