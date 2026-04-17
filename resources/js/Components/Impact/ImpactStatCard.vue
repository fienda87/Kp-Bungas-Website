<script setup>
defineProps({
    icon: { type: String, required: true },
    iconGradient: { type: String, required: true },
    value: { type: String, required: true },
    label: { type: String, required: true },
    description: { type: String, default: '' },
});
</script>

<template>
    <article class="rounded-2xl bg-white p-8 text-center shadow-[0_10px_15px_rgba(0,0,0,0.1),0_4px_6px_rgba(0,0,0,0.1)]">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full" :style="{ backgroundImage: iconGradient }">
            <img :src="icon" alt="" class="h-8 w-8" aria-hidden="true" />
        </div>
        <p class="mt-4 text-5xl font-bold leading-10 text-[#101828]">{{ value }}</p>
        <p class="mt-2 text-base leading-6 text-[#4a5565]">{{ label }}</p>
        <p v-if="description" class="mt-1 text-sm leading-6 text-[#4a5565]">{{ description }}</p>
    </article>
</template>
