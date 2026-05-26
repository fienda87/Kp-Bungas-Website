<script setup>
import { ref, watch } from 'vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';

const props = defineProps({
    gallery: Object
});

const page = usePage();
const showConfirmModal = ref(false);
const photoToDelete = ref(null);
const toastMessage = ref('');
const toastType = ref('success');
const imagePreviews = ref([]);

const uploadForm = useForm({
    photos: []
});

const handleFileChange = (e) => {
    const files = Array.from(e.target.files);
    uploadForm.photos = files;
    
    // Create previews
    imagePreviews.value = [];
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreviews.value.push(e.target.result);
        };
        reader.readAsDataURL(file);
    });
};

const submitUpload = () => {
    uploadForm.post(route('admin.galleries.photos.upload', props.gallery.id), {
        onSuccess: () => {
            uploadForm.reset();
            imagePreviews.value = [];
            showToast('Foto berhasil diunggah');
        },
    });
};

const confirmDeletePhoto = (photo) => {
    photoToDelete.value = photo;
    showConfirmModal.value = true;
};

const deletePhoto = () => {
    router.delete(route('admin.galleries.photos.destroy', photoToDelete.value.id), {
        onSuccess: () => {
            showConfirmModal.value = false;
            photoToDelete.value = null;
            showToast('Foto berhasil dihapus');
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

// Check for flash messages
watch(() => page.props.flash?.success, (message) => {
    if (message) {
        showToast(message);
    }
}, { immediate: true });

watch(() => page.props.flash?.error, (message) => {
    if (message) {
        showToast(message, 'error');
    }
}, { immediate: true });

const goBack = () => {
    router.visit(route('admin.galleries.index'));
};
</script>

<template>
    <Head :title="`Galeri: ${gallery.album_name}`" />

    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <button @click="goBack" class="p-2 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Album: {{ gallery.album_name }}</h2>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Album Header Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="md:flex">
                        <div class="md:w-1/3 h-64 md:h-auto bg-gray-100">
                            <img v-if="gallery.cover_image_url" :src="gallery.cover_image_url" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="md:w-2/3 p-8">
                            <h1 class="text-2xl font-bold text-gray-900">{{ gallery.album_name }}</h1>
                            <p class="text-sm text-gray-500 mt-1">Dibuat pada {{ new Date(gallery.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}</p>
                            
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 uppercase tracking-wider">Deskripsi</h4>
                                <p class="mt-2 text-gray-600 leading-relaxed">
                                    {{ gallery.description || 'Tidak ada deskripsi untuk album ini.' }}
                                </p>
                            </div>

                            <div class="mt-6 flex items-center gap-4">
                                <div class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100 inline-flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-semibold">{{ gallery.photos.length }} Foto</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Section -->
                <div class="bg-white p-6 shadow-sm rounded-xl border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Foto Baru</h3>
                    
                    <form @submit.prevent="submitUpload" class="space-y-4">
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau seret dan lepas</p>
                                    <p class="text-xs text-gray-500">PNG, JPG atau WEBP (Multiple allowed)</p>
                                </div>
                                <input type="file" class="hidden" multiple accept="image/*" @change="handleFileChange" />
                            </label>
                        </div>

                        <!-- Preview -->
                        <div v-if="imagePreviews.length > 0" class="space-y-4">
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                <div v-for="(preview, index) in imagePreviews" :key="index" class="relative aspect-square">
                                    <img :src="preview" class="w-full h-full object-cover rounded-lg border border-gray-200" />
                                </div>
                            </div>
                            <div class="flex justify-end gap-3">
                                <SecondaryButton @click="imagePreviews = []; uploadForm.reset();">Batal</SecondaryButton>
                                <PrimaryButton :disabled="uploadForm.processing">
                                    <span v-if="uploadForm.processing">Mengunggah...</span>
                                    <span v-else>Unggah {{ uploadForm.photos.length }} Foto</span>
                                </PrimaryButton>
                            </div>
                        </div>
                        <div v-if="uploadForm.errors.photos" class="text-sm text-red-600">
                            {{ uploadForm.errors.photos }}
                        </div>
                    </form>
                </div>

                <!-- Photo Grid -->
                <div class="bg-white p-6 shadow-sm rounded-xl border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Koleksi Foto</h3>
                    
                    <div v-if="gallery.photos.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <div v-for="photo in gallery.photos" :key="photo.id" class="group relative aspect-square overflow-hidden rounded-xl bg-gray-100 border border-gray-200">
                            <img :src="photo.image_url" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" />
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button 
                                    @click="confirmDeletePhoto(photo)"
                                    class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 shadow-lg"
                                    title="Hapus Foto"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>

                            <div v-if="photo.caption" class="absolute bottom-0 inset-x-0 p-2 bg-black/60 text-white text-xs truncate">
                                {{ photo.caption }}
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center py-12">
                        <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">Belum ada foto dalam album ini.</p>
                        <p class="text-gray-400 text-sm mt-1">Gunakan form di atas untuk mengunggah foto pertama Anda!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Delete Photo Modal -->
        <ConfirmModal
            :show="showConfirmModal"
            title="Hapus Foto?"
            message="Apakah Anda yakin ingin menghapus foto ini? Tindakan ini tidak dapat dibatalkan."
            @confirm="deletePhoto"
            @close="showConfirmModal = false"
        />

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>
