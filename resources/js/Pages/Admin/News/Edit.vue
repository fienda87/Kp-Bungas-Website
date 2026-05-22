<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import FileUpload from '@/Components/Admin/FileUpload.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';
import { ref } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    news: Object,
    categories: Array
});

const toastMessage = ref('');
const toastType = ref('success');

const formatForInput = (dateString) => {
    if (!dateString) return null;
    const date = new Date(dateString);
    return new Date(date.getTime() - (date.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
};

const form = useForm({
    _method: 'PUT',
    title: props.news.title,
    content: props.news.content,
    category: props.news.category,
    status: props.news.status,
    thumbnail: null,
    pdf_file: null,
    published_at: formatForInput(props.news.published_at),
});

const submit = () => {
    // We use POST with _method PUT because of file upload limitation in Laravel with PUT
    form.post(route('admin.news.update', props.news.slug), {
        onSuccess: () => {
            showToast('Berita berhasil diperbarui');
        },
        onError: () => {
            showToast('Terjadi kesalahan saat memperbarui berita', 'error');
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
    <Head title="Edit Berita" />

    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Berita</h2>
                <Link :href="route('admin.news.index')" class="text-sm text-gray-600 hover:text-gray-900">
                    &larr; Kembali ke Daftar
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="md:col-span-2 space-y-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Judul Berita</label>
                                <input
                                    type="text"
                                    id="title"
                                    v-model="form.title"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-lg font-semibold"
                                    placeholder="Masukkan judul berita..."
                                    required
                                >
                                <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
                            </div>

                            <div class="mt-6">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                                <div class="bg-white">
                                    <QuillEditor
                                        v-model:content="form.content"
                                        content-type="html"
                                        theme="snow"
                                        toolbar="full"
                                        placeholder="Tulis berita Anda di sini..."
                                        class="min-h-[400px]"
                                    />
                                </div>
                                <div v-if="form.errors.content" class="text-red-500 text-xs mt-1">{{ form.errors.content }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Publish Options -->
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-sm font-medium text-gray-700 mb-4 border-b pb-2">Pengaturan Publikasi</h3>
                            
                            <div>
                                <label for="category" class="block text-xs text-gray-500 uppercase tracking-wider mb-1">Kategori</label>
                                <input
                                    list="category-list"
                                    id="category"
                                    v-model="form.category"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"
                                    placeholder="Pilih atau ketik kategori..."
                                    required
                                >
                                <datalist id="category-list">
                                    <option v-for="cat in categories" :key="cat" :value="cat" />
                                </datalist>
                                <div v-if="form.errors.category" class="text-red-500 text-xs mt-1">{{ form.errors.category }}</div>
                            </div>

                            <div class="mt-4">
                                <label for="status" class="block text-xs text-gray-500 uppercase tracking-wider mb-1">Status</label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"
                                >
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <label for="published_at" class="block text-xs text-gray-500 uppercase tracking-wider mb-1">Tanggal Publikasi</label>
                                <input
                                    type="datetime-local"
                                    id="published_at"
                                    v-model="form.published_at"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"
                                >
                                <div v-if="form.errors.published_at" class="text-red-500 text-xs mt-1">{{ form.errors.published_at }}</div>
                            </div>

                            <div class="mt-6 pt-4 border-t text-xs text-gray-500">
                                <p>Dibuat pada: {{ new Date(news.created_at).toLocaleString() }}</p>
                                <p v-if="news.published_at">Diterbitkan pada: {{ new Date(news.published_at).toLocaleString() }}</p>
                            </div>

                            <div class="mt-6 pt-4 border-t">
                                <PrimaryButton
                                    class="w-full justify-center"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Perbarui Berita
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Thumbnail -->
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-sm font-medium text-gray-700 mb-4 border-b pb-2">Thumbnail</h3>
                            
                            <div v-if="news.thumbnail" class="mb-4">
                                <p class="text-xs text-gray-500 mb-2">Thumbnail saat ini:</p>
                                <img :src="'/storage/' + news.thumbnail" class="w-full h-32 object-cover rounded-md border" alt="Thumbnail">
                            </div>

                            <FileUpload
                                v-model="form.thumbnail"
                                accept="image/*"
                                label="Ubah thumbnail"
                                preview
                            />
                            <div v-if="form.errors.thumbnail" class="text-red-500 text-xs mt-1">{{ form.errors.thumbnail }}</div>
                        </div>

                        <!-- PDF Attachment -->
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-sm font-medium text-gray-700 mb-4 border-b pb-2">Lampiran PDF</h3>
                            
                            <div v-if="news.pdf_file" class="mb-4 p-2 bg-gray-50 rounded border flex items-center">
                                <span class="text-xs text-gray-600 truncate flex-1">{{ news.pdf_file.split('/').pop() }}</span>
                                <a :href="'/storage/' + news.pdf_file" target="_blank" class="text-primary hover:underline text-xs ml-2">Lihat</a>
                            </div>

                            <FileUpload
                                v-model="form.pdf_file"
                                accept=".pdf"
                                label="Ubah file PDF"
                            />
                            <div v-if="form.errors.pdf_file" class="text-red-500 text-xs mt-1">{{ form.errors.pdf_file }}</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>
