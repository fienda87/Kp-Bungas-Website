<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../Layouts/GuestLayout.vue';
import ProgramActivitiesSection from '../Components/Program/ProgramActivitiesSection.vue';

defineProps({
    programs: {
        type: Array,
        default: () => [],
    }
});
</script>

<template>
    <Head>
        <title>Program - Kampung Bungas</title>
        <meta head-key="description" name="description" content="Jelajahi berbagai program dan kegiatan unggulan di Kampung Bungas." />
    </Head>

    <GuestLayout>
        <ProgramActivitiesSection :programs="programs" />
    </GuestLayout>
</template>
