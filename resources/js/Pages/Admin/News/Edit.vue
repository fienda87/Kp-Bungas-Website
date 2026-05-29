<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import FileUpload from '@/Components/Admin/FileUpload.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';
import { computed, ref } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    news: Object,
    categories: Array
});

const toastMessage = ref('');
const toastType = ref('success');
const uploadErrors = ref({
    thumbnail: '',
    pdf_file: '',
});

const formatForInput = (dateString) => {
    if (!dateString) return null;
    const date = new Date(dateString);
    return new Date(date.getTime() - (date.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
};

const form = useForm({
    _method: 'PUT',
    title: props.news.data.title,
    content: props.news.data.raw_content || props.news.data.content,
    category: props.news.data.category,
    status: props.news.data.status,
    thumbnail: null,
    pdf_file: null,
    published_at: formatForInput(props.news.data.published_at),
});

const thumbnailError = computed(() => uploadErrors.value.thumbnail || form.errors.thumbnail);
const pdfError = computed(() => uploadErrors.value.pdf_file || form.errors.pdf_file);

const setUploadError = (field, message) => {
    uploadErrors.value[field] = message;
};

const submit = (status = null) => {
    if (uploadErrors.value.thumbnail || uploadErrors.value.pdf_file) {
        showToast('Periksa kembali file yang diunggah.', 'error');
        return;
    }

    if (status) {
        form.status = status;
    }

    // We use POST with _method PUT because of file upload limitation in Laravel with PUT
    form.post(route('admin.news.update', props.news.data.slug), {
        forceFormData: true,
        onSuccess: () => {
            showToast(form.status === 'published' ? 'Berita berhasil dipublikasikan' : 'Draft berhasil disimpan');
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
                <form @submit.prevent="submit()" class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                                <p>Dibuat pada: {{ new Date(news.data.created_at).toLocaleString('id-ID') }}</p>
                                <p v-if="news.data.published_at">Diterbitkan pada: {{ new Date(news.data.published_at).toLocaleString('id-ID') }}</p>
                            </div>

                            <div class="mt-6 grid gap-3 border-t pt-4">
                                <button
                                    type="button"
                                    class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                                    :disabled="form.processing"
                                    @click="submit('draft')"
                                >
                                    <span v-if="form.processing && form.status === 'draft'">Menyimpan...</span>
                                    <span v-else>Simpan Draft</span>
                                </button>
                                <PrimaryButton
                                    type="button"
                                    class="w-full justify-center"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                    @click="submit('published')"
                                >
                                    <span v-if="form.processing && form.status === 'published'">Memperbarui...</span>
                                    <span v-else>Publish Berita</span>
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Thumbnail -->
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-sm font-medium text-gray-700 mb-4 border-b pb-2">Thumbnail</h3>
                            
                            <div v-if="news.data.thumbnail" class="mb-4">
                                <p class="text-xs text-gray-500 mb-2">Thumbnail saat ini:</p>
                                <img :src="news.data.thumbnail" class="w-full h-32 object-cover rounded-md border" alt="Thumbnail">
                            </div>

                            <FileUpload
                                v-model="form.thumbnail"
                                accept="image/*"
                                label="Ubah thumbnail"
                                :max-size-mb="5"
                                helper-text="JPG, PNG, GIF, WEBP hingga 5MB"
                                :preview-url="news.data.thumbnail"
                                preview
                                :error="thumbnailError"
                                @validation-error="setUploadError('thumbnail', $event)"
                            />
                        </div>

                        <!-- PDF Attachment -->
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h3 class="text-sm font-medium text-gray-700 mb-4 border-b pb-2">Lampiran PDF</h3>
                            
                            <div v-if="news.data.pdf_file" class="mb-4 p-2 bg-gray-50 rounded border flex items-center">
                                <span class="text-xs text-gray-600 truncate flex-1">{{ news.data.pdf_file.split('/').pop() }}</span>
                                <a :href="news.data.pdf_file" target="_blank" class="text-primary hover:underline text-xs ml-2" rel="noreferrer">Lihat</a>
                            </div>

                            <FileUpload
                                v-model="form.pdf_file"
                                accept=".pdf"
                                label="Ubah file PDF"
                                :max-size-mb="10"
                                helper-text="PDF hingga 10MB"
                                :preview-url="news.data.pdf_file"
                                :error="pdfError"
                                @validation-error="setUploadError('pdf_file', $event)"
                            />
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>
