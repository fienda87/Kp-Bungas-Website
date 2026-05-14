<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../Layouts/GuestLayout.vue';
import TimelineSection from '../Components/Journey/TimelineSection.vue';
</script>

<template>
    <Head title="Perjalanan" />

    <GuestLayout>
        <TimelineSection />
    </GuestLayout>
</template>
