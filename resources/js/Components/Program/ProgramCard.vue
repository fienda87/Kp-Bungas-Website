<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    image: {
        type: String,
        required: false,
    },
    icon: {
        type: String,
        required: false,
    },
    iconGradient: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <article class="overflow-hidden rounded-2xl bg-white shadow-[0_10px_15px_-3px_rgba(0,0,0,0.1),0_4px_6px_-4px_rgba(0,0,0,0.1)] transition-transform duration-300 hover:-translate-y-1">
        <!-- Image Container -->
        <div class="relative h-48 overflow-hidden bg-slate-200 sm:h-80 lg:h-96">
            <img v-if="image" :src="image" :alt="title" class="h-full w-full object-cover transition duration-500 hover:scale-105" loading="lazy" />
            <div v-else class="flex h-full w-full items-center justify-center bg-emerald-50 text-emerald-700 font-medium">Belum ada gambar</div>
        </div>

        <!-- Content Container -->
        <div class="flex flex-col p-6 sm:p-8">
            <!-- Icon Badge -->
            <div
                class="mb-6 flex h-12 w-12 items-center justify-center rounded-full shadow-sm text-xl text-white"
                :style="{ backgroundImage: iconGradient }"
                aria-hidden="true"
            >
                <img v-if="icon && (icon.includes('/') || icon.includes('.'))" :src="icon" :alt="`${title} icon`" class="h-6 w-6 object-contain" loading="lazy" />
                <span v-else>{{ icon || '🌱' }}</span>
            </div>

            <!-- Title -->
            <h3 class="text-2xl font-bold leading-8 text-[#101828] sm:text-3xl sm:leading-10">
                {{ title }}
            </h3>

            <!-- Description -->
            <p class="mt-3 flex-1 text-base leading-6 text-[#4a5565] sm:text-base sm:leading-7">
                {{ description }}
            </p>
        </div>
    </article>
</template>
