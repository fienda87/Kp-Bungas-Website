<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../Layouts/GuestLayout.vue';
import GallerySection from '../Components/Gallery/GallerySection.vue';
</script>

<template>
    <Head title="Galeri" />

    <GuestLayout>
        <GallerySection />
    </GuestLayout>
</template>
