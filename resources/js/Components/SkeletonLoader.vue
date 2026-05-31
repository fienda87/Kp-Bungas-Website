<script setup>
defineProps({
    type: {
        type: String,
        default: 'text', // 'text', 'avatar', 'image', 'card'
    },
    class: {
        type: String,
        default: '',
    }
});
</script>

<template>
    <div
        class="animate-pulse bg-slate-200 dark:bg-slate-700 rounded-md"
        :class="[
            type === 'text' ? 'h-4 w-full' : '',
            type === 'avatar' ? 'h-12 w-12 rounded-full' : '',
            type === 'image' ? 'h-48 w-full' : '',
            type === 'card' ? 'h-64 w-full rounded-2xl' : '',
            $props.class
        ]"
    ></div>
</template>
