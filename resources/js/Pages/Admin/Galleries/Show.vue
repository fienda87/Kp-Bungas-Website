<script setup>
import { ref, watch } from 'vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';
import AdminEmptyState from '@/Components/Admin/AdminEmptyState.vue';
import AdminSectionCard from '@/Components/Admin/AdminSectionCard.vue';
import AdminStatusBadge from '@/Components/Admin/AdminStatusBadge.vue';
import { validateSelectedFile } from '@/utils/file';

const props = defineProps({
    gallery: Object,
});

const page = usePage();
const showConfirmModal = ref(false);
const photoToDelete = ref(null);
const toastMessage = ref('');
const toastType = ref('success');
const imagePreviews = ref([]);
const fileError = ref('');
const actionLoading = ref(null);
const captionDrafts = ref(Object.fromEntries((props.gallery.photos || []).map((photo) => [photo.id, photo.caption || ''])));

const uploadForm = useForm({
    photos: [],
});

const captionForm = useForm({
    caption: '',
});

const handleFileChange = (event) => {
    const files = Array.from(event.target.files || []);
    fileError.value = '';
    imagePreviews.value = [];

    const invalidFile = files.find((file) => validateSelectedFile(file, 'image/*', 5));
    if (invalidFile) {
        fileError.value = validateSelectedFile(invalidFile, 'image/*', 5);
        uploadForm.photos = [];
        event.target.value = '';
        return;
    }

    uploadForm.photos = files;
    imagePreviews.value = files.map((file) => ({
        name: file.name,
        url: URL.createObjectURL(file),
    }));
};

const resetUpload = () => {
    uploadForm.reset();
    imagePreviews.value = [];
    fileError.value = '';
};

const submitUpload = () => {
    if (!uploadForm.photos.length) {
        fileError.value = 'Pilih minimal satu foto.';
        return;
    }

    uploadForm.post(route('admin.galleries.photos.upload', props.gallery.id), {
        forceFormData: true,
        onSuccess: () => {
            resetUpload();
            showToast('Foto berhasil diunggah');
        },
        onError: () => showToast('Terjadi kesalahan saat mengunggah foto', 'error'),
    });
};

const confirmDeletePhoto = (photo) => {
    photoToDelete.value = photo;
    showConfirmModal.value = true;
};

const deletePhoto = () => {
    actionLoading.value = `delete-${photoToDelete.value.id}`;
    router.delete(route('admin.galleries.photos.destroy', photoToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showConfirmModal.value = false;
            photoToDelete.value = null;
            showToast('Foto berhasil dihapus');
        },
        onError: () => showToast('Terjadi kesalahan saat menghapus foto', 'error'),
        onFinish: () => {
            actionLoading.value = null;
        },
    });
};

const updateCaption = (photo) => {
    captionForm.caption = captionDrafts.value[photo.id] || '';
    actionLoading.value = `caption-${photo.id}`;
    captionForm.patch(route('admin.galleries.photos.caption', photo.id), {
        preserveScroll: true,
        onSuccess: () => showToast('Caption berhasil disimpan'),
        onError: () => showToast('Terjadi kesalahan saat menyimpan caption', 'error'),
        onFinish: () => {
            actionLoading.value = null;
        },
    });
};

const setCover = (photo) => {
    actionLoading.value = `cover-${photo.id}`;
    router.post(route('admin.galleries.cover.from-photo', [props.gallery.id, photo.id]), {}, {
        preserveScroll: true,
        onSuccess: () => showToast('Cover album berhasil diperbarui'),
        onError: () => showToast('Terjadi kesalahan saat memperbarui cover', 'error'),
        onFinish: () => {
            actionLoading.value = null;
        },
    });
};

const movePhoto = (index, direction) => {
    const targetIndex = index + direction;
    if (targetIndex < 0 || targetIndex >= props.gallery.photos.length || actionLoading.value) {
        return;
    }

    const reordered = [...props.gallery.photos];
    [reordered[index], reordered[targetIndex]] = [reordered[targetIndex], reordered[index]];

    actionLoading.value = 'reorder';
    router.post(route('admin.galleries.photos.reorder', props.gallery.id), {
        photos: reordered.map((photo, photoIndex) => ({
            id: photo.id,
            order: photoIndex + 1,
        })),
    }, {
        preserveScroll: true,
        onSuccess: () => showToast('Urutan foto berhasil diperbarui'),
        onError: () => showToast('Terjadi kesalahan saat mengubah urutan foto', 'error'),
        onFinish: () => {
            actionLoading.value = null;
        },
    });
};

const isCurrentCover = (photo) => props.gallery.cover_image === photo.image_path || props.gallery.cover_image_url === photo.image_url;

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
    <Head :title="`Galeri: ${gallery.album_name}`" />

    <AdminLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <Link :href="route('admin.galleries.index')" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                        &larr; Kembali ke Galeri
                    </Link>
                    <h2 class="mt-2 text-xl font-semibold leading-tight text-gray-800">{{ gallery.album_name }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ gallery.photos.length }} foto · dibuat {{ formatDate(gallery.created_at) }}</p>
                </div>
                <AdminStatusBadge :status="gallery.photos.length ? 'active' : 'empty'" :label="gallery.photos.length ? 'Aktif' : 'Kosong'" />
            </div>
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <AdminSectionCard title="Informasi Album">
                    <div class="grid gap-5 md:grid-cols-[260px_1fr]">
                        <div class="h-48 overflow-hidden rounded-lg bg-gray-100">
                            <img v-if="gallery.cover_image_url" :src="gallery.cover_image_url" :alt="gallery.album_name" class="h-full w-full object-cover">
                            <div v-else class="flex h-full w-full items-center justify-center text-sm font-semibold text-gray-400">Belum Ada Cover</div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ gallery.album_name }}</h3>
                            <p class="mt-2 text-sm leading-6 text-gray-600">{{ gallery.description || 'Tidak ada deskripsi untuk album ini.' }}</p>
                            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-lg border border-gray-100 p-3">
                                    <p class="text-xs font-semibold uppercase text-gray-500">Foto</p>
                                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ gallery.photos.length }}</p>
                                </div>
                                <div class="rounded-lg border border-gray-100 p-3">
                                    <p class="text-xs font-semibold uppercase text-gray-500">Cover</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ gallery.cover_image_url ? 'Tersedia' : 'Belum ada' }}</p>
                                </div>
                                <div class="rounded-lg border border-gray-100 p-3">
                                    <p class="text-xs font-semibold uppercase text-gray-500">Update</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ formatDate(gallery.updated_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </AdminSectionCard>

                <AdminSectionCard title="Upload Foto Baru" description="Pilih beberapa foto sekaligus. Maksimal 5MB per file.">
                    <form @submit.prevent="submitUpload" class="space-y-4">
                        <label class="flex min-h-32 cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-center hover:bg-gray-100">
                            <span class="text-sm font-semibold text-gray-700">Klik untuk memilih foto</span>
                            <span class="mt-1 text-xs text-gray-500">PNG, JPG, GIF, WEBP</span>
                            <input type="file" class="hidden" multiple accept="image/*" @change="handleFileChange">
                        </label>

                        <div v-if="imagePreviews.length" class="grid grid-cols-2 gap-3 md:grid-cols-4 lg:grid-cols-6">
                            <div v-for="preview in imagePreviews" :key="preview.url" class="overflow-hidden rounded-lg border border-gray-100 bg-white">
                                <img :src="preview.url" :alt="preview.name" class="aspect-square w-full object-cover">
                                <p class="truncate px-2 py-1 text-xs text-gray-500">{{ preview.name }}</p>
                            </div>
                        </div>

                        <p v-if="fileError || uploadForm.errors.photos" class="text-sm text-red-600">
                            {{ fileError || uploadForm.errors.photos }}
                        </p>

                        <div class="flex justify-end gap-3">
                            <SecondaryButton type="button" @click="resetUpload">Batal</SecondaryButton>
                            <PrimaryButton type="submit" :disabled="uploadForm.processing || !uploadForm.photos.length" :class="{ 'opacity-50': uploadForm.processing || !uploadForm.photos.length }">
                                {{ uploadForm.processing ? 'Mengunggah...' : `Unggah ${uploadForm.photos.length} Foto` }}
                            </PrimaryButton>
                        </div>
                    </form>
                </AdminSectionCard>

                <AdminSectionCard title="Koleksi Foto" description="Atur cover, caption, urutan, dan hapus foto album.">
                    <div v-if="gallery.photos.length" class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        <article v-for="(photo, index) in gallery.photos" :key="photo.id" class="overflow-hidden rounded-lg border border-gray-100 bg-white shadow-sm">
                            <div class="relative">
                                <img :src="photo.image_url" :alt="photo.caption || gallery.album_name" class="aspect-[4/3] w-full object-cover">
                                <div v-if="isCurrentCover(photo)" class="absolute left-3 top-3">
                                    <AdminStatusBadge status="active" label="Cover" />
                                </div>
                            </div>

                            <div class="space-y-4 p-4">
                                <div>
                                    <label :for="`caption-${photo.id}`" class="text-xs font-semibold uppercase text-gray-500">Caption</label>
                                    <textarea
                                        :id="`caption-${photo.id}`"
                                        v-model="captionDrafts[photo.id]"
                                        rows="2"
                                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                        placeholder="Tulis caption foto..."
                                    ></textarea>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <button
                                        type="button"
                                        class="rounded-md border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                                        :disabled="actionLoading === `caption-${photo.id}`"
                                        @click="updateCaption(photo)"
                                    >
                                        Simpan Caption
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-md border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                                        :disabled="isCurrentCover(photo) || actionLoading === `cover-${photo.id}`"
                                        @click="setCover(photo)"
                                    >
                                        Jadikan Cover
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-md border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                                        :disabled="index === 0 || actionLoading === 'reorder'"
                                        @click="movePhoto(index, -1)"
                                    >
                                        Naik
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-md border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                                        :disabled="index === gallery.photos.length - 1 || actionLoading === 'reorder'"
                                        @click="movePhoto(index, 1)"
                                    >
                                        Turun
                                    </button>
                                    <button
                                        type="button"
                                        class="ml-auto rounded-md border border-red-100 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50"
                                        @click="confirmDeletePhoto(photo)"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </article>
                    </div>

                    <AdminEmptyState
                        v-else
                        title="Album belum memiliki foto"
                        message="Upload foto pertama agar album ini tampil lengkap di galeri."
                    />
                </AdminSectionCard>
            </div>
        </div>

        <ConfirmModal
            :show="showConfirmModal"
            :loading="actionLoading === `delete-${photoToDelete?.id}`"
            title="Hapus Foto?"
            message="Apakah Anda yakin ingin menghapus foto ini? Jika foto ini menjadi cover, cover akan dipindahkan otomatis."
            @confirm="deletePhoto"
            @close="showConfirmModal = false"
        />

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>
