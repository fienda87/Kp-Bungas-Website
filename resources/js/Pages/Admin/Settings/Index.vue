<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';
import AdminSectionCard from '@/Components/Admin/AdminSectionCard.vue';

const props = defineProps({
    settings: Array,
});

const page = usePage();
const toastMessage = ref('');
const toastType = ref('success');

// Initialize form dynamically based on settings from database
const initialData = {};
props.settings.forEach(setting => {
    initialData[setting.key] = setting.value || '';
});

const form = useForm({
    settings: initialData
});

const submitForm = () => {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            showToast('Pengaturan berhasil disimpan');
        },
        onError: () => {
            showToast('Terjadi kesalahan saat menyimpan pengaturan', 'error');
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

watch(() => page.props.flash?.success, (message) => {
    if (message) showToast(message);
}, { immediate: true });

watch(() => page.props.flash?.error, (message) => {
    if (message) showToast(message, 'error');
}, { immediate: true });
</script>

<template>
    <Head title="Kelola Informasi Website" />

    <AdminLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">Kelola Informasi Website</h2>
                    <p class="mt-1 text-sm text-gray-500">Kelola kontak, profil, dan seluruh link media sosial website.</p>
                </div>
            </div>
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                
                <AdminSectionCard>
                    <form @submit.prevent="submitForm" class="space-y-6">
                        
                        <div v-for="setting in settings" :key="setting.id" class="border-b border-gray-100 pb-5 last:border-0 last:pb-0">
                            <InputLabel :for="setting.key" :value="setting.description || setting.key" />
                            
                            <template v-if="setting.type === 'textarea'">
                                <textarea
                                    :id="setting.key"
                                    v-model="form.settings[setting.key]"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    rows="4"
                                ></textarea>
                            </template>
                            
                            <template v-else>
                                <TextInput
                                    :id="setting.key"
                                    v-model="form.settings[setting.key]"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                            </template>
                            
                            <InputError class="mt-2" :message="form.errors[`settings.${setting.key}`]" />
                        </div>
                        
                        <div class="flex justify-end pt-4">
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                {{ form.processing ? 'Menyimpan...' : 'Simpan Pengaturan' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </AdminSectionCard>
                
            </div>
        </div>

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>