<script setup>
import { computed, ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { buildNewsFilterParams, formatNewsDate, getExcerpt } from '@/utils/news';

const props = defineProps({
    news: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const selectedCategory = ref(props.filters?.category || '');

const items = computed(() => props.news?.data ?? []);
const paginationLinks = computed(() => props.news?.meta?.links ?? []);
const hasActiveFilters = computed(() => Boolean(search.value.trim() || selectedCategory.value));
const isFilteredEmpty = computed(() => hasActiveFilters.value && items.value.length === 0);
const resultsLabel = computed(() => {
    const total = props.news?.meta?.total ?? items.value.length;

    if (!total) {
        return 'Belum ada artikel yang dapat ditampilkan.';
    }

    return `Menampilkan ${total} artikel untuk Anda jelajahi.`;
});

const applyFilters = debounce(() => {
    router.get(route('news.index'), buildNewsFilterParams({
        search: search.value,
        category: selectedCategory.value,
    }), {
        preserveState: true,
        replace: true,
    });
}, 300);

watch([search, selectedCategory], applyFilters);

const resetFilters = () => {
    search.value = '';
    selectedCategory.value = '';
    router.get(route('news.index'), {}, {
        preserveState: true,
        replace: true,
    });
};

const goToLogin = () => {
    router.visit(route('login'));
};
</script>

<template>
    <Head title="Berita Artikel" />

    <GuestLayout>
        <section class="bg-gradient-to-b from-emerald-50 via-white to-white">
            <div class="mx-auto w-full max-w-6xl px-6 pb-16 pt-12">
                <div class="flex flex-col gap-6 text-center">
                    <div class="mx-auto flex max-w-3xl flex-col items-center gap-4">
                        <span class="rounded-full bg-emerald-100 px-4 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">
                            Berita Artikel
                        </span>
                        <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">
                            Cerita Perjalanan Kampung Bungas
                        </h1>
                        <p class="max-w-2xl text-sm leading-7 text-slate-500 sm:text-base">
                            Ikuti kabar terbaru tentang program, kegiatan warga, dan perkembangan ruang hijau Kampung Bungas.
                        </p>
                    </div>

                    <div class="mx-auto w-full max-w-5xl rounded-[2rem] border border-emerald-100 bg-white/90 p-5 shadow-[0_20px_60px_-35px_rgba(16,185,129,0.35)] backdrop-blur sm:p-6">
                        <div class="grid gap-4 md:grid-cols-[1.6fr_0.8fr_auto] md:items-end">
                            <label class="text-left">
                                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                    Cari Artikel
                                </span>
                                <input
                                    v-model="search"
                                    type="text"
                                    placeholder="Cari judul atau isi berita..."
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-emerald-500"
                                >
                            </label>

                            <label class="text-left">
                                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                    Kategori
                                </span>
                                <select
                                    v-model="selectedCategory"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-emerald-500 focus:bg-white focus:ring-emerald-500"
                                >
                                    <option value="">Semua kategori</option>
                                    <option v-for="category in categories || []" :key="category" :value="category">
                                        {{ category }}
                                    </option>
                                </select>
                            </label>

                            <button
                                type="button"
                                class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:border-emerald-200 hover:text-emerald-700"
                                @click="resetFilters"
                            >
                                Reset Filter
                            </button>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4 text-sm">
                            <p class="text-slate-500">
                                {{ resultsLabel }}
                            </p>
                            <div v-if="hasActiveFilters" class="flex flex-wrap items-center gap-2 text-xs font-medium text-emerald-700">
                                <span class="rounded-full bg-emerald-50 px-3 py-1">
                                    Filter aktif
                                </span>
                                <span v-if="search.trim()" class="rounded-full bg-slate-100 px-3 py-1 text-slate-600">
                                    "{{ search.trim() }}"
                                </span>
                                <span v-if="selectedCategory" class="rounded-full bg-slate-100 px-3 py-1 text-slate-600">
                                    {{ selectedCategory }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="items.length" class="mt-12 space-y-10">
                    <div class="grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                        <article
                            v-for="item in items"
                            :key="item.id"
                            class="group relative flex flex-col overflow-hidden rounded-[1.5rem] bg-white p-3 shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-slate-100/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)]"
                        >
                            <div class="aspect-[16/10] w-full overflow-hidden rounded-xl bg-slate-100">
                                <img
                                    v-if="item.thumbnail"
                                    :src="item.thumbnail"
                                    :alt="item.title"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                >
                                <div v-else class="flex h-full w-full items-center justify-center bg-slate-50 text-sm font-medium text-slate-400">
                                    Tidak ada gambar
                                </div>
                            </div>
                            
                            <div class="flex flex-col flex-1 px-2 py-4">
                                <h3 class="text-lg font-bold leading-snug text-slate-900 transition-colors group-hover:text-emerald-600 line-clamp-3">
                                    <Link :href="route('news.show', item.slug)" class="focus:outline-none">
                                        <span class="absolute inset-0" aria-hidden="true"></span>
                                        {{ item.title }}
                                    </Link>
                                </h3>
                                <p class="mt-3 text-sm font-medium text-emerald-600">
                                    {{ item.category || 'Berita Kampung' }}
                                </p>
                            </div>
                        </article>
                    </div>
                </div>

                <div v-else class="mt-12 rounded-[2rem] border border-dashed border-slate-200 bg-white p-10 text-center shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-800">
                        {{ isFilteredEmpty ? 'Tidak ada hasil yang cocok' : 'Belum ada berita' }}
                    </h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        {{ isFilteredEmpty
                            ? 'Coba ubah kata kunci atau kategori agar hasil pencarian lebih luas.'
                            : 'Nantikan kabar terbaru dari Kampung Bungas dalam waktu dekat.' }}
                    </p>
                    <button
                        v-if="isFilteredEmpty"
                        type="button"
                        class="mt-5 rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700"
                        @click="resetFilters"
                    >
                        Lihat Semua Berita
                    </button>
                </div>

                <div v-if="paginationLinks.length > 3" class="mt-12 flex justify-center">
                    <nav class="flex flex-wrap items-center justify-center gap-2" aria-label="Pagination">
                        <Link
                            v-for="link in paginationLinks"
                            :key="`${link.label}-${link.url}`"
                            :href="link.url || '#'"
                            v-html="link.label"
                            :class="[
                                'min-w-11 rounded-2xl border px-4 py-3 text-center text-sm font-semibold transition',
                                link.active
                                    ? 'border-emerald-600 bg-emerald-600 text-white'
                                    : 'border-slate-200 bg-white text-slate-600 hover:border-emerald-200 hover:text-emerald-700',
                                !link.url ? 'pointer-events-none opacity-50' : '',
                            ]"
                        />
                    </nav>
                </div>

                <div class="mt-14 flex justify-center">
                    <button
                        type="button"
                        class="rounded-full bg-emerald-600 px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700"
                        @click="goToLogin"
                    >
                        Masuk ke Admin
                    </button>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
