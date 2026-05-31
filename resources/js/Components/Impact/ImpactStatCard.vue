<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    icon: { type: String, required: true },
    iconGradient: { type: String, required: true },
    value: { type: String, required: true },
    label: { type: String, required: true },
    description: { type: String, default: '' },
    delayClass: { type: String, default: 'delay-0' },
});

const isVisible = ref(false);
const displayValue = ref('0');
const cardRef = ref(null);
let observer = null;

const animateCount = () => {
    const rawValue = props.value;
    const numberMatch = rawValue.replace(/,/g, '').match(/\d+/);
    if (!numberMatch) {
        displayValue.value = rawValue;
        return;
    }

    const targetNumber = parseInt(numberMatch[0], 10);
    const prefix = rawValue.substring(0, rawValue.indexOf(numberMatch[0]));
    const suffix = rawValue.substring(rawValue.indexOf(numberMatch[0]) + numberMatch[0].length);

    const duration = 2000; // 2 seconds
    const startTime = performance.now();

    const update = (now) => {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Ease Out Quad
        const easeProgress = progress * (2 - progress);
        const currentVal = Math.floor(easeProgress * targetNumber);

        // Format with thousands separator
        const hasComma = rawValue.includes(',');
        const formattedVal = hasComma 
            ? currentVal.toLocaleString('en-US') 
            : currentVal.toString();

        displayValue.value = `${prefix}${formattedVal}${suffix}`;

        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            displayValue.value = rawValue; // Snap to target
        }
    };

    requestAnimationFrame(update);
};

watch(isVisible, (newVal) => {
    if (newVal) {
        animateCount();
    }
});

onMounted(() => {
    observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                isVisible.value = true;
                if (observer && cardRef.value) {
                    observer.unobserve(cardRef.value);
                }
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -60px 0px'
    });

    if (cardRef.value) {
        observer.observe(cardRef.value);
    }
});

onUnmounted(() => {
    if (observer && cardRef.value) {
        observer.disconnect();
    }
});
</script>

<template>
    <article 
        ref="cardRef"
        class="rounded-2xl bg-white p-8 text-center shadow-[0_10px_15px_rgba(0,0,0,0.1),0_4px_6px_rgba(0,0,0,0.1)] hover:shadow-2xl hover:-translate-y-2 transition-all duration-700 ease-out transform"
        :class="[
            isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-16 pointer-events-none',
            delayClass
        ]"
    >
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full transition-transform duration-500 hover:rotate-12" :style="{ backgroundImage: iconGradient }">
            <img :src="icon" alt="" class="h-8 w-8" aria-hidden="true" />
        </div>
        <p 
            class="mt-4 text-5xl font-bold leading-10 text-[#101828] transition-all duration-1000 ease-out transform"
            :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
        >
            {{ displayValue }}
        </p>
        <p class="mt-2 text-base leading-6 text-[#4a5565]">{{ label }}</p>
        <p v-if="description" class="mt-1 text-sm leading-6 text-[#4a5565]">{{ description }}</p>
    </article>
</template>
