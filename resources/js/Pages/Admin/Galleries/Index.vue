<script setup>
import { ref, watch } from 'vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';
import FileUpload from '@/Components/Admin/FileUpload.vue';
import AdminEmptyState from '@/Components/Admin/AdminEmptyState.vue';
import AdminStatusBadge from '@/Components/Admin/AdminStatusBadge.vue';
import AdminTableToolbar from '@/Components/Admin/AdminTableToolbar.vue';

const props = defineProps({
    galleries: Array,
    filters: Object,
});

const page = usePage();
const search = ref(props.filters?.search || '');
const showModal = ref(false);
const showConfirmModal = ref(false);
const isEditing = ref(false);
const selectedGallery = ref(null);
const toastMessage = ref('');
const toastType = ref('success');
const isDeleting = ref(false);
const uploadError = ref('');

const form = useForm({
    album_name: '',
    description: '',
    cover_image: null,
});

watch(search, debounce(() => {
    router.get(route('admin.galleries.index'), {
        search: search.value,
    }, {
        preserveState: true,
        replace: true,
    });
}, 300));

const openCreateModal = () => {
    isEditing.value = false;
    selectedGallery.value = null;
    uploadError.value = '';
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const openEditModal = (gallery) => {
    isEditing.value = true;
    selectedGallery.value = gallery;
    uploadError.value = '';
    form.album_name = gallery.album_name;
    form.description = gallery.description || '';
    form.cover_image = null;
    form.clearErrors();
    showModal.value = true;
};

const submitForm = () => {
    if (uploadError.value) {
        showToast('Periksa kembali cover album.', 'error');
        return;
    }

    if (isEditing.value) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.galleries.update', selectedGallery.value.id), {
            forceFormData: true,
            onSuccess: () => {
                showModal.value = false;
                showToast('Album berhasil diperbarui');
            },
            onError: () => showToast('Terjadi kesalahan saat memperbarui album', 'error'),
        });
    } else {
        form.transform((data) => data).post(route('admin.galleries.store'), {
            forceFormData: true,
            onSuccess: () => {
                showModal.value = false;
                showToast('Album berhasil dibuat');
            },
            onError: () => showToast('Terjadi kesalahan saat membuat album', 'error'),
        });
    }
};

const confirmDelete = (gallery) => {
    selectedGallery.value = gallery;
    showConfirmModal.value = true;
};

const deleteGallery = () => {
    isDeleting.value = true;
    router.delete(route('admin.galleries.destroy', selectedGallery.value.id), {
        onSuccess: () => {
            showConfirmModal.value = false;
            showToast('Album berhasil dihapus');
        },
        onError: () => showToast('Terjadi kesalahan saat menghapus album', 'error'),
        onFinish: () => {
            isDeleting.value = false;
        },
    });
};

const resetFilters = () => {
    search.value = '';
    router.get(route('admin.galleries.index'), {}, {
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

const formatDate = (value) => {
    if (!value) return '-';
    return new Date(value).toLocaleDateString('id-ID', {
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
    <Head title="Manajemen Galeri" />

    <AdminLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">Manajemen Galeri</h2>
                    <p class="mt-1 text-sm text-gray-500">Kelola album, cover, dan koleksi foto kegiatan.</p>
                </div>
                <PrimaryButton @click="openCreateModal">Tambah Album</PrimaryButton>
            </div>
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
                <AdminTableToolbar>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari nama atau deskripsi album..."
                        class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-primary focus:ring-primary md:max-w-md px-4 py-2.5 bg-white/70"
                    >
                    <template #actions>
                        <button type="button" class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-50 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 cursor-pointer" @click="resetFilters">
                            Reset
                        </button>
                    </template>
                </AdminTableToolbar>

                <div v-if="galleries.length" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <article v-for="gallery in galleries" :key="gallery.id" class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-xs hover:-translate-y-1 hover:shadow-md hover:shadow-primary-light/5 transition-all duration-300 flex flex-col">
                        <div class="relative h-48 bg-gray-100 overflow-hidden shrink-0">
                            <img 
                                v-if="gallery.cover_image_url" 
                                :src="gallery.cover_image_url" 
                                :alt="gallery.album_name" 
                                class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                            <div v-else class="flex h-full w-full items-center justify-center text-sm font-bold text-gray-400 tracking-wider">BELUM ADA COVER</div>
                            <div class="absolute left-3 top-3 z-10">
                                <AdminStatusBadge :status="gallery.photos_count > 0 ? 'active' : 'empty'" :label="gallery.photos_count > 0 ? 'Aktif' : 'Kosong'" />
                            </div>
                        </div>
                        <div class="space-y-4 p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="truncate text-base font-bold text-gray-900 tracking-tight transition-colors group-hover:text-primary">{{ gallery.album_name }}</h3>
                                <p class="mt-1 text-[11px] font-semibold text-gray-400">{{ gallery.photos_count }} foto · {{ formatDate(gallery.created_at) }}</p>
                                <p class="mt-3 line-clamp-2 text-sm text-gray-500 leading-relaxed">{{ gallery.description || 'Tidak ada deskripsi.' }}</p>
                            </div>
                            <div class="flex items-center gap-2 border-t border-gray-50 pt-4 mt-auto">
                                <Link :href="route('admin.galleries.show', gallery.id)" class="px-3 py-1.5 rounded-xl text-xs font-bold border border-emerald-100 bg-emerald-50/50 text-emerald-700 hover:bg-emerald-100 transition-colors">Kelola</Link>
                                <button type="button" class="px-3 py-1.5 rounded-xl text-xs font-bold border border-blue-100 bg-blue-50/50 text-blue-700 hover:bg-blue-100 transition-colors cursor-pointer" @click="openEditModal(gallery)">Edit</button>
                                <button type="button" class="ml-auto px-3 py-1.5 rounded-xl text-xs font-bold border border-red-100 bg-red-50/50 text-red-700 hover:bg-red-100 transition-colors cursor-pointer" @click="confirmDelete(gallery)">Hapus</button>
                            </div>
                        </div>
                    </article>
                </div>

                <AdminEmptyState
                    v-else
                    title="Belum ada album"
                    message="Buat album pertama untuk mulai mengelola foto galeri."
                >
                    <template #actions>
                        <PrimaryButton @click="openCreateModal">Tambah Album</PrimaryButton>
                    </template>
                </AdminEmptyState>
            </div>
        </div>

        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                    {{ isEditing ? 'Edit Album' : 'Tambah Album Baru' }}
                </h2>

                <form @submit.prevent="submitForm" class="mt-6 space-y-5">
                    <div>
                        <InputLabel for="album_name" value="Nama Album" />
                        <TextInput id="album_name" v-model="form.album_name" type="text" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required autofocus />
                        <InputError class="mt-2" :message="form.errors.album_name" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Deskripsi" />
                        <textarea
                            id="description"
                            v-model="form.description"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring-primary text-sm px-4 py-2.5 bg-gray-50/50"
                            rows="3"
                            placeholder="Tuliskan deskripsi singkat mengenai isi album..."
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>

                    <div>
                        <InputLabel for="cover_image" value="Cover Album" />
                        <FileUpload
                            v-model="form.cover_image"
                            accept="image/*"
                            :max-size-mb="5"
                            helper-text="JPG, PNG, GIF, WEBP hingga 5MB"
                            :preview-url="isEditing ? selectedGallery?.cover_image_url : ''"
                            preview
                            :error="uploadError || form.errors.cover_image"
                            @validation-error="uploadError = $event"
                        />
                    </div>

                    <div class="flex justify-end gap-3 pt-3">
                        <SecondaryButton type="button" class="rounded-xl" @click="showModal = false">Batal</SecondaryButton>
                        <PrimaryButton type="submit" class="rounded-xl" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <ConfirmModal
            :show="showConfirmModal"
            :loading="isDeleting"
            title="Hapus Album?"
            :message="`Apakah Anda yakin ingin menghapus album ${selectedGallery?.album_name || ''}? Semua foto di dalamnya akan ikut terhapus.`"
            @confirm="deleteGallery"
            @close="showConfirmModal = false"
        />

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>
