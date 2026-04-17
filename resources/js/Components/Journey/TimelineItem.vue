<script setup>
defineProps({
    year: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    description: {
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
    position: {
        type: String,
        enum: ['left', 'right'],
        required: true,
    },
});
</script>

<template>
    <div class="relative h-52">
        <!-- Left-aligned card -->
        <div v-if="position === 'left'" class="absolute left-0 top-0 w-[47%]">
            <div class="rounded-2xl bg-white p-6 shadow-[0_10px_15px_-3px_rgba(0,0,0,0.1),0_4px_6px_-4px_rgba(0,0,0,0.1)]">
                <div class="mb-4 flex items-center justify-end gap-3">
                    <p class="text-3xl font-bold leading-8 text-[#101828]">{{ year }}</p>
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full"
                        :style="{ backgroundImage: iconGradient }"
                        aria-hidden="true"
                    >
                        <img :src="icon" alt="" class="h-5 w-5" />
                    </div>
                </div>

                <h3 class="text-right text-2xl font-bold leading-7 text-[#101828]">{{ title }}</h3>
                <p class="mt-3 text-right text-base leading-6 text-[#4a5565]">{{ description }}</p>
            </div>
        </div>

        <!-- Timeline center marker -->
        <div class="absolute left-1/2 top-19 -translate-x-1/2">
            <div class="h-6 w-6 rounded-full border-4 border-[#00c950] bg-white"></div>
        </div>

        <!-- Right-aligned card -->
        <div v-if="position === 'right'" class="absolute right-0 top-0 w-[47%]">
            <div class="rounded-2xl bg-white p-6 shadow-[0_10px_15px_-3px_rgba(0,0,0,0.1),0_4px_6px_-4px_rgba(0,0,0,0.1)]">
                <div class="mb-4 flex items-center justify-start gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full"
                        :style="{ backgroundImage: iconGradient }"
                        aria-hidden="true"
                    >
                        <img :src="icon" alt="" class="h-5 w-5" />
                    </div>
                    <p class="text-3xl font-bold leading-8 text-[#101828]">{{ year }}</p>
                </div>

                <h3 class="text-2xl font-bold leading-7 text-[#101828]">{{ title }}</h3>
                <p class="mt-3 text-base leading-6 text-[#4a5565]">{{ description }}</p>
            </div>
        </div>
    </div>
</template>
