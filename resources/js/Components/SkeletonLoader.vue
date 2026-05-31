<script setup>
defineProps({
    type: {
        type: String,
        default: 'text', // 'text', 'avatar', 'image', 'card'
    },
    width: {
        type: String,
        default: 'w-full',
    },
    height: {
        type: String,
        default: 'h-4',
    },
    rounded: {
        type: String,
        default: 'rounded',
    },
});
</script>

<template>
    <div
        class="animate-pulse bg-gray-200 dark:bg-gray-700"
        :class="[
            type === 'avatar' ? 'w-12 h-12 rounded-full' : '',
            type === 'image' ? 'w-full aspect-video rounded-lg' : '',
            type === 'card' ? 'w-full h-48 rounded-xl' : '',
            type === 'text' ? `${width} ${height} ${rounded}` : '',
        ]"
    ></div>
</template>
