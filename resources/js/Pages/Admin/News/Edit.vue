<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import FileUpload from '@/Components/Admin/FileUpload.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';
import { computed, ref, onMounted, watch } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

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

const isCategoryDropdownOpen = ref(false);
const isStatusDropdownOpen = ref(false);

const selectCategory = (cat) => {
    form.category = cat;
    isCategoryDropdownOpen.value = false;
};

const selectStatus = (status) => {
    form.status = status;
    isStatusDropdownOpen.value = false;
};

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

const publishedAtInput = ref(null);
let fpInstance = null;

const Indonesian = {
    weekdays: {
        shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
        longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"]
    },
    months: {
        shorthand: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des"],
        longhand: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
    },
    firstDayOfWeek: 1,
    ordinal: () => "",
    time_24hr: true
};

onMounted(() => {
    fpInstance = flatpickr(publishedAtInput.value, {
        enableTime: true,
        dateFormat: "Y-m-d H:i:S",
        altInput: true,
        altFormat: "d F Y, H:i",
        altInputClass: "w-full pl-3 pr-10 py-2.5 border border-gray-200 rounded-xl shadow-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 sm:text-sm font-medium text-gray-700 bg-white cursor-pointer",
        time_24hr: true,
        locale: Indonesian,
        defaultDate: form.published_at || new Date(),
        monthSelectorType: "static",
        onChange: (selectedDates, dateStr) => {
            form.published_at = dateStr;
        }
    });
});

watch(() => form.published_at, (newVal) => {
    if (fpInstance && newVal !== fpInstance.input.value) {
        fpInstance.setDate(newVal);
    }
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
            <div class="flex justify-between items-center bg-white/40 backdrop-blur-xs py-4 px-6 rounded-2xl border border-gray-100/50 shadow-xs -mx-4">
                <div class="flex flex-col gap-1">
                    <h2 class="font-extrabold text-xl text-gray-900 tracking-tight leading-tight">Edit Berita</h2>
                    <p class="text-xs text-gray-400">Sunting dan perbarui artikel berita desa.</p>
                </div>
                <Link :href="route('admin.news.index')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 bg-white text-xs font-semibold text-gray-600 hover:text-primary hover:border-primary-light hover:shadow-xs transition-all duration-300">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto">
                <form @submit.prevent="submit()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Berita</label>
                                <input
                                    type="text"
                                    id="title"
                                    v-model="form.title"
                                    class="block w-full border-gray-200 rounded-xl p-3.5 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 sm:text-lg font-bold text-gray-800 placeholder-gray-400"
                                    placeholder="Masukkan judul berita..."
                                    required
                                >
                                <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
                            </div>

                            <div>
                                <label for="content" class="block text-sm font-semibold text-gray-700 mb-1.5">Konten</label>
                                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs focus-within:ring-2 focus-within:ring-primary/20 focus-within:border-primary transition-all duration-300">
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
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 class="text-sm font-bold text-gray-900 mb-4 border-b border-gray-50 pb-2">Pengaturan Publikasi</h3>
                            
                            <div class="space-y-4">
                                <!-- Kategori dengan Custom Select (Tidak Bisa Diketik) -->
                                <div class="relative" @mouseenter="isCategoryDropdownOpen = true" @mouseleave="isCategoryDropdownOpen = false">
                                    <label for="category" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kategori</label>
                                    <div class="relative">
                                        <button
                                            type="button"
                                            id="category"
                                            @click="isCategoryDropdownOpen = !isCategoryDropdownOpen"
                                            class="w-full text-left pl-3 pr-10 py-2.5 border border-gray-200 rounded-xl shadow-xs focus:outline-hidden focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 sm:text-sm font-semibold text-gray-800 bg-white cursor-pointer flex items-center justify-between"
                                        >
                                            <span v-if="form.category" class="block truncate">{{ form.category }}</span>
                                            <span v-else class="block truncate text-gray-400 font-medium">Pilih kategori...</span>
                                            
                                            <!-- Arrow Icon -->
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg 
                                                    class="h-4 w-4 text-gray-400 transition-transform duration-300"
                                                    :class="isCategoryDropdownOpen ? 'rotate-180 text-primary' : ''"
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
                                            v-if="isCategoryDropdownOpen && categories.length" 
                                            class="absolute z-50 mt-1.5 w-full bg-white rounded-xl border border-gray-100 shadow-xl max-h-60 overflow-y-auto py-1.5 backdrop-blur-md bg-white/95"
                                        >
                                            <button
                                                v-for="cat in categories"
                                                :key="cat"
                                                type="button"
                                                @mousedown="selectCategory(cat)"
                                                class="w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-600 hover:text-white font-semibold transition-colors duration-150 flex items-center justify-between first:rounded-t-xl last:rounded-b-xl cursor-pointer group"
                                            >
                                                <span>{{ cat }}</span>
                                                <svg v-if="form.category === cat" class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </transition>
                                    <div v-if="form.errors.category" class="text-red-500 text-xs mt-1">{{ form.errors.category }}</div>
                                </div>

                                <!-- Status dengan Custom Design (Konsisten dengan Kategori) -->
                                <div class="relative" @mouseenter="isStatusDropdownOpen = true" @mouseleave="isStatusDropdownOpen = false">
                                    <label for="status" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status</label>
                                    <div class="relative">
                                        <button
                                            type="button"
                                            id="status"
                                            @click="isStatusDropdownOpen = !isStatusDropdownOpen"
                                            class="w-full text-left pl-3 pr-10 py-2.5 border border-gray-200 rounded-xl shadow-xs focus:outline-hidden focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 sm:text-sm font-semibold text-gray-800 bg-white cursor-pointer flex items-center justify-between"
                                        >
                                            <span class="block truncate capitalize font-semibold text-gray-800">{{ form.status }}</span>
                                            
                                            <!-- Arrow Icon -->
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg 
                                                    class="h-4 w-4 text-gray-400 transition-transform duration-300"
                                                    :class="isStatusDropdownOpen ? 'rotate-180 text-primary' : ''"
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
                                            v-if="isStatusDropdownOpen" 
                                            class="absolute z-50 mt-1.5 w-full bg-white rounded-xl border border-gray-100 shadow-xl py-1.5 backdrop-blur-md bg-white/95"
                                        >
                                            <button
                                                type="button"
                                                @mousedown="selectStatus('draft')"
                                                class="w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-600 hover:text-white font-semibold transition-colors duration-150 flex items-center justify-between first:rounded-t-xl cursor-pointer group"
                                            >
                                                <span>Draft</span>
                                                <svg v-if="form.status === 'draft'" class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                            <button
                                                type="button"
                                                @mousedown="selectStatus('published')"
                                                class="w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-600 hover:text-white font-semibold transition-colors duration-150 flex items-center justify-between last:rounded-b-xl cursor-pointer group"
                                            >
                                                <span>Published</span>
                                                <svg v-if="form.status === 'published'" class="w-4 h-4 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </transition>
                                </div>

                                <!-- Tanggal Publikasi -->
                                <div>
                                    <label for="published_at" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal Publikasi</label>
                                    <div class="relative">
                                        <input
                                            ref="publishedAtInput"
                                            type="text"
                                            id="published_at"
                                            placeholder="Pilih tanggal & waktu..."
                                            class="w-full pl-3 pr-10 py-2.5 border border-gray-200 rounded-xl shadow-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-300 sm:text-sm font-medium text-gray-700 bg-white cursor-pointer"
                                        >
                                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div v-if="form.errors.published_at" class="text-red-500 text-xs mt-1">{{ form.errors.published_at }}</div>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-100/80 text-[10px] font-semibold text-gray-400 space-y-1.5 bg-gray-50/50 p-3 rounded-xl">
                                <div class="flex justify-between items-center">
                                    <span>Dibuat pada:</span>
                                    <span class="text-gray-600">{{ new Date(news.data.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}</span>
                                </div>
                                <div v-if="news.data.published_at" class="flex justify-between items-center">
                                    <span>Diterbitkan:</span>
                                    <span class="text-emerald-700">{{ new Date(news.data.published_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}</span>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="mt-6 grid gap-3 border-t border-gray-50 pt-4">
                                <button
                                    type="button"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 focus:outline-hidden transition-all duration-200 disabled:opacity-50"
                                    :disabled="form.processing"
                                    @click="submit('draft')"
                                >
                                    <span v-if="form.processing && form.status === 'draft'">Menyimpan...</span>
                                    <span v-else>Simpan Draft</span>
                                </button>
                                <PrimaryButton
                                    type="button"
                                    class="w-full justify-center py-2.5 rounded-xl text-xs font-bold shadow-xs hover:shadow-md transition-all duration-300"
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
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 class="text-sm font-bold text-gray-900 mb-4 border-b border-gray-50 pb-2">Thumbnail</h3>
                            
                            <div v-if="news.data.thumbnail" class="mb-4">
                                <p class="text-xs font-semibold text-gray-400 mb-2 uppercase tracking-wider">Thumbnail Saat Ini</p>
                                <div class="relative group rounded-xl overflow-hidden border border-gray-100 shadow-inner">
                                    <img :src="news.data.thumbnail" class="w-full h-32 object-cover transition-transform duration-500 group-hover:scale-105" alt="Thumbnail">
                                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                </div>
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
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 class="text-sm font-bold text-gray-900 mb-4 border-b border-gray-50 pb-2">Lampiran PDF</h3>
                            
                            <div v-if="news.data.pdf_file" class="mb-4 p-3 bg-emerald-50/20 border border-emerald-100/60 rounded-xl flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2 min-w-0">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-xs font-semibold text-emerald-800 truncate">{{ news.data.pdf_file.split('/').pop() }}</span>
                                </div>
                                <a :href="news.data.pdf_file" target="_blank" class="shrink-0 text-emerald-600 hover:text-emerald-800 font-bold text-xs hover:underline" rel="noreferrer">Lihat</a>
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
