<script setup>
defineProps({
    title: String,
    description: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <section class="rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300">
        <div v-if="title || $slots.actions" class="flex flex-col gap-3 border-b border-gray-100/80 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 v-if="title" class="text-base font-semibold text-gray-900">{{ title }}</h3>
                <p v-if="description" class="mt-1 text-sm text-gray-500">{{ description }}</p>
            </div>
            <div v-if="$slots.actions">
                <slot name="actions" />
            </div>
        </div>
        <div class="p-5">
            <slot />
        </div>
    </section>
</template>
