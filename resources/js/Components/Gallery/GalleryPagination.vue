<script setup>
import { computed } from 'vue';

const props = defineProps({
    currentPage: {
        type: Number,
        required: true,
    },
    totalPages: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(['prev', 'next', 'go-to']);

const pages = computed(() => Array.from({ length: props.totalPages }, (_, index) => index + 1));
</script>

<template>
    <div class="mt-6 flex flex-wrap items-center justify-between gap-4 text-black">
        <button
            type="button"
            class="text-3xl font-medium leading-tight disabled:cursor-not-allowed disabled:opacity-40"
            :disabled="currentPage === 1"
            @click="emit('prev')"
        >
            &lt; Sebelumnya
        </button>

        <div class="flex items-center">
            <button
                v-for="page in pages"
                :key="page"
                type="button"
                class="h-9 w-10 text-3xl leading-none text-black"
                :class="page === currentPage ? 'bg-[#8cbe00] text-white' : 'bg-white text-black'"
                @click="emit('go-to', page)"
            >
                {{ page }}
            </button>
        </div>

        <button
            type="button"
            class="text-3xl font-medium leading-tight disabled:cursor-not-allowed disabled:opacity-40"
            :disabled="currentPage === totalPages"
            @click="emit('next')"
        >
            Selanjutnya &gt;
        </button>
    </div>
</template>
