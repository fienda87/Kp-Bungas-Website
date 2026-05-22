<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';

const props = defineProps({
    categories: Array
});

const isModalOpen = ref(false);
const isEditing = ref(false);
const editingCategory = ref(null);
const showConfirmModal = ref(false);
const categoryToDelete = ref(null);
const toastMessage = ref('');
const toastType = ref('success');

const form = useForm({
    name: '',
});

const openCreateModal = () => {
    isEditing.value = false;
    editingCategory.value = null;
    form.reset();
    isModalOpen.value = true;
};

const openEditModal = (category) => {
    isEditing.value = true;
    editingCategory.value = category;
    form.name = category.name;
    isModalOpen.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route('admin.categories.update', editingCategory.value.id), {
            onSuccess: () => {
                isModalOpen.value = false;
                showToast('Kategori berhasil diperbarui');
            }
        });
    } else {
        form.post(route('admin.categories.store'), {
            onSuccess: () => {
                isModalOpen.value = false;
                showToast('Kategori berhasil ditambahkan');
            }
        });
    }
};

const confirmDelete = (category) => {
    categoryToDelete.value = category;
    showConfirmModal.value = true;
};

const deleteCategory = () => {
    form.delete(route('admin.categories.destroy', categoryToDelete.value.id), {
        onSuccess: () => {
            showConfirmModal.value = false;
            showToast('Kategori berhasil dihapus');
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
</script>

<template>
    <Head title="Kelola Kategori" />

    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Kategori</h2>
                <PrimaryButton @click="openCreateModal">
                    Tambah Kategori
                </PrimaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Berita</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="category in categories" :key="category.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ category.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ category.slug }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ category.news_count || 0 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="openEditModal(category)" class="text-primary hover:text-primary-dark mr-3">Edit</button>
                                        <button @click="confirmDelete(category)" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                                <tr v-if="categories.length === 0">
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada kategori.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Create/Edit -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="isModalOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form @submit.prevent="submitForm">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                {{ isEditing ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                            </h3>
                            <div class="mt-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                                <input type="text" v-model="form.name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm" required>
                                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                {{ isEditing ? 'Simpan Perubahan' : 'Tambah' }}
                            </PrimaryButton>
                            <button type="button" @click="isModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="showConfirmModal"
            title="Hapus Kategori?"
            message="Apakah Anda yakin ingin menghapus kategori ini? Semua artikel dalam kategori ini akan kehilangan kategorinya."
            @confirm="deleteCategory"
            @close="showConfirmModal = false"
        />

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>
