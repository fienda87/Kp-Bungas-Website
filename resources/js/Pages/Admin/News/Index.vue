<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';
import AdminEmptyState from '@/Components/Admin/AdminEmptyState.vue';
import AdminStatusBadge from '@/Components/Admin/AdminStatusBadge.vue';
import AdminTableToolbar from '@/Components/Admin/AdminTableToolbar.vue';

const props = defineProps({
    news: Object,
    categories: Array,
    filters: Object,
});

const page = usePage();
const search = ref(props.filters?.search || '');
const selectedCategory = ref(props.filters?.category || '');
const selectedStatus = ref(props.filters?.status || '');
const showConfirmModal = ref(false);
const itemToDelete = ref(null);
const toastMessage = ref('');
const toastType = ref('success');
const isDeleting = ref(false);

watch([search, selectedCategory, selectedStatus], debounce(() => {
    router.get(route('admin.news.index'), {
        search: search.value,
        category: selectedCategory.value,
        status: selectedStatus.value,
    }, {
        preserveState: true,
        replace: true,
    });
}, 300));

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showConfirmModal.value = true;
};

const deleteNews = () => {
    isDeleting.value = true;
    router.delete(route('admin.news.destroy', itemToDelete.value.slug), {
        onSuccess: () => {
            showConfirmModal.value = false;
            showToast('Berita berhasil dihapus');
        },
        onError: () => showToast('Terjadi kesalahan saat menghapus berita', 'error'),
        onFinish: () => {
            isDeleting.value = false;
        },
    });
};

const resetFilters = () => {
    search.value = '';
    selectedCategory.value = '';
    selectedStatus.value = '';
    router.get(route('admin.news.index'), {}, {
        preserveState: true,
        replace: true,
    });
};

const showToast = (message, type = 'success') => {
    toastMessage.value = message;
    toastType.value = type;
    setTimeout(() => {
        toastMessage.value = '';
    }, 3000);
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
};

watch(() => page.props.flash?.success, (message) => {
    if (message) showToast(message);
}, { immediate: true });

watch(() => page.props.flash?.error, (message) => {
    if (message) showToast(message, 'error');
}, { immediate: true });
</script>

<template>
    <Head title="Kelola Berita" />

    <AdminLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">Kelola Berita</h2>
                    <p class="mt-1 text-sm text-gray-500">Pantau draft, publikasi, thumbnail, dan lampiran artikel.</p>
                </div>
                <Link :href="route('admin.news.create')">
                    <PrimaryButton>Tambah Berita</PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
                <AdminTableToolbar>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari judul atau isi berita..."
                        class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 md:max-w-sm"
                    >
                    <select
                        v-model="selectedCategory"
                        class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 md:max-w-52"
                    >
                        <option value="">Semua kategori</option>
                        <option v-for="cat in categories || []" :key="cat" :value="cat">{{ cat }}</option>
                    </select>
                    <select
                        v-model="selectedStatus"
                        class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 md:max-w-44"
                    >
                        <option value="">Semua status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                    <template #actions>
                        <button type="button" class="rounded-md border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50" @click="resetFilters">
                            Reset
                        </button>
                    </template>
                </AdminTableToolbar>

                <div class="overflow-hidden rounded-lg border border-gray-100 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Artikel</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Kategori</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Media</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Dilihat</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Tanggal</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody v-if="news.data.length" class="divide-y divide-gray-100 bg-white">
                                <tr v-for="item in news.data" :key="item.id" class="hover:bg-gray-50">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <img
                                                v-if="item.thumbnail_url"
                                                :src="item.thumbnail_url"
                                                :alt="item.title"
                                                class="h-12 w-16 rounded-md object-cover"
                                            >
                                            <div v-else class="flex h-12 w-16 items-center justify-center rounded-md bg-gray-100 text-xs font-semibold text-gray-400">
                                                No Img
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-gray-900">{{ item.title }}</p>
                                                <p class="truncate text-xs text-gray-500">/{{ item.slug }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">{{ item.category || 'Tanpa Kategori' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <AdminStatusBadge :status="item.status" :label="item.status === 'published' ? 'Published' : 'Draft'" />
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <span :class="item.thumbnail_url ? 'text-emerald-700' : 'text-gray-400'">Thumbnail</span>
                                            <span :class="item.pdf_url ? 'text-emerald-700' : 'text-gray-400'">PDF</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">{{ item.view_count ?? 0 }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">{{ formatDate(item.published_at || item.created_at) }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-medium">
                                        <Link v-if="item.status === 'published'" :href="route('news.show', item.slug)" class="mr-3 text-gray-600 hover:text-gray-900" target="_blank">Lihat</Link>
                                        <Link :href="route('admin.news.edit', item.slug)" class="mr-3 text-emerald-700 hover:text-emerald-800">Edit</Link>
                                        <button type="button" class="text-red-600 hover:text-red-800" @click="confirmDelete(item)">Hapus</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="!news.data.length" class="p-6">
                        <AdminEmptyState title="Tidak ada artikel" message="Ubah filter atau buat artikel baru." />
                    </div>

                    <div v-if="news.links && news.links.length > 3" class="border-t border-gray-100 px-5 py-4">
                        <nav class="flex flex-wrap justify-center gap-2" aria-label="Pagination">
                            <Link
                                v-for="(link, k) in news.links"
                                :key="k"
                                :href="link.url || '#'"
                                v-html="link.label"
                                :class="[
                                    'rounded-md border px-3 py-2 text-sm font-medium',
                                    link.active ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50',
                                    !link.url ? 'pointer-events-none opacity-50' : '',
                                ]"
                            />
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="showConfirmModal"
            :loading="isDeleting"
            title="Hapus Berita?"
            message="Apakah Anda yakin ingin menghapus berita ini secara permanen?"
            @confirm="deleteNews"
            @close="showConfirmModal = false"
        />

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>
