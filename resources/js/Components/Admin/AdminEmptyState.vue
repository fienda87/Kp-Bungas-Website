<script setup>
defineProps({
    title: String,
    message: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="rounded-lg border border-dashed border-gray-200 bg-gray-50 px-6 py-10 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-white text-gray-400 shadow-sm">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3.5L12 3 9.5 5H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0H4" />
            </svg>
        </div>
        <h3 class="mt-4 text-sm font-semibold text-gray-900">{{ title }}</h3>
        <p v-if="message" class="mt-1 text-sm text-gray-500">{{ message }}</p>
        <div v-if="$slots.actions" class="mt-5">
            <slot name="actions" />
        </div>
    </div>
</template>
