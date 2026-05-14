<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../Layouts/GuestLayout.vue';
import ProgramActivitiesSection from '../Components/Program/ProgramActivitiesSection.vue';
</script>

<template>
    <Head title="Program" />

    <GuestLayout>
        <ProgramActivitiesSection />
    </GuestLayout>
</template>
