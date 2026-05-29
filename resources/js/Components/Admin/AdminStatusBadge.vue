<script setup>
const props = defineProps({
    status: {
        type: String,
        default: 'default',
    },
    label: {
        type: String,
        default: '',
    },
});

const classes = {
    published: 'bg-green-100 text-green-700',
    draft: 'bg-yellow-100 text-yellow-700',
    empty: 'bg-red-100 text-red-700',
    active: 'bg-emerald-100 text-emerald-700',
    default: 'bg-gray-100 text-gray-700',
};
</script>

<template>
    <span :class="['inline-flex rounded-full px-2.5 py-1 text-xs font-semibold', classes[status] || classes.default]">
        {{ label || status }}
    </span>
</template>
