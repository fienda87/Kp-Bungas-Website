<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import ConfirmModal from '@/Components/Admin/ConfirmModal.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';
import AdminEmptyState from '@/Components/Admin/AdminEmptyState.vue';
import AdminStatusBadge from '@/Components/Admin/AdminStatusBadge.vue';
import AdminSectionCard from '@/Components/Admin/AdminSectionCard.vue';

const props = defineProps({
    users: Array,
    roles: Array,
});

const page = usePage();
const showModal = ref(false);
const showConfirmModal = ref(false);
const isEditing = ref(false);
const selectedUser = ref(null);
const toastMessage = ref('');
const toastType = ref('success');
const isDeleting = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_id: '',
});

const openCreateModal = () => {
    isEditing.value = false;
    selectedUser.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const openEditModal = (user) => {
    isEditing.value = true;
    selectedUser.value = user;
    form.name = user.name;
    form.email = user.email;
    form.password = '';
    form.password_confirmation = '';
    form.role_id = user.roles?.[0]?.id || '';
    form.clearErrors();
    showModal.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route('admin.users.update', selectedUser.value.id), {
            onSuccess: () => {
                showModal.value = false;
                showToast('Pengguna berhasil diperbarui');
            },
            onError: () => showToast('Terjadi kesalahan data', 'error'),
        });
    } else {
        form.post(route('admin.users.store'), {
            onSuccess: () => {
                showModal.value = false;
                showToast('Pengguna berhasil ditambahkan');
            },
            onError: () => showToast('Terjadi kesalahan data', 'error'),
        });
    }
};

const confirmDelete = (user) => {
    selectedUser.value = user;
    showConfirmModal.value = true;
};

const deleteUser = () => {
    isDeleting.value = true;
    router.delete(route('admin.users.destroy', selectedUser.value.id), {
        onSuccess: () => {
            showConfirmModal.value = false;
            showToast('Pengguna berhasil dihapus');
        },
        onError: () => {
            showConfirmModal.value = false;
            showToast(page.props.flash?.error || 'Terjadi kesalahan saat menghapus pengguna', 'error');
        },
        onFinish: () => {
            isDeleting.value = false;
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

watch(() => page.props.flash?.success, (message) => {
    if (message) showToast(message);
}, { immediate: true });

watch(() => page.props.flash?.error, (message) => {
    if (message) showToast(message, 'error');
}, { immediate: true });
</script>

<template>
    <Head title="Manajemen Pengguna" />

    <AdminLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">Manajemen Pengguna</h2>
                    <p class="mt-1 text-sm text-gray-500">Kelola akun dan akses role admin atau editor.</p>
                </div>
                <PrimaryButton @click="openCreateModal">Tambah Pengguna</PrimaryButton>
            </div>
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                
                <AdminSectionCard>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="user in users" :key="user.id">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ user.name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ user.email }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <AdminStatusBadge :status="user.roles?.[0]?.name === 'admin' ? 'published' : (user.roles?.[0]?.name === 'editor' ? 'active' : 'draft')" :label="user.roles?.[0]?.name || '-'" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <button type="button" class="text-emerald-600 hover:text-emerald-900" @click="openEditModal(user)">Edit</button>
                                        <button 
                                            v-if="user.id !== $page.props.auth.user.id" 
                                            type="button" 
                                            class="ml-3 text-red-600 hover:text-red-900" 
                                            @click="confirmDelete(user)"
                                        >
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </AdminSectionCard>
            </div>
        </div>

        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ isEditing ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
                </h2>

                <form @submit.prevent="submitForm" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="name" value="Nama Lengkap" />
                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Alamat Email" />
                        <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="password" :value="isEditing ? 'Password Baru (Opsional)' : 'Password'" />
                        <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" />
                        <p v-if="isEditing" class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div>
                        <InputLabel for="password_confirmation" :value="isEditing ? 'Konfirmasi Password Baru (Opsional)' : 'Konfirmasi Password'" />
                        <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" />
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <SecondaryButton type="button" @click="showModal = false">Batal</SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <ConfirmModal
            :show="showConfirmModal"
            :loading="isDeleting"
            title="Hapus Pengguna?"
            :message="`Apakah Anda yakin ingin menghapus pengguna ${selectedUser?.name || ''}? Tindakan ini tidak dapat dibatalkan.`"
            @confirm="deleteUser"
            @close="showConfirmModal = false"
        />

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>