<script setup>
import { ref, watch, computed } from 'vue';
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

const isRoleDropdownOpen = ref(false);

const selectRole = (roleId) => {
    form.role_id = roleId;
    isRoleDropdownOpen.value = false;
};

const selectedRoleName = computed(() => {
    const role = props.roles.find(r => r.id == form.role_id);
    if (!role) return '';
    return role.name === 'admin' ? 'Administrator' : (role.name === 'editor' ? 'Editor' : role.name);
});

const openCreateModal = () => {
    isEditing.value = false;
    selectedUser.value = null;
    form.reset();
    form.clearErrors();
    isRoleDropdownOpen.value = false;
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
    isRoleDropdownOpen.value = false;
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
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/70">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Nama</th>
                                    <th scope="col" class="hidden sm:table-cell px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Email</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Role</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <tr 
                                    v-for="user in users" 
                                    :key="user.id"
                                    class="transition-all duration-200 hover:bg-slate-50/80"
                                >
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-900">{{ user.name }}</td>
                                    <td class="hidden sm:table-cell whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ user.email }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                        <AdminStatusBadge :status="user.roles?.[0]?.name === 'admin' ? 'published' : (user.roles?.[0]?.name === 'editor' ? 'active' : 'draft')" :label="user.roles?.[0]?.name === 'admin' ? 'Administrator' : (user.roles?.[0]?.name === 'editor' ? 'Editor' : '-')" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <button 
                                            type="button" 
                                            class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold border border-emerald-100 bg-emerald-50/50 text-emerald-700 hover:bg-emerald-100 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 cursor-pointer" 
                                            @click="openEditModal(user)"
                                        >
                                            Edit
                                        </button>
                                        <button 
                                            v-if="user.id !== $page.props.auth.user.id" 
                                            type="button" 
                                            class="ml-2 inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold border border-red-100 bg-red-50/50 text-red-700 hover:bg-red-100 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 cursor-pointer" 
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
                <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                    {{ isEditing ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
                </h2>

                <form @submit.prevent="submitForm" class="mt-6 space-y-5">
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

                    <div class="relative" @mouseenter="isRoleDropdownOpen = true" @mouseleave="isRoleDropdownOpen = false">
                        <InputLabel for="role_id" value="Hak Akses / Role" />
                        
                        <!-- Backdrop to close dropdown on click outside -->
                        <div v-if="isRoleDropdownOpen" class="fixed inset-0 z-40" @click="isRoleDropdownOpen = false" />
                        
                        <div class="relative mt-1 z-50">
                            <button
                                type="button"
                                id="role_id"
                                @click="isRoleDropdownOpen = !isRoleDropdownOpen"
                                class="w-full text-left pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 text-sm font-semibold text-gray-800 bg-gray-50/50 cursor-pointer flex items-center justify-between"
                            >
                                <span v-if="form.role_id" class="block truncate">{{ selectedRoleName }}</span>
                                <span v-else class="block truncate text-gray-400 font-medium">Pilih Role...</span>
                                
                                <!-- Arrow Icon -->
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg 
                                        class="h-4 w-4 text-gray-400 transition-transform duration-300"
                                        :class="isRoleDropdownOpen ? 'rotate-180 text-primary' : ''"
                                        fill="none" 
                                        viewBox="0 0 24 24" 
                                        stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>
                        </div>
                        
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
                                v-if="isRoleDropdownOpen && roles.length" 
                                class="absolute z-50 mt-1.5 w-full bg-white rounded-xl border border-gray-100 shadow-xl py-1.5 backdrop-blur-md bg-white/95 text-left"
                            >
                                <button
                                    v-for="role in roles"
                                    :key="role.id"
                                    type="button"
                                    @click="selectRole(role.id)"
                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-600 hover:text-white font-semibold transition-colors duration-150 flex items-center justify-between first:rounded-t-xl last:rounded-b-xl cursor-pointer group"
                                >
                                    <span>{{ role.name === 'admin' ? 'Administrator' : (role.name === 'editor' ? 'Editor' : role.name) }}</span>
                                    <svg v-if="form.role_id == role.id" class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </div>
                        </transition>
                        <InputError class="mt-2" :message="form.errors.role_id" />
                    </div>

                    <div>
                        <InputLabel for="password" :value="isEditing ? 'Password Baru (Opsional)' : 'Password'" />
                        <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" />
                        <p v-if="isEditing" class="mt-1 text-[11px] font-medium text-gray-400">Kosongkan jika tidak ingin mengubah password.</p>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div>
                        <InputLabel for="password_confirmation" :value="isEditing ? 'Konfirmasi Password Baru (Opsional)' : 'Konfirmasi Password'" />
                        <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" />
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
            title="Hapus Pengguna?"
            :message="`Apakah Anda yakin ingin menghapus pengguna ${selectedUser?.name || ''}? Tindakan ini tidak dapat dibatalkan.`"
            @confirm="deleteUser"
            @close="showConfirmModal = false"
        />

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>