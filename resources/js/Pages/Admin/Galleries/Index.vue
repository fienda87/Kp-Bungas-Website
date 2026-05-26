<script setup>
import { ref, watch } from 'vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
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

const props = defineProps({
    galleries: Array
});

const page = usePage();
const showModal = ref(false);
const showConfirmModal = ref(false);
const isEditing = ref(false);
const selectedGallery = ref(null);
const toastMessage = ref('');
const toastType = ref('success');

const form = useForm({
    album_name: '',
    description: '',
    cover_image: null,
});

const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const openEditModal = (gallery) => {
    isEditing.value = true;
    selectedGallery.value = gallery;
    form.album_name = gallery.album_name;
    form.description = gallery.description || '';
    form.cover_image = null;
    form.clearErrors();
    showModal.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.post(route('admin.galleries.update', selectedGallery.value.id), {
            _method: 'put',
            forceFormData: true,
            onSuccess: () => {
                showModal.value = false;
                showToast('Album berhasil diperbarui');
            },
        });
    } else {
        form.post(route('admin.galleries.store'), {
            onSuccess: () => {
                showModal.value = false;
                showToast('Album berhasil dibuat');
            },
        });
    }
};

const confirmDelete = (gallery) => {
    selectedGallery.value = gallery;
    showConfirmModal.value = true;
};

const deleteGallery = () => {
    router.delete(route('admin.galleries.destroy', selectedGallery.value.id), {
        onSuccess: () => {
            showConfirmModal.value = false;
            showToast('Album berhasil dihapus');
        },
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
</script>

<template>
    <Head title="Manajemen Galeri" />

    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Galeri</h2>
                <PrimaryButton @click="openCreateModal">
                    Tambah Album Baru
                </PrimaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="galleries.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="gallery in galleries" :key="gallery.id" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                        <!-- Cover Image -->
                        <div class="h-48 bg-gray-100 relative">
                            <img v-if="gallery.cover_image_url" :src="gallery.cover_image_url" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <!-- Info -->
                        <div class="p-5 flex-grow">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-1">{{ gallery.album_name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ gallery.photos_count }} foto</p>
                            <p class="text-xs text-gray-400 mt-1">Dibuat {{ new Date(gallery.created_at).toLocaleDateString('id-ID') }}</p>
                            
                            <p v-if="gallery.description" class="text-sm text-gray-600 mt-3 line-clamp-2">
                                {{ gallery.description }}
                            </p>
                        </div>
                        <!-- Actions -->
                        <div class="p-5 pt-0 border-t border-gray-50 flex gap-2 mt-auto">
                            <Link :href="route('admin.galleries.show', gallery.id)" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Lihat</Link>
                            <button @click="openEditModal(gallery)" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Edit</button>
                            <button @click="confirmDelete(gallery)" class="text-sm text-red-600 hover:text-red-700 font-medium ml-auto">Hapus</button>
                        </div>
                    </div>
                </div>

                <div v-else class="bg-white p-12 rounded-xl border border-dashed border-gray-300 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada album</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat album galeri pertama Anda.</p>
                    <div class="mt-6">
                        <PrimaryButton @click="openCreateModal">
                            Tambah Album Baru
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ isEditing ? 'Edit Album' : 'Tambah Album Baru' }}
                </h2>

                <form @submit.prevent="submitForm" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="album_name" value="Nama Album" />
                        <TextInput
                            id="album_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.album_name"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.album_name" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Deskripsi" />
                        <textarea
                            id="description"
                            class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm"
                            v-model="form.description"
                            rows="3"
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>

                    <div>
                        <InputLabel for="cover_image" value="Cover Image" />
                        <FileUpload
                            id="cover_image"
                            v-model="form.cover_image"
                            accept="image/*"
                            :preview="isEditing ? selectedGallery.cover_image_url : null"
                        />
                        <InputError class="mt-2" :message="form.errors.cover_image" />
                    </div>

                    <div class="flex justify-end gap-3">
                        <SecondaryButton @click="showModal = false"> Batal </SecondaryButton>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Simpan
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <ConfirmModal
            :show="showConfirmModal"
            title="Hapus Album?"
            :message="`Apakah Anda yakin ingin menghapus album ${selectedGallery?.album_name}? Semua foto di dalamnya akan ikut terhapus.`"
            @confirm="deleteGallery"
            @close="showConfirmModal = false"
        />

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>
