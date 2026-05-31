<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

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

const isVisible = ref(false);
const itemRef = ref(null);
let observer = null;

onMounted(() => {
    observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                isVisible.value = true;
                if (observer && itemRef.value) {
                    observer.unobserve(itemRef.value);
                }
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -80px 0px'
    });

    if (itemRef.value) {
        observer.observe(itemRef.value);
    }
});

onUnmounted(() => {
    if (observer && itemRef.value) {
        observer.disconnect();
    }
});
</script>

<template>
    <div ref="itemRef" class="relative h-52">
        <!-- Left-aligned card -->
        <div 
            v-if="position === 'left'" 
            class="absolute left-0 top-0 w-[47%] transition-all duration-1000 ease-out transform"
            :class="isVisible ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-16 pointer-events-none'"
        >
            <div class="rounded-2xl bg-white p-6 shadow-[0_10px_15px_-3px_rgba(0,0,0,0.1),0_4px_6px_-4px_rgba(0,0,0,0.1)] hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
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
        <div 
            class="absolute left-1/2 top-19 -translate-x-1/2 transition-all duration-700 delay-300 ease-out transform"
            :class="isVisible ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"
        >
            <div class="h-6 w-6 rounded-full border-4 border-[#00c950] bg-white shadow-md"></div>
        </div>

        <!-- Right-aligned card -->
        <div 
            v-if="position === 'right'" 
            class="absolute right-0 top-0 w-[47%] transition-all duration-1000 ease-out transform"
            :class="isVisible ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-16 pointer-events-none'"
        >
            <div class="rounded-2xl bg-white p-6 shadow-[0_10px_15px_-3px_rgba(0,0,0,0.1),0_4px_6px_-4px_rgba(0,0,0,0.1)] hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
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
