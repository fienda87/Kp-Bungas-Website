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

const isCategoryDropdownOpen = ref(false);
const isStatusDropdownOpen = ref(false);

const selectCategory = (cat) => {
    selectedCategory.value = cat;
    isCategoryDropdownOpen.value = false;
};

const selectStatus = (status) => {
    selectedStatus.value = status;
    isStatusDropdownOpen.value = false;
};

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
                        class="w-full rounded-xl border border-gray-300 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 md:max-w-sm px-4 py-2.5 bg-white/70 placeholder-gray-400 font-medium"
                    >
                    
                    <!-- Kategori Dropdown -->
                    <div class="relative w-full md:max-w-52 text-left" @mouseenter="isCategoryDropdownOpen = true" @mouseleave="isCategoryDropdownOpen = false">
                        <!-- Backdrop to close dropdown on click outside -->
                        <div v-if="isCategoryDropdownOpen" class="fixed inset-0 z-40" @click="isCategoryDropdownOpen = false" />

                        <button
                            type="button"
                            @click="isCategoryDropdownOpen = !isCategoryDropdownOpen"
                            class="w-full text-left pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 text-sm font-semibold text-gray-800 bg-white/70 backdrop-blur-xs cursor-pointer flex items-center justify-between relative z-50"
                        >
                            <span class="block truncate">{{ selectedCategory || 'Semua kategori' }}</span>
                            
                            <!-- Arrow Icon -->
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg 
                                    class="h-4 w-4 text-gray-400 transition-transform duration-300"
                                    :class="isCategoryDropdownOpen ? 'rotate-180 text-primary' : ''"
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>

                        <!-- Dropdown List dengan Transisi Halus -->
                        <transition
                            enter-active-class="transition duration-150 ease-out"
                            enter-from-class="transform scale-95 opacity-0 -translate-y-2"
                            enter-to-class="transform scale-100 opacity-100 translate-y-0"
                            leave-active-class="transition duration-100 ease-in"
                            leave-from-class="transform scale-100 opacity-100 translate-y-0"
                            leave-to-class="transform scale-95 opacity-0 -translate-y-2"
                        >
                            <div 
                                v-if="isCategoryDropdownOpen" 
                                class="absolute z-50 mt-1.5 w-full bg-white rounded-xl border border-gray-100 shadow-xl max-h-60 overflow-y-auto py-1.5 backdrop-blur-md bg-white/95 text-left"
                            >
                                <button
                                    type="button"
                                    @click="selectCategory('')"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-emerald-600 hover:text-white font-semibold transition-colors duration-150 flex items-center justify-between cursor-pointer group"
                                >
                                    <span>Semua kategori</span>
                                    <svg v-if="selectedCategory === ''" class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                <button
                                    v-for="cat in categories || []"
                                    :key="cat"
                                    type="button"
                                    @click="selectCategory(cat)"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-emerald-600 hover:text-white font-semibold transition-colors duration-150 flex items-center justify-between cursor-pointer group"
                                >
                                    <span>{{ cat }}</span>
                                    <svg v-if="selectedCategory === cat" class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </div>
                        </transition>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="relative w-full md:max-w-44 text-left" @mouseenter="isStatusDropdownOpen = true" @mouseleave="isStatusDropdownOpen = false">
                        <!-- Backdrop to close dropdown on click outside -->
                        <div v-if="isStatusDropdownOpen" class="fixed inset-0 z-40" @click="isStatusDropdownOpen = false" />

                        <button
                            type="button"
                            @click="isStatusDropdownOpen = !isStatusDropdownOpen"
                            class="w-full text-left pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 text-sm font-semibold text-gray-800 bg-white/70 backdrop-blur-xs cursor-pointer flex items-center justify-between capitalize relative z-50"
                        >
                            <span class="block truncate">{{ selectedStatus === 'published' ? 'Published' : (selectedStatus === 'draft' ? 'Draft' : 'Semua status') }}</span>
                            
                            <!-- Arrow Icon -->
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg 
                                    class="h-4 w-4 text-gray-400 transition-transform duration-300"
                                    :class="isStatusDropdownOpen ? 'rotate-180 text-primary' : ''"
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>

                        <!-- Dropdown List dengan Transisi Halus -->
                        <transition
                            enter-active-class="transition duration-150 ease-out"
                            enter-from-class="transform scale-95 opacity-0 -translate-y-2"
                            enter-to-class="transform scale-100 opacity-100 translate-y-0"
                            leave-active-class="transition duration-100 ease-in"
                            leave-from-class="transform scale-100 opacity-100 translate-y-0"
                            leave-to-class="transform scale-95 opacity-0 -translate-y-2"
                        >
                            <div 
                                v-if="isStatusDropdownOpen" 
                                class="absolute z-50 mt-1.5 w-full bg-white rounded-xl border border-gray-100 shadow-xl py-1.5 backdrop-blur-md bg-white/95 text-left"
                            >
                                <button
                                    type="button"
                                    @click="selectStatus('')"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-emerald-600 hover:text-white font-semibold transition-colors duration-150 flex items-center justify-between cursor-pointer group"
                                >
                                    <span>Semua status</span>
                                    <svg v-if="selectedStatus === ''" class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    @click="selectStatus('published')"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-emerald-600 hover:text-white font-semibold transition-colors duration-150 flex items-center justify-between cursor-pointer group"
                                >
                                    <span>Published</span>
                                    <svg v-if="selectedStatus === 'published'" class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    @click="selectStatus('draft')"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-emerald-600 hover:text-white font-semibold transition-colors duration-150 flex items-center justify-between cursor-pointer group"
                                >
                                    <span>Draft</span>
                                    <svg v-if="selectedStatus === 'draft'" class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </div>
                        </transition>
                    </div>
                    <template #actions>
                        <button type="button" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-50 hover:-translate-y-0.5 active:scale-95 focus:outline-none focus:ring-2 focus:ring-gray-100 transition-all duration-200 cursor-pointer" @click="resetFilters">
                            Reset
                        </button>
                    </template>
                </AdminTableToolbar>

                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/70">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase text-gray-500">Artikel</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase text-gray-500">Kategori</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase text-gray-500">Status</th>
                                    <th class="hidden md:table-cell px-6 py-4 text-left text-xs font-bold uppercase text-gray-500">Media</th>
                                    <th class="hidden sm:table-cell px-6 py-4 text-left text-xs font-bold uppercase text-gray-500">Dilihat</th>
                                    <th class="hidden md:table-cell px-6 py-4 text-left text-xs font-bold uppercase text-gray-500">Tanggal</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold uppercase text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody v-if="news.data.length" class="divide-y divide-gray-200 bg-white">
                                <tr v-for="item in news.data" :key="item.id" class="hover:bg-slate-50/70 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img
                                                v-if="item.thumbnail_url"
                                                :src="item.thumbnail_url"
                                                :alt="item.title"
                                                class="h-12 w-16 rounded-lg object-cover shadow-xs"
                                            >
                                            <div v-else class="flex h-12 w-16 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-[10px] font-bold text-gray-400 tracking-wider">
                                                NO IMG
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-gray-900 leading-snug">{{ item.title }}</p>
                                                <p class="truncate text-[10px] font-medium text-gray-400 mt-0.5">/{{ item.slug }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-600">{{ item.category || 'Tanpa Kategori' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <AdminStatusBadge :status="item.status" :label="item.status === 'published' ? 'Published' : 'Draft'" />
                                    </td>
                                    <td class="hidden md:table-cell whitespace-nowrap px-6 py-4 text-sm text-gray-600 font-medium">
                                        <div class="flex items-center gap-2">
                                            <span :class="item.thumbnail_url ? 'text-primary font-semibold' : 'text-gray-300'">Thumbnail</span>
                                            <span :class="item.pdf_url ? 'text-primary font-semibold' : 'text-gray-300'">PDF</span>
                                        </div>
                                    </td>
                                    <td class="hidden sm:table-cell whitespace-nowrap px-6 py-4 text-sm text-gray-600 font-medium">{{ item.view_count ?? 0 }}</td>
                                    <td class="hidden md:table-cell whitespace-nowrap px-6 py-4 text-sm text-gray-500 font-medium">{{ formatDate(item.published_at || item.created_at) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-bold">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a v-if="item.status === 'published'" :href="route('news.show', item.slug)" class="px-2.5 py-1.5 rounded-xl text-xs border border-gray-100 bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer" target="_blank">Lihat</a>
                                            <Link :href="route('admin.news.edit', item.slug)" class="px-2.5 py-1.5 rounded-xl text-xs border border-emerald-100 bg-emerald-50/50 text-emerald-700 hover:bg-emerald-100 transition-colors">Edit</Link>
                                            <button type="button" class="px-2.5 py-1.5 rounded-xl text-xs border border-red-100 bg-red-50/50 text-red-700 hover:bg-red-100 transition-colors cursor-pointer" @click="confirmDelete(item)">Hapus</button>
                                        </div>
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
                                    link.active ? 'border-primary bg-primary text-white' : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50',
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
            message="Apakah Anda yakin ingin menghapus berita ini secara permanen? Tindakan ini tidak dapat dibatalkan."
            @confirm="deleteNews"
            @close="showConfirmModal = false"
        />

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>