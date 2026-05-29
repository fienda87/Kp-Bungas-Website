<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { formatNewsDate, getExcerpt } from '@/utils/news';

const props = defineProps({
    news: Object,
    recentNews: Object,
});

const article = computed(() => props.news?.data ?? {});
const relatedItems = computed(() => props.recentNews?.data ?? []);
</script>

<template>
    <Head>
        <title>{{ article.title || 'Detail Berita' }} - Kampung Bungas</title>
        <meta head-key="description" name="description" :content="getExcerpt(article.content, 150, 'Berita dan informasi terbaru dari Kampung Bungas.')" />
        <meta head-key="og:title" property="og:title" :content="article.title || 'Detail Berita'" />
        <meta head-key="og:description" property="og:description" :content="getExcerpt(article.content, 150, 'Berita dan informasi terbaru dari Kampung Bungas.')" />
        <meta head-key="og:type" property="og:type" content="article" />
        <meta v-if="article.thumbnail" head-key="og:image" property="og:image" :content="article.thumbnail" />
        <meta head-key="twitter:card" name="twitter:card" content="summary_large_image" />
    </Head>

    <GuestLayout>
        <section class="bg-gradient-to-b from-emerald-50/60 via-white to-white">
            <div class="mx-auto w-full max-w-5xl px-6 pb-20 pt-10">
                <div class="flex flex-col gap-4">
                    <Link :href="route('news.index')" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
                        ← Kembali ke Berita
                    </Link>

                    <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">
                        <span>{{ formatNewsDate(article.published_at) }}</span>
                        <span class="h-1 w-1 rounded-full bg-emerald-300"></span>
                        <span>{{ article.category || 'Berita Kampung' }}</span>
                    </div>

                    <h1 class="text-3xl font-semibold leading-tight text-slate-900 sm:text-4xl">
                        {{ article.title || 'Judul berita belum tersedia' }}
                    </h1>

                    <p class="text-sm text-slate-500">
                        Dilihat {{ article.view_count ?? 0 }} kali
                    </p>
                </div>

                <div class="mt-8 overflow-hidden rounded-[2rem] bg-slate-100 shadow-sm">
                    <img
                        v-if="article.thumbnail"
                        :src="article.thumbnail"
                        :alt="article.title"
                        class="h-72 w-full object-cover sm:h-96"
                        loading="lazy"
                    >
                    <div v-else class="flex h-72 w-full items-center justify-center bg-gradient-to-br from-emerald-200 via-emerald-100 to-slate-50 text-sm font-semibold uppercase tracking-[0.2em] text-emerald-700 sm:h-96">
                        Kampung Bungas
                    </div>
                </div>

                <div class="mt-10 rounded-[2rem] bg-white p-6 shadow-[0_20px_45px_-35px_rgba(15,23,42,0.35)] sm:p-8">
                    <div v-if="article.content" class="prose prose-lg max-w-none text-slate-700" v-html="article.content"></div>
                    <p v-else class="text-sm leading-7 text-slate-500">
                        Konten berita belum tersedia. Silakan kembali lagi nanti untuk informasi lengkapnya.
                    </p>
                </div>

                <div v-if="article.pdf_file" class="mt-8">
                    <a
                        :href="article.pdf_file"
                        class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-5 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100"
                        target="_blank"
                        rel="noreferrer"
                    >
                        Lihat Lampiran PDF
                    </a>
                </div>

                <div v-if="relatedItems.length" class="mt-14">
                    <h2 class="text-xl font-semibold text-slate-900">Berita Terkait</h2>
                    <div class="mt-6 grid gap-6 sm:grid-cols-2">
                        <article
                            v-for="item in relatedItems"
                            :key="item.id"
                            class="rounded-[1.5rem] border border-slate-100 bg-white p-5 shadow-sm"
                        >
                            <div class="text-xs font-medium text-emerald-600">
                                {{ formatNewsDate(item.published_at) }}
                            </div>
                            <h3 class="mt-2 text-base font-semibold text-slate-900">
                                <Link :href="route('news.show', item.slug)" class="hover:text-emerald-600">
                                    {{ item.title }}
                                </Link>
                            </h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                {{ getExcerpt(item.content, 96, 'Deskripsi berita belum tersedia.') }}
                            </p>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
