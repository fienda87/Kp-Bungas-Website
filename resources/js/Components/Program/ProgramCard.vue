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
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
    iconGradient: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <article class="overflow-hidden rounded-2xl bg-white shadow-[0_10px_15px_-3px_rgba(0,0,0,0.1),0_4px_6px_-4px_rgba(0,0,0,0.1)]">
        <!-- Image Container -->
        <div class="relative h-48 overflow-hidden bg-slate-200 sm:h-80 lg:h-96">
            <img :src="image" :alt="title" class="h-full w-full object-cover" />
        </div>

        <!-- Content Container -->
        <div class="flex flex-col p-6 sm:p-8">
            <!-- Icon Badge -->
            <div
                class="mb-6 flex h-12 w-12 items-center justify-center rounded-full"
                :style="{ backgroundImage: iconGradient }"
                aria-hidden="true"
            >
                <img :src="icon" alt="" class="h-6 w-6" />
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
