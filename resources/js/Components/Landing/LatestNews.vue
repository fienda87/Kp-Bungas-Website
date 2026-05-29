<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    news: {
        type: Array,
        default: () => [],
    }
});
</script>

<template>
    <section id="latest-news" class="bg-slate-50 py-20">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Kabar Terbaru</p>
                <h2 class="mt-3 text-3xl font-bold text-slate-900 sm:text-4xl">Berita & Artikel</h2>
                <p class="mt-4 text-lg text-slate-600">
                    Ikuti terus perkembangan, kegiatan, dan cerita inspiratif terbaru dari Kampung Bungas.
                </p>
            </div>

            <div v-if="news.length" class="mt-12 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="item in news"
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
            
            <div class="mt-12 text-center">
                <Link
                    :href="route('news.index')"
                    class="inline-block rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
                >
                    Lihat Semua Berita
                </Link>
            </div>
        </div>
    </section>
</template>
