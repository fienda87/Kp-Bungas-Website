<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminEmptyState from '@/Components/Admin/AdminEmptyState.vue';
import AdminQuickActionCard from '@/Components/Admin/AdminQuickActionCard.vue';
import AdminSectionCard from '@/Components/Admin/AdminSectionCard.vue';
import AdminStatusBadge from '@/Components/Admin/AdminStatusBadge.vue';

const props = defineProps({
    stats: Object,
    attentionItems: Object,
    recentNews: Array,
    recentGalleries: Array,
    quickLinks: Array,
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
};

const statCards = computed(() => [
    { 
        label: 'Total Artikel', 
        value: props.stats?.news_count ?? 0, 
        hint: `${props.stats?.published_news_count ?? 0} published`,
        icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" /></svg>',
        colorClass: 'text-blue-600',
        bgClass: 'bg-blue-50',
    },
    { 
        label: 'Draft', 
        value: props.stats?.draft_news_count ?? 0, 
        hint: 'Menunggu publish',
        icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>',
        colorClass: 'text-amber-600',
        bgClass: 'bg-amber-50',
    },
    { 
        label: 'Album Galeri', 
        value: props.stats?.galleries_count ?? 0, 
        hint: `${props.stats?.empty_galleries_count ?? 0} album kosong`,
        icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>',
        colorClass: 'text-emerald-600',
        bgClass: 'bg-emerald-50',
    },
    { 
        label: 'Total Foto', 
        value: props.stats?.photos_count ?? 0, 
        hint: 'Semua album',
        icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.822 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" /></svg>',
        colorClass: 'text-purple-600',
        bgClass: 'bg-purple-50',
    },
    { 
        label: 'Program', 
        value: props.stats?.programs_count ?? 0, 
        hint: 'Konten aktif/nonaktif',
        icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z" /></svg>',
        colorClass: 'text-indigo-600',
        bgClass: 'bg-indigo-50',
    },
    { 
        label: 'Tanpa Thumbnail', 
        value: props.stats?.news_without_thumbnail_count ?? 0, 
        hint: 'Perlu visual',
        icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
        colorClass: 'text-red-600',
        bgClass: 'bg-red-50',
    },
]);

const hasAttention = computed(() => {
    const items = props.attentionItems || {};
    return Object.values(items).some((collection) => (collection || []).length > 0);
});
</script>

<template>
    <Head title="Dashboard Admin" />

    <AdminLayout>
        <template #header>
            <div class="flex flex-col gap-1">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="text-sm text-gray-500">Ringkasan konten dan pekerjaan yang perlu diprioritaskan.</p>
            </div>
        </template>

        <div class="space-y-6">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <div
                    v-for="card in statCards"
                    :key="card.label"
                    class="relative overflow-hidden rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md"
                >
                    <div class="flex items-center gap-4">
                        <div :class="['flex h-14 w-14 shrink-0 items-center justify-center rounded-full', card.bgClass, card.colorClass]" v-html="card.icon"></div>
                        <div>
                            <p class="text-xs font-bold tracking-wider uppercase text-gray-500">{{ card.label }}</p>
                            <p class="mt-1 text-3xl font-black text-gray-900">{{ card.value }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        <p class="text-sm font-medium text-gray-500">{{ card.hint }}</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
                <AdminSectionCard title="Perlu Perhatian" description="Konten yang sebaiknya dicek sebelum dipublikasikan.">
                    <div v-if="hasAttention" class="grid gap-4 lg:grid-cols-2">
                        <div class="rounded-lg border border-gray-100 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-gray-900">Draft Terbaru</h4>
                                <Link :href="route('admin.news.index', { status: 'draft' })" class="text-xs font-semibold text-emerald-700">Lihat</Link>
                            </div>
                            <div v-if="attentionItems?.draftNews?.length" class="space-y-3">
                                <Link
                                    v-for="item in attentionItems.draftNews"
                                    :key="item.id"
                                    :href="route('admin.news.edit', item.slug)"
                                    class="block rounded-md border border-gray-100 px-3 py-2 hover:bg-gray-50"
                                >
                                    <p class="truncate text-sm font-medium text-gray-900">{{ item.title }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ formatDate(item.created_at) }}</p>
                                </Link>
                            </div>
                            <p v-else class="text-sm text-gray-500">Tidak ada draft.</p>
                        </div>

                        <div class="rounded-lg border border-gray-100 p-4">
                            <h4 class="mb-3 text-sm font-semibold text-gray-900">Artikel Tanpa Thumbnail</h4>
                            <div v-if="attentionItems?.newsWithoutThumbnail?.length" class="space-y-3">
                                <Link
                                    v-for="item in attentionItems.newsWithoutThumbnail"
                                    :key="item.id"
                                    :href="route('admin.news.edit', item.slug)"
                                    class="block rounded-md border border-gray-100 px-3 py-2 hover:bg-gray-50"
                                >
                                    <p class="truncate text-sm font-medium text-gray-900">{{ item.title }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ item.status }}</p>
                                </Link>
                            </div>
                            <p v-else class="text-sm text-gray-500">Semua artikel sudah punya thumbnail.</p>
                        </div>

                        <div class="rounded-lg border border-gray-100 p-4">
                            <h4 class="mb-3 text-sm font-semibold text-gray-900">Album Kosong</h4>
                            <div v-if="attentionItems?.emptyGalleries?.length" class="space-y-3">
                                <Link
                                    v-for="item in attentionItems.emptyGalleries"
                                    :key="item.id"
                                    :href="route('admin.galleries.show', item.id)"
                                    class="block rounded-md border border-gray-100 px-3 py-2 hover:bg-gray-50"
                                >
                                    <p class="truncate text-sm font-medium text-gray-900">{{ item.album_name }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ formatDate(item.created_at) }}</p>
                                </Link>
                            </div>
                            <p v-else class="text-sm text-gray-500">Tidak ada album kosong.</p>
                        </div>

                        <div class="rounded-lg border border-gray-100 p-4">
                            <h4 class="mb-3 text-sm font-semibold text-gray-900">Album Tanpa Cover</h4>
                            <div v-if="attentionItems?.galleriesWithoutCover?.length" class="space-y-3">
                                <Link
                                    v-for="item in attentionItems.galleriesWithoutCover"
                                    :key="item.id"
                                    :href="route('admin.galleries.show', item.id)"
                                    class="block rounded-md border border-gray-100 px-3 py-2 hover:bg-gray-50"
                                >
                                    <p class="truncate text-sm font-medium text-gray-900">{{ item.album_name }}</p>
                                    <p class="mt-1 text-xs text-gray-500">Cover belum dipilih</p>
                                </Link>
                            </div>
                            <p v-else class="text-sm text-gray-500">Semua album sudah punya cover.</p>
                        </div>
                    </div>
                    <AdminEmptyState
                        v-else
                        title="Tidak ada pekerjaan tertunda"
                        message="Draft, thumbnail, dan album galeri dalam kondisi rapi."
                    />
                </AdminSectionCard>

                <div class="space-y-3">
                    <AdminQuickActionCard
                        v-for="item in quickLinks || []"
                        :key="`${item.route}-${item.label}`"
                        :href="route(item.route, item.params || {})"
                        :title="item.label"
                    />
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-2">
                <AdminSectionCard title="Artikel Terbaru">
                    <div v-if="recentNews?.length" class="divide-y divide-gray-100">
                        <div v-for="item in recentNews" :key="item.id" class="flex items-center justify-between gap-4 py-3">
                            <div class="min-w-0">
                                <Link :href="route('admin.news.edit', item.slug)" class="truncate text-sm font-semibold text-gray-900 hover:text-emerald-700">
                                    {{ item.title }}
                                </Link>
                                <p class="mt-1 text-xs text-gray-500">{{ item.category || 'Tanpa kategori' }} · {{ formatDate(item.published_at || item.created_at) }}</p>
                            </div>
                            <AdminStatusBadge :status="item.status" :label="item.status === 'published' ? 'Published' : 'Draft'" />
                        </div>
                    </div>
                    <AdminEmptyState v-else title="Belum ada artikel" message="Artikel terbaru akan muncul di sini." />
                </AdminSectionCard>

                <AdminSectionCard title="Galeri Terbaru">
                    <div v-if="recentGalleries?.length" class="divide-y divide-gray-100">
                        <div v-for="item in recentGalleries" :key="item.id" class="flex items-center justify-between gap-4 py-3">
                            <div class="min-w-0">
                                <Link :href="route('admin.galleries.show', item.id)" class="truncate text-sm font-semibold text-gray-900 hover:text-emerald-700">
                                    {{ item.album_name }}
                                </Link>
                                <p class="mt-1 text-xs text-gray-500">{{ item.photos_count }} foto · {{ formatDate(item.created_at) }}</p>
                            </div>
                            <AdminStatusBadge :status="item.photos_count > 0 ? 'active' : 'empty'" :label="item.photos_count > 0 ? 'Aktif' : 'Kosong'" />
                        </div>
                    </div>
                    <AdminEmptyState v-else title="Belum ada galeri" message="Album terbaru akan muncul di sini." />
                </AdminSectionCard>
            </div>
        </div>
    </AdminLayout>
</template>
