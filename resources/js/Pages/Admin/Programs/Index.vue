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

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden border border-gray-100 shadow-sm rounded-2xl p-6 transition-all duration-300">
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/70">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Program</th>
                                    <th class="hidden sm:table-cell px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Icon</th>
                                    <th class="hidden sm:table-cell px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Gambar</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Urutan</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr v-for="(program, index) in programs" :key="program.id" class="hover:bg-slate-50/50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-400">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900 leading-snug">{{ program.title }}</div>
                                        <div class="text-xs text-gray-400 mt-1 line-clamp-1 max-w-sm">{{ program.description || 'Tidak ada deskripsi.' }}</div>
                                    </td>
                                    <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-gray-500">
                                        <span v-if="program.icon" class="text-lg bg-gray-50 p-2 rounded-xl border border-gray-100 shadow-xs inline-block">{{ program.icon }}</span>
                                        <span v-else class="text-gray-300">-</span>
                                    </td>
                                    <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap">
                                        <img
                                            v-if="program.image_url"
                                            :src="program.image_url"
                                            :alt="program.title"
                                            class="h-10 w-16 object-cover rounded-lg shadow-xs border border-gray-100"
                                        />
                                        <span v-else class="text-gray-300 text-sm font-medium">-</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[
                                                'px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full',
                                                program.is_active
                                                    ? 'bg-green-50 text-green-700 border border-green-100'
                                                    : 'bg-gray-50 text-gray-500 border border-gray-100'
                                            ]"
                                        >
                                            {{ program.is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-semibold">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-5 text-center font-bold text-gray-800">{{ program.order }}</span>
                                            <button
                                                @click="moveUp(index)"
                                                :class="[
                                                    'p-1.5 rounded-xl hover:bg-gray-100 focus:outline-none transition-all duration-150 active:scale-90',
                                                    index === 0 || reorderLoading ? 'text-gray-200 cursor-not-allowed' : 'text-gray-600 hover:text-primary cursor-pointer'
                                                ]"
                                                :disabled="index === 0 || reorderLoading"
                                                title="Naik"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                                                </svg>
                                            </button>
                                            <button
                                                @click="moveDown(index)"
                                                :class="[
                                                    'p-1.5 rounded-xl hover:bg-gray-100 focus:outline-none transition-all duration-150 active:scale-90',
                                                    index === programs.length - 1 || reorderLoading ? 'text-gray-200 cursor-not-allowed' : 'text-gray-600 hover:text-primary cursor-pointer'
                                                ]"
                                                :disabled="index === programs.length - 1 || reorderLoading"
                                                title="Turun"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button
                                                @click="openEditModal(program)"
                                                class="px-2.5 py-1.5 rounded-xl text-xs border border-emerald-100 bg-emerald-50/50 text-emerald-700 hover:bg-emerald-100 transition-colors cursor-pointer"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="confirmDelete(program)"
                                                class="px-2.5 py-1.5 rounded-xl text-xs border border-red-100 bg-red-50/50 text-red-700 hover:bg-red-100 transition-colors cursor-pointer"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="programs.length === 0">
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-400 font-medium">
                                        Belum ada program desa yang terdaftar.
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
            <div class="p-6 font-sans">
                <h3 class="text-lg font-bold text-gray-900 tracking-tight mb-6">
                    {{ isEditing ? 'Edit Program' : 'Tambah Program Baru' }}
                </h3>

                <form @submit.prevent="submitForm" class="space-y-5">
                    <!-- Title -->
                    <div>
                        <InputLabel for="title" value="Judul Program" />
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
                        <InputLabel for="description" value="Deskripsi Singkat" />
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm px-4 py-2.5 bg-gray-50/50 transition-all duration-300"
                            placeholder="Jelaskan mengenai maksud dan tujuan program desa ini..."
                        ></textarea>
                        <InputError :message="form.errors.description" />
                    </div>

                    <!-- Icon -->
                    <div>
                        <InputLabel for="icon" value="Karakter Icon (Emoji)" />
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
                        <InputLabel for="image" value="Gambar Ilustrasi" />
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
                        <InputLabel for="order" value="No. Urutan Tampilan" />
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
                    <div class="flex items-center gap-2.5 pt-2">
                        <input
                            id="is_active"
                            v-model="form.is_active"
                            type="checkbox"
                            class="rounded-md border-gray-300 text-primary shadow-sm focus:ring-primary/20 h-4.5 w-4.5"
                        />
                        <label for="is_active" class="text-sm font-semibold text-gray-700">Tampilkan Program Secara Aktif</label>
                        <InputError :message="form.errors.is_active" />
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-50">
                        <SecondaryButton type="button" class="rounded-xl" @click="closeModal">
                            Batal
                        </SecondaryButton>
                        <PrimaryButton type="submit" class="rounded-xl font-bold" :disabled="form.processing">
                            {{ isEditing ? 'Simpan Perubahan' : 'Tambah Program' }}
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
