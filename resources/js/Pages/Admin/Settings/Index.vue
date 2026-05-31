<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import FileUpload from '@/Components/Admin/FileUpload.vue';
import ToastNotification from '@/Components/Admin/ToastNotification.vue';
import AdminSectionCard from '@/Components/Admin/AdminSectionCard.vue';

const props = defineProps({
    settings: Array,
});

const page = usePage();
const toastMessage = ref('');
const toastType = ref('success');
const isEditing = ref(false);
const heroSlides = ref([]);

// Initialize form dynamically based on settings from database
const initialData = {};
props.settings.forEach(setting => {
    initialData[setting.key] = setting.value || '';
});

const form = useForm({
    settings: initialData,
    slide_image_0: null,
    slide_image_1: null,
    slide_image_2: null,
    slide_image_3: null,
    slide_image_4: null,
});

const loadSlides = () => {
    try {
        const slidesSetting = props.settings.find(s => s.key === 'hero_slides');
        if (slidesSetting && slidesSetting.value) {
            heroSlides.value = JSON.parse(slidesSetting.value);
        }
    } catch (e) {
        console.error("Error parsing hero slides JSON", e);
    }
};
loadSlides();

const submitForm = () => {
    // Serialize slides back into JSON string setting
    form.settings.hero_slides = JSON.stringify(heroSlides.value);

    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            showToast('Pengaturan berhasil disimpan');
            isEditing.value = false;
        },
        onError: () => {
            showToast('Terjadi kesalahan saat menyimpan pengaturan', 'error');
        }
    });
};

const cancelEdit = () => {
    // Reset all form settings fields to their original prop values
    props.settings.forEach(setting => {
        form.settings[setting.key] = setting.value || '';
    });
    // Reset slide image file selections
    form.slide_image_0 = null;
    form.slide_image_1 = null;
    form.slide_image_2 = null;
    form.slide_image_3 = null;
    form.slide_image_4 = null;
    loadSlides();
    form.clearErrors();
    isEditing.value = false;
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
    <Head :title="isEditing ? 'Edit Informasi Website' : 'Kelola Informasi Website'" />

    <AdminLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        {{ isEditing ? 'Edit Informasi Website' : 'Kelola Informasi Website' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ isEditing ? 'Ubah seluruh konfigurasi data kontak, profil, dan link media sosial sekaligus.' : 'Kelola kontak, profil, dan seluruh link media sosial website.' }}
                    </p>
                </div>
                <PrimaryButton v-if="!isEditing" @click="isEditing = true" class="rounded-xl font-bold px-5 py-2.5">
                    Edit Pengaturan
                </PrimaryButton>
            </div>
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-7xl space-y-8 sm:px-6 lg:px-8">
                
                <AdminSectionCard 
                    :title="isEditing ? 'Form Pengaturan Website' : 'Profil Website'" 
                    :description="isEditing ? 'Sesuaikan nilai parameter di bawah ini lalu klik Simpan Perubahan.' : 'Berikut adalah seluruh parameter konfigurasi website saat ini.'"
                >


                    <!-- Mode Tampilan (View Mode - 2 Columns) -->
                    <div v-if="!isEditing" class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                        <!-- Left Column: Identitas & Profil Website -->
                        <div class="space-y-6">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Identitas & Profil Website</h3>
                            
                            <div class="space-y-5">
                                <div>
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Website</h4>
                                    <div class="mt-2 text-sm font-semibold text-gray-800 bg-gray-50/70 border border-gray-100/80 rounded-xl px-4 py-3">
                                        {{ form.settings['site_name'] || '-' }}
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Teks Singkat Footer (Tentang Kami)</h4>
                                    <div class="mt-2 text-sm font-semibold text-gray-800 bg-gray-50/70 border border-gray-100/80 rounded-xl px-4 py-3 whitespace-pre-wrap leading-relaxed">
                                        {{ form.settings['about_footer'] || '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Kontak & Media Sosial -->
                        <div class="space-y-6">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Kontak & Media Sosial</h3>
                            
                            <div class="space-y-5">
                                <div>
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Alamat Lengkap</h4>
                                    <div class="mt-2 text-sm font-semibold text-gray-800 bg-gray-50/70 border border-gray-100/80 rounded-xl px-4 py-3 whitespace-pre-wrap leading-relaxed">
                                        {{ form.settings['contact_address'] || '-' }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nomor WhatsApp / Telepon</h4>
                                        <div class="mt-2 text-sm font-semibold text-gray-800 bg-gray-50/70 border border-gray-100/80 rounded-xl px-4 py-3 truncate">
                                            {{ form.settings['contact_phone'] || '-' }}
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Email Kontak Utama</h4>
                                        <div class="mt-2 text-sm font-semibold text-gray-800 bg-gray-50/70 border border-gray-100/80 rounded-xl px-4 py-3 truncate">
                                            {{ form.settings['contact_email'] || '-' }}
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Link Instagram</h4>
                                        <div class="mt-2 text-sm font-semibold text-gray-800 bg-gray-50/70 border border-gray-100/80 rounded-xl px-4 py-3 truncate">
                                            {{ form.settings['social_instagram'] || '-' }}
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Link Facebook</h4>
                                        <div class="mt-2 text-sm font-semibold text-gray-800 bg-gray-50/70 border border-gray-100/80 rounded-xl px-4 py-3 truncate">
                                            {{ form.settings['social_facebook'] || '-' }}
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Link TikTok</h4>
                                        <div class="mt-2 text-sm font-semibold text-gray-800 bg-gray-50/70 border border-gray-100/80 rounded-xl px-4 py-3 truncate">
                                            {{ form.settings['social_tiktok'] || '-' }}
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Link YouTube</h4>
                                        <div class="mt-2 text-sm font-semibold text-gray-800 bg-gray-50/70 border border-gray-100/80 rounded-xl px-4 py-3 truncate">
                                            {{ form.settings['social_youtube'] || '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mode Edit (Edit Mode - 2 Columns) -->
                    <form v-else @submit.prevent="submitForm" class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                            <!-- Left Column: Identitas & Profil Website (Edit Mode) -->
                            <div class="space-y-5">
                                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Identitas & Profil Website</h3>
                                
                                <div>
                                    <InputLabel for="site_name" class="text-sm font-bold text-gray-700" value="Nama Website" />
                                    <TextInput
                                        id="site_name"
                                        v-model="form.settings['site_name']"
                                        type="text"
                                        class="mt-2 block w-full px-4 py-2.5 bg-gray-50/50 font-semibold"
                                    />
                                    <InputError class="mt-2" :message="form.errors['settings.site_name']" />
                                </div>
                                
                                <div>
                                    <InputLabel for="about_footer" class="text-sm font-bold text-gray-700" value="Teks Singkat Footer (Tentang Kami)" />
                                    <textarea
                                        id="about_footer"
                                        v-model="form.settings['about_footer']"
                                        class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm px-4 py-3 bg-gray-50/50 font-semibold leading-relaxed transition-all duration-300"
                                        rows="6"
                                        placeholder="Jelaskan secara singkat profil desa atau website..."
                                    ></textarea>
                                    <InputError class="mt-2" :message="form.errors['settings.about_footer']" />
                                </div>
                            </div>

                            <!-- Right Column: Kontak & Media Sosial (Edit Mode) -->
                            <div class="space-y-5">
                                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Kontak & Media Sosial</h3>
                                
                                <div class="space-y-5">
                                    <div>
                                        <InputLabel for="contact_address" class="text-sm font-bold text-gray-700" value="Alamat Lengkap" />
                                        <textarea
                                            id="contact_address"
                                            v-model="form.settings['contact_address']"
                                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm px-4 py-3 bg-gray-50/50 font-semibold leading-relaxed transition-all duration-300"
                                            rows="3"
                                            placeholder="Masukkan alamat lengkap..."
                                        ></textarea>
                                        <InputError class="mt-2" :message="form.errors['settings.contact_address']" />
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <InputLabel for="contact_phone" class="text-sm font-bold text-gray-700" value="Nomor WhatsApp / Telepon" />
                                            <TextInput
                                                id="contact_phone"
                                                v-model="form.settings['contact_phone']"
                                                type="text"
                                                class="mt-2 block w-full px-4 py-2.5 bg-gray-50/50 font-semibold"
                                            />
                                            <InputError class="mt-2" :message="form.errors['settings.contact_phone']" />
                                        </div>
                                        
                                        <div>
                                            <InputLabel for="contact_email" class="text-sm font-bold text-gray-700" value="Email Kontak Utama" />
                                            <TextInput
                                                id="contact_email"
                                                v-model="form.settings['contact_email']"
                                                type="text"
                                                class="mt-2 block w-full px-4 py-2.5 bg-gray-50/50 font-semibold"
                                            />
                                            <InputError class="mt-2" :message="form.errors['settings.contact_email']" />
                                        </div>
                                        
                                        <div>
                                            <InputLabel for="social_instagram" class="text-sm font-bold text-gray-700" value="Link Instagram" />
                                            <TextInput
                                                id="social_instagram"
                                                v-model="form.settings['social_instagram']"
                                                type="text"
                                                class="mt-2 block w-full px-4 py-2.5 bg-gray-50/50 font-semibold"
                                            />
                                            <InputError class="mt-2" :message="form.errors['settings.social_instagram']" />
                                        </div>
                                        
                                        <div>
                                            <InputLabel for="social_facebook" class="text-sm font-bold text-gray-700" value="Link Facebook" />
                                            <TextInput
                                                id="social_facebook"
                                                v-model="form.settings['social_facebook']"
                                                type="text"
                                                class="mt-2 block w-full px-4 py-2.5 bg-gray-50/50 font-semibold"
                                            />
                                            <InputError class="mt-2" :message="form.errors['settings.social_facebook']" />
                                        </div>
                                        
                                        <div>
                                            <InputLabel for="social_tiktok" class="text-sm font-bold text-gray-700" value="Link TikTok" />
                                            <TextInput
                                                id="social_tiktok"
                                                v-model="form.settings['social_tiktok']"
                                                type="text"
                                                class="mt-2 block w-full px-4 py-2.5 bg-gray-50/50 font-semibold"
                                            />
                                            <InputError class="mt-2" :message="form.errors['settings.social_tiktok']" />
                                        </div>
                                        
                                        <div>
                                            <InputLabel for="social_youtube" class="text-sm font-bold text-gray-700" value="Link YouTube" />
                                            <TextInput
                                                id="social_youtube"
                                                v-model="form.settings['social_youtube']"
                                                type="text"
                                                class="mt-2 block w-full px-4 py-2.5 bg-gray-50/50 font-semibold"
                                            />
                                            <InputError class="mt-2" :message="form.errors['settings.social_youtube']" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Hero Slider Section (Edit Mode) -->
                        <div class="border-t border-gray-100 pt-8 mt-8">
                            <h3 class="text-base font-bold text-gray-800 tracking-tight mb-2">Pengaturan Hero Slider (5 Slide)</h3>
                            <p class="text-xs text-gray-400 font-medium mb-6">Sesuaikan konten visual dan teks untuk ke-5 slide karousel di halaman utama website.</p>
                            
                            <div class="space-y-8">
                                <div 
                                    v-for="(slide, index) in heroSlides" 
                                    :key="index"
                                    class="bg-gray-50/50 border border-gray-200/40 rounded-2xl p-6 relative"
                                >
                                    <div class="absolute top-4 right-4 bg-emerald-50 text-emerald-800 text-xs font-bold px-3 py-1.5 rounded-xl border border-emerald-100">
                                        Slide #{{ index + 1 }}
                                    </div>

                                    <h4 class="text-sm font-bold text-slate-800 mb-5 pb-2 border-b border-slate-200/50">Edit Slide {{ index + 1 }}</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <InputLabel :for="'slide_tagline_' + index" class="text-xs font-bold text-slate-500" value="Tagline / Label Kecil (e.g. Aktivitas Warga Kampung)" />
                                            <TextInput
                                                :id="'slide_tagline_' + index"
                                                v-model="slide.tagline"
                                                type="text"
                                                class="mt-2 block w-full px-4 py-2.5 bg-white"
                                            />
                                        </div>
                                        
                                        <div>
                                            <InputLabel :for="'slide_image_' + index" class="text-xs font-bold text-slate-500 mb-2" value="Gambar Latar Belakang Slide" />
                                            <FileUpload
                                                v-slot="fileUploadProps"
                                                v-model="form['slide_image_' + index]"
                                                accept="image/*"
                                                :max-size-mb="10"
                                                helper-text="PNG, JPG, JPEG, WEBP hingga 10MB"
                                                :preview-url="slide.image"
                                                preview
                                                :error="form.errors['slide_image_' + index]"
                                            />
                                        </div>
                                        
                                        <div>
                                            <InputLabel :for="'slide_title1_' + index" class="text-xs font-bold text-slate-500" value="Judul Bagian 1 (e.g. Membangun Kebersamaan)" />
                                            <TextInput
                                                :id="'slide_title1_' + index"
                                                v-model="slide.titlePart1"
                                                type="text"
                                                class="mt-2 block w-full px-4 py-2.5 bg-white"
                                            />
                                        </div>
                                        
                                        <div>
                                            <InputLabel :for="'slide_title_hl_' + index" class="text-xs font-bold text-slate-500" value="Judul Sorotan / Highlight (e.g. Lewat Gotong Royong)" />
                                            <TextInput
                                                :id="'slide_title_hl_' + index"
                                                v-model="slide.titleHighlight"
                                                type="text"
                                                class="mt-2 block w-full px-4 py-2.5 bg-white text-emerald-700 font-semibold"
                                            />
                                        </div>
                                        
                                        <div class="col-span-1 md:col-span-2">
                                            <InputLabel :for="'slide_desc_' + index" class="text-xs font-bold text-slate-500" value="Deskripsi Singkat Slide" />
                                            <textarea
                                                :id="'slide_desc_' + index"
                                                v-model="slide.description"
                                                class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm px-4 py-3 bg-white leading-relaxed transition-all duration-300"
                                                rows="3"
                                                placeholder="Tulis deskripsi slide..."
                                            ></textarea>
                                        </div>
                                        
                                        <div>
                                            <InputLabel :for="'slide_cta_' + index" class="text-xs font-bold text-slate-500" value="Teks Tombol CTA (e.g. Lihat Perjalanan)" />
                                            <TextInput
                                                :id="'slide_cta_' + index"
                                                v-model="slide.ctaText"
                                                type="text"
                                                class="mt-2 block w-full px-4 py-2.5 bg-white"
                                            />
                                        </div>
                                        
                                        <div>
                                            <InputLabel :for="'slide_link_' + index" class="text-xs font-bold text-slate-500" value="Link Tombol CTA (e.g. /perjalanan)" />
                                            <TextInput
                                                :id="'slide_link_' + index"
                                                v-model="slide.ctaLink"
                                                type="text"
                                                class="mt-2 block w-full px-4 py-2.5 bg-white font-mono text-xs"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex justify-end gap-3 pt-5 border-t border-gray-100 mt-8">
                            <SecondaryButton type="button" class="rounded-xl px-5 py-2.5 font-bold" @click="cancelEdit">
                                Batal
                            </SecondaryButton>
                            <PrimaryButton type="submit" class="rounded-xl px-5 py-2.5 font-bold text-white bg-primary hover:bg-primary-dark" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Simpan Perubahan
                            </PrimaryButton>
                        </div>
                    </form>
                </AdminSectionCard>

                <!-- Pengaturan Hero Slider Card (View Mode Only) -->
                <AdminSectionCard 
                    v-if="!isEditing"
                    title="Pengaturan Hero Slider" 
                    description="Berikut adalah daftar slide pada bagian Hero halaman utama website saat ini."
                >


                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <div 
                            v-for="(slide, index) in heroSlides" 
                            :key="index"
                            class="bg-white border border-gray-100 rounded-2xl p-5 shadow-2xs hover:shadow-xs transition-all duration-300 relative overflow-hidden flex flex-col justify-between"
                        >
                            <div>
                                <!-- Mini image preview or path -->
                                <div class="h-32 w-full rounded-xl bg-slate-900 relative overflow-hidden mb-4 border border-slate-100 shadow-3xs">
                                    <img :src="slide.image" :alt="slide.alt || slide.titlePart1" class="absolute inset-0 h-full w-full object-cover opacity-60" />
                                    <div class="absolute top-3 left-3 bg-black/40 text-white text-[10px] font-bold px-2 py-1 rounded-md backdrop-blur-xs">
                                        Slide #{{ index + 1 }}
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tagline / Label</span>
                                        <div>
                                            <p class="text-[10px] font-semibold text-emerald-700 bg-emerald-50/80 px-2.5 py-1 rounded-md inline-block mt-0.5">{{ slide.tagline }}</p>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Judul Slide</span>
                                        <h4 class="text-sm font-bold text-gray-800 leading-snug mt-0.5">
                                            {{ slide.titlePart1 }} <span class="text-emerald-600">{{ slide.titleHighlight }}</span>
                                        </h4>
                                    </div>
                                    
                                    <div>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Deskripsi</span>
                                        <p class="text-xs text-slate-500 font-semibold leading-relaxed line-clamp-3 mt-0.5">{{ slide.description }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-4 border-t border-gray-50 text-[10px] font-bold text-gray-400 mt-4">
                                <div>
                                    <span>Teks Tombol:</span>
                                    <p class="text-xs text-gray-700 font-bold mt-0.5">{{ slide.ctaText }}</p>
                                </div>
                                <div>
                                    <span>Link Tombol:</span>
                                    <p class="text-xs text-gray-700 font-semibold mt-0.5 font-mono truncate">{{ slide.ctaLink }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </AdminSectionCard>
                
            </div>
        </div>

        <ToastNotification v-if="toastMessage" :message="toastMessage" :type="toastType" />
    </AdminLayout>
</template>