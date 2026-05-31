<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: String,
    title: String,
    description: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <Link
        :href="href"
        class="group flex items-center justify-between rounded-2xl border border-gray-100 bg-white px-5 py-4 text-left shadow-sm transition-all duration-300 hover:border-primary-light/30 hover:bg-primary-extralight/20 hover:shadow-md hover:-translate-y-0.5"
    >
        <span>
            <span class="block text-sm font-semibold text-gray-900 transition-colors group-hover:text-primary">{{ title }}</span>
            <span v-if="description" class="mt-1 block text-xs text-gray-500">{{ description }}</span>
        </span>
        <svg class="h-4 w-4 text-gray-400 group-hover:text-primary group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </Link>
</template>
