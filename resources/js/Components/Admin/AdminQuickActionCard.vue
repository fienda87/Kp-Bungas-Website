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
        class="flex items-center justify-between rounded-lg border border-gray-100 bg-white px-4 py-3 text-left shadow-sm transition hover:border-emerald-200 hover:bg-emerald-50"
    >
        <span>
            <span class="block text-sm font-semibold text-gray-900">{{ title }}</span>
            <span v-if="description" class="mt-0.5 block text-xs text-gray-500">{{ description }}</span>
        </span>
        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </Link>
</template>
