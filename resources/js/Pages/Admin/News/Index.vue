<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    news: Object,
    categories: Array,
    filters: Object
});

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || '');
const showConfirmModal = ref(false);
const itemToDelete = ref(null);
const toastMessage = ref('');
const toastType = ref('success');

watch([search, selectedCategory], debounce(([newSearch, newCategory]) => {
    router.get(route('admin.news.index'), { 
        search: newSearch, 
        category: newCategory 
    }, {
        preserveState: true,
        replace: true
    });
}, 300));

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showConfirmModal.value = true;
};

const deleteNews = () => {
    router.delete(route('admin.news.destroy', itemToDelete.value.slug), {
        onSuccess: () => {
            showConfirmModal.value = false;
            showToast('Berita berhasil dihapus');
        }
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
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Kelola Berita" />

    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Berita</h2>
                <Link :href="route('admin.news.create')">
                    <PrimaryButton>
                        Tambah Berita
                    </PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <!-- Filters -->
                    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1 max-w-sm">
                            <input
                                type="text"
                                v-model="search"
                                placeholder="Cari judul berita..."
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"
                            >
                        </div>
                        <div class="w-full md:w-48">
                            <select
                                v-model="selectedCategory"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"
                            >
                                <option value="">Semua Kategori</option>
                                <option v-for="cat in categories" :key="cat" :value="cat">
                                    {{ cat }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="item in news.data" :key="item.id">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ item.title }}</div>
                                        <div class="text-xs text-gray-500">/{{ item.slug }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ item.category || 'Tanpa Kategori' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="[
                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            item.status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                                        ]">
                                            {{ item.status === 'published' ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatDate(item.published_at || item.created_at) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('admin.news.edit', item.slug)" class="text-primary hover:text-primary-dark mr-3">Edit</Link>
                                        <button @click="confirmDelete(item)" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                                <tr v-if="news.data.length === 0">
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada berita.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex justify-center" v-if="news.links && news.links.length > 3">
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <Link
                                v-for="(link, k) in news.links"
                                :key="k"
                                :href="link.url || '#'"
                                v-html="link.label"
                                :class="[
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    link.active ? 'z-10 bg-primary border-primary text-white' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                    !link.url ? 'cursor-not-allowed' : '',
                                    k === 0 ? 'rounded-l-md' : '',
                                    k === news.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="showConfirmModal"
            title="Hapus Berita?"
            message="Apakah Anda yakin ingin menghapus berita ini secara permanen?"
            @confirm="deleteNews"
            @close="showConfirmModal = false"
        />

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>
