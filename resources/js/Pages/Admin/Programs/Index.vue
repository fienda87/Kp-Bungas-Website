<script setup>
import { ref, watch } from 'vue';
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import FileUpload from '@/Components/Admin/FileUpload.vue';

const props = defineProps({
    programs: Array,
});

const page = usePage();
const showFormModal = ref(false);
const isEditing = ref(false);
const editingProgram = ref(null);
const showConfirmModal = ref(false);
const itemToDelete = ref(null);
const toastMessage = ref('');
const toastType = ref('success');
const reorderLoading = ref(false);
const deleting = ref(false);
const uploadError = ref('');

const form = useForm({
    title: '',
    description: '',
    icon: '',
    image: null,
    is_active: true,
    order: 0,
});

const resetForm = () => {
    form.reset();
    form.clearErrors();
    form.is_active = true;
    form.order = 0;
    form.image = null;
    uploadError.value = '';
};

const openCreateModal = () => {
    resetForm();
    isEditing.value = false;
    editingProgram.value = null;
    form.order = props.programs.length + 1;
    showFormModal.value = true;
};

const openEditModal = (program) => {
    isEditing.value = true;
    editingProgram.value = program;
    form.title = program.title;
    form.description = program.description;
    form.icon = program.icon || '';
    form.is_active = program.is_active;
    form.order = program.order;
    form.image = null;
    form.clearErrors();
    uploadError.value = '';
    showFormModal.value = true;
};

const setUploadError = (message) => {
    uploadError.value = message;
};

const submitForm = () => {
    if (uploadError.value) {
        showToast('Periksa kembali file gambar program.', 'error');
        return;
    }

    if (isEditing.value && editingProgram.value) {
        form.transform((data) => ({
            ...data,
            _method: 'put',
        })).post(route('admin.programs.update', editingProgram.value.id), {
            forceFormData: true,
            onSuccess: () => {
                showFormModal.value = false;
                showToast('Program berhasil diperbarui');
            },
            onError: () => {
                showToast('Terjadi kesalahan saat memperbarui program', 'error');
            },
            preserveScroll: true,
        });
    } else {
        form.transform((data) => data).post(route('admin.programs.store'), {
            forceFormData: true,
            onSuccess: () => {
                showFormModal.value = false;
                showToast('Program berhasil ditambahkan');
            },
            onError: () => {
                showToast('Terjadi kesalahan saat menambahkan program', 'error');
            },
            preserveScroll: true,
        });
    }
};

const closeModal = () => {
    showFormModal.value = false;
    resetForm();
    uploadError.value = '';
};

const confirmDelete = (item) => {
    itemToDelete.value = item;
    showConfirmModal.value = true;
};

const deleteProgram = () => {
    deleting.value = true;
    router.delete(route('admin.programs.destroy', itemToDelete.value.id), {
        onSuccess: () => {
            showConfirmModal.value = false;
            itemToDelete.value = null;
            showToast('Program berhasil dihapus');
        },
        onError: () => {
            showToast('Terjadi kesalahan saat menghapus program', 'error');
        },
        onFinish: () => {
            deleting.value = false;
        },
        preserveScroll: true,
    });
};

const moveUp = (index) => {
    if (index <= 0 || reorderLoading.value) return;
    const programsCopy = [...props.programs];
    const reorderData = programsCopy.map((p, i) => ({
        id: p.id,
        order: i === index ? index : i === index - 1 ? index + 1 : i + 1,
    }));
    reorderLoading.value = true;
    router.post(route('admin.programs.reorder'), {
        programs: reorderData,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showToast('Urutan program berhasil diubah');
        },
        onError: () => {
            showToast('Terjadi kesalahan saat mengubah urutan program', 'error');
        },
        onFinish: () => {
            reorderLoading.value = false;
        },
    });
};

const moveDown = (index) => {
    if (index >= props.programs.length - 1 || reorderLoading.value) return;
    const programsCopy = [...props.programs];
    const reorderData = programsCopy.map((p, i) => ({
        id: p.id,
        order: i === index ? index + 2 : i === index + 1 ? index + 1 : i + 1,
    }));
    reorderLoading.value = true;
    router.post(route('admin.programs.reorder'), {
        programs: reorderData,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showToast('Urutan program berhasil diubah');
        },
        onError: () => {
            showToast('Terjadi kesalahan saat mengubah urutan program', 'error');
        },
        onFinish: () => {
            reorderLoading.value = false;
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

// Handle flash messages from server
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
    <Head title="Manajemen Program" />

    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Program</h2>
                <PrimaryButton @click="openCreateModal">
                    Tambah Program Baru
                </PrimaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(program, index) in programs" :key="program.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ program.title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span v-if="program.icon" class="text-lg">{{ program.icon }}</span>
                                        <span v-else class="text-gray-400">-</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img
                                            v-if="program.image_url"
                                            :src="program.image_url"
                                            :alt="program.title"
                                            class="h-10 w-10 object-cover rounded"
                                        />
                                        <span v-else class="text-gray-400 text-sm">-</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                program.is_active
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-gray-100 text-gray-800'
                                            ]"
                                        >
                                            {{ program.is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex items-center gap-1">
                                            <span class="mr-1">{{ program.order }}</span>
                                            <button
                                                @click="moveUp(index)"
                                                :class="[
                                                    'p-1 rounded hover:bg-gray-100 focus:outline-none',
                                                    index === 0 || reorderLoading ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:text-gray-900'
                                                ]"
                                                :disabled="index === 0 || reorderLoading"
                                                title="Naik"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            </button>
                                            <button
                                                @click="moveDown(index)"
                                                :class="[
                                                    'p-1 rounded hover:bg-gray-100 focus:outline-none',
                                                    index === programs.length - 1 || reorderLoading ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:text-gray-900'
                                                ]"
                                                :disabled="index === programs.length - 1 || reorderLoading"
                                                title="Turun"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button
                                            @click="openEditModal(program)"
                                            class="text-blue-600 hover:text-blue-900 mr-3"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="confirmDelete(program)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="programs.length === 0">
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada program.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <Modal :show="showFormModal" maxWidth="lg" @close="closeModal">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    {{ isEditing ? 'Edit Program' : 'Tambah Program Baru' }}
                </h3>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <!-- Title -->
                    <div>
                        <InputLabel for="title" value="Title" />
                        <TextInput
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError :message="form.errors.title" />
                    </div>

                    <!-- Description -->
                    <div>
                        <InputLabel for="description" value="Deskripsi" />
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        ></textarea>
                        <InputError :message="form.errors.description" />
                    </div>

                    <!-- Icon -->
                    <div>
                        <InputLabel for="icon" value="Icon" />
                        <TextInput
                            id="icon"
                            v-model="form.icon"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Contoh: 🌱"
                        />
                        <InputError :message="form.errors.icon" />
                    </div>

                    <!-- Image -->
                    <div>
                        <InputLabel for="image" value="Gambar" />
                        <FileUpload
                            v-model="form.image"
                            accept="image/*"
                            :max-size-mb="5"
                            helper-text="JPG, PNG, GIF, WEBP hingga 5MB"
                            :preview-url="isEditing ? editingProgram?.image_url : ''"
                            preview
                            :error="uploadError || form.errors.image"
                            @validation-error="setUploadError"
                        />
                    </div>

                    <!-- Order -->
                    <div>
                        <InputLabel for="order" value="Urutan" />
                        <TextInput
                            id="order"
                            v-model="form.order"
                            type="number"
                            class="mt-1 block w-full"
                            min="1"
                        />
                        <InputError :message="form.errors.order" />
                    </div>

                    <!-- Is Active -->
                    <div class="flex items-center gap-2">
                        <input
                            id="is_active"
                            v-model="form.is_active"
                            type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        />
                        <label for="is_active" class="text-sm font-medium text-gray-700">Aktif</label>
                        <InputError :message="form.errors.is_active" />
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4">
                        <SecondaryButton type="button" @click="closeModal">
                            Batal
                        </SecondaryButton>
                        <PrimaryButton type="submit" :disabled="form.processing">
                            {{ isEditing ? 'Simpan Perubahan' : 'Simpan' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation -->
        <ConfirmModal
            :show="showConfirmModal"
            :loading="deleting"
            title="Hapus Program?"
            :message="`Apakah Anda yakin ingin menghapus program ${itemToDelete?.title || ''}?`"
            @confirm="deleteProgram"
            @close="showConfirmModal = false"
        />

        <!-- Toast Notification -->
        <div class="fixed bottom-4 right-4 z-50">
            <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
        </div>
    </AdminLayout>
</template>
