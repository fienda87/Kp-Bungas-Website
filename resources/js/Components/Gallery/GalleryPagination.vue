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
    <nav
        class="mt-12 border-t border-slate-100 pt-8"
        aria-label="Navigasi halaman galeri"
    >
        <div class="grid items-center gap-5 sm:grid-cols-[1fr_auto_1fr]">
        <!-- Previous Arrow Button -->
        <button
            type="button"
            class="group inline-flex h-12 w-full items-center justify-center gap-3 rounded-full border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-[#8cbe00]/40 hover:bg-[#f7fee7] hover:text-[#3f6212] hover:shadow-md active:translate-y-0 active:scale-[0.98] disabled:pointer-events-none disabled:border-slate-100 disabled:bg-slate-50 disabled:text-slate-300 disabled:shadow-none sm:w-auto sm:justify-self-start"
            :disabled="currentPage === 1"
            :aria-label="currentPage === 1 ? 'Sudah di halaman pertama' : 'Ke halaman sebelumnya'"
            @click="emit('prev')"
        >
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 transition duration-300 group-hover:-translate-x-1 group-hover:bg-[#8cbe00] group-hover:text-white group-disabled:bg-slate-100 group-disabled:text-slate-300">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6" />
                </svg>
            </span>
            <span>Sebelumnya</span>
        </button>

        <!-- Page Numbers List -->
        <div class="flex items-center justify-center gap-2 rounded-full bg-slate-50 p-1.5 shadow-inner">
            <button
                v-for="page in pages"
                :key="page"
                type="button"
                class="flex h-10 w-10 items-center justify-center rounded-full text-base font-bold transition duration-300 active:scale-95"
                :class="page === currentPage 
                    ? 'bg-[#8cbe00] text-white shadow-md shadow-[#8cbe00]/30' 
                    : 'text-slate-500 hover:bg-white hover:text-[#4d7c0f] hover:shadow-sm'"
                :aria-current="page === currentPage ? 'page' : undefined"
                :aria-label="`Ke halaman ${page}`"
                @click="emit('go-to', page)"
            >
                {{ page }}
            </button>
        </div>

        <!-- Next Arrow Button -->
        <button
            type="button"
            class="group inline-flex h-12 w-full items-center justify-center gap-3 rounded-full border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-[#8cbe00]/40 hover:bg-[#f7fee7] hover:text-[#3f6212] hover:shadow-md active:translate-y-0 active:scale-[0.98] disabled:pointer-events-none disabled:border-slate-100 disabled:bg-slate-50 disabled:text-slate-300 disabled:shadow-none sm:w-auto sm:justify-self-end"
            :disabled="currentPage === totalPages"
            :aria-label="currentPage === totalPages ? 'Sudah di halaman terakhir' : 'Ke halaman selanjutnya'"
            @click="emit('next')"
        >
            <span>Selanjutnya</span>
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 transition duration-300 group-hover:translate-x-1 group-hover:bg-[#8cbe00] group-hover:text-white group-disabled:bg-slate-100 group-disabled:text-slate-300">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </span>
        </button>
        </div>
    </nav>
</template>
