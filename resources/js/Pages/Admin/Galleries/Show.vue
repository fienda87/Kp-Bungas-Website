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
                    <div class="grid gap-6 md:grid-cols-[240px_1fr]">
                        <div class="h-44 overflow-hidden rounded-2xl bg-gray-100 shrink-0 shadow-xs border border-gray-100">
                            <img v-if="gallery.cover_image_url" :src="gallery.cover_image_url" :alt="gallery.album_name" class="h-full w-full object-cover">
                            <div v-else class="flex h-full w-full items-center justify-center text-xs font-bold text-gray-400 tracking-wider">BELUM ADA COVER</div>
                        </div>
                        <div class="flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 tracking-tight">{{ gallery.album_name }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ gallery.description || 'Tidak ada deskripsi untuk album ini.' }}</p>
                            </div>
                            <div class="mt-4 grid gap-3 grid-cols-3">
                                <div class="rounded-xl border border-gray-100/80 p-3 bg-gray-50/20">
                                    <p class="text-[9px] font-extrabold uppercase tracking-wider text-gray-400">Total Foto</p>
                                    <p class="mt-1 text-xl font-black text-gray-900 leading-tight">{{ gallery.photos.length }}</p>
                                </div>
                                <div class="rounded-xl border border-gray-100/80 p-3 bg-gray-50/20">
                                    <p class="text-[9px] font-extrabold uppercase tracking-wider text-gray-400">Cover Album</p>
                                    <p class="mt-1 text-xs font-semibold text-gray-900 leading-tight">{{ gallery.cover_image_url ? 'Aktif' : 'Belum Ada' }}</p>
                                </div>
                                <div class="rounded-xl border border-gray-100/80 p-3 bg-gray-50/20">
                                    <p class="text-[9px] font-extrabold uppercase tracking-wider text-gray-400">Terakhir Update</p>
                                    <p class="mt-1 text-xs font-semibold text-gray-900 leading-tight">{{ formatDate(gallery.updated_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </AdminSectionCard>

                <AdminSectionCard title="Upload Foto Baru" description="Pilih beberapa foto sekaligus. Maksimal 5MB per file.">
                    <form @submit.prevent="submitUpload" class="space-y-4">
                        <label class="group flex min-h-36 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50/50 px-4 py-8 text-center hover:border-primary-light/40 hover:bg-primary-extralight/20 transition-all duration-300">
                            <span class="text-sm font-bold text-gray-700 group-hover:text-primary transition-colors">Klik untuk memilih foto</span>
                            <span class="mt-1.5 text-xs text-gray-400">Format PNG, JPG, GIF, WEBP (Maksimal 5MB)</span>
                            <input type="file" class="hidden" multiple accept="image/*" @change="handleFileChange">
                        </label>

                        <div v-if="imagePreviews.length" class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-6">
                            <div v-for="preview in imagePreviews" :key="preview.url" class="overflow-hidden rounded-xl border border-gray-100 bg-white">
                                <img :src="preview.url" :alt="preview.name" class="aspect-square w-full object-cover">
                                <p class="truncate px-2 py-1.5 text-[10px] font-semibold text-gray-500">{{ preview.name }}</p>
                            </div>
                        </div>

                        <p v-if="fileError || uploadForm.errors.photos" class="text-sm text-red-600">
                            {{ fileError || uploadForm.errors.photos }}
                        </p>

                        <div class="flex justify-end gap-3 pt-2">
                            <SecondaryButton type="button" class="rounded-xl" @click="resetUpload">Batal</SecondaryButton>
                            <PrimaryButton type="submit" class="rounded-xl font-bold" :disabled="uploadForm.processing || !uploadForm.photos.length" :class="{ 'opacity-50': uploadForm.processing || !uploadForm.photos.length }">
                                {{ uploadForm.processing ? 'Mengunggah...' : `Unggah ${uploadForm.photos.length} Foto` }}
                            </PrimaryButton>
                        </div>
                    </form>
                </AdminSectionCard>

                <AdminSectionCard title="Koleksi Foto" description="Atur cover, caption, urutan, dan hapus foto album.">
                    <div v-if="gallery.photos.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <article v-for="(photo, index) in gallery.photos" :key="photo.id" class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-xs hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary-light/5 transition-all duration-300 flex flex-col justify-between">
                            <div class="relative overflow-hidden shrink-0">
                                <img :src="photo.image_url" :alt="photo.caption || gallery.album_name" class="aspect-[4/3] w-full object-cover group-hover:scale-103 transition-transform duration-500">
                                <div v-if="isCurrentCover(photo)" class="absolute left-3 top-3 z-10">
                                    <AdminStatusBadge status="active" label="Cover" />
                                </div>
                            </div>

                            <div class="space-y-4 p-4 flex-1 flex flex-col justify-between bg-white">
                                <div>
                                    <label :for="`caption-${photo.id}`" class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Caption Foto</label>
                                    <textarea
                                        :id="`caption-${photo.id}`"
                                        v-model="captionDrafts[photo.id]"
                                        rows="2"
                                        class="mt-1 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary focus:ring-primary px-3 py-2 bg-gray-50/50 placeholder:text-gray-300"
                                        placeholder="Tulis caption foto..."
                                    ></textarea>
                                </div>

                                <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-gray-50">
                                    <button
                                        type="button"
                                        class="rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-100 px-2.5 py-1.5 text-xs font-bold text-gray-700 disabled:opacity-50 disabled:pointer-events-none transition-colors duration-150"
                                        :disabled="actionLoading === `caption-${photo.id}`"
                                        @click="updateCaption(photo)"
                                    >
                                        Simpan
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-xl border border-emerald-100 bg-emerald-50/50 hover:bg-emerald-100 px-2.5 py-1.5 text-xs font-bold text-emerald-700 disabled:opacity-50 disabled:pointer-events-none transition-colors duration-150"
                                        :disabled="isCurrentCover(photo) || actionLoading === `cover-${photo.id}`"
                                        @click="setCover(photo)"
                                    >
                                        Cover
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-100 px-2.5 py-1.5 text-xs font-bold text-gray-700 disabled:opacity-50 disabled:pointer-events-none transition-all duration-150"
                                        :disabled="index === 0 || actionLoading === 'reorder'"
                                        @click="movePhoto(index, -1)"
                                        title="Pindahkan ke atas"
                                    >
                                        &uarr;
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-gray-100 px-2.5 py-1.5 text-xs font-bold text-gray-700 disabled:opacity-50 disabled:pointer-events-none transition-all duration-150"
                                        :disabled="index === gallery.photos.length - 1 || actionLoading === 'reorder'"
                                        @click="movePhoto(index, 1)"
                                        title="Pindahkan ke bawah"
                                    >
                                        &darr;
                                    </button>
                                    <button
                                        type="button"
                                        class="ml-auto rounded-xl border border-red-100 bg-red-50/50 hover:bg-red-100 px-2.5 py-1.5 text-xs font-bold text-red-600 cursor-pointer transition-colors duration-150"
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
