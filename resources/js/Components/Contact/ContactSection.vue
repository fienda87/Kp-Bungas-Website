<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import ContactCard from './ContactCard.vue';

const page = usePage();
const settings = computed(() => page.props.settings || {});

const siteAddressLine1 = computed(() => settings.value.contact_address || 'Kampung Bungas, Jl. Ahmad Yani');
const siteAddressLine2 = computed(() => settings.value.contact_address_detail || 'Kelurahan Gunungsari Ilir, Kecamatan Balikpapan Tengah');
const siteAddressLine3 = computed(() => settings.value.contact_city || 'Balikpapan, Kalimantan Timur');
const siteEmail = computed(() => settings.value.contact_email || 'kampungbungasbalikpapan@gmail.com');
const sitePhone = computed(() => settings.value.contact_phone || '+62 812 5422 777');

// Social links from settings (editable via admin)
const socialInstagramLink = computed(() => settings.value.social_instagram || '');
const socialTiktokLink = computed(() => settings.value.social_tiktok || '');
const socialFacebookLink = computed(() => settings.value.social_facebook || '');
const socialYoutubeLink = computed(() => settings.value.social_youtube || '');

const contactItems = [
    {
        id: 'lokasi',
        icon: '/images/contact/icon-lokasi.svg',
        iconBgColor: '#00c950',
        title: 'Lokasi',
        lines: [
            siteAddressLine1.value,
            siteAddressLine2.value,
            siteAddressLine3.value,
        ],
        borderColor: '#dcfce7',
        bgGradient: 'linear-gradient(166.93426981689493deg, rgb(240, 253, 244) 0%, rgb(236, 253, 245) 100%)',
    },
    {
        id: 'email',
        icon: '/images/contact/icon-email.svg',
        iconBgColor: '#2b7fff',
        title: 'Email',
        lines: [siteEmail.value],
        borderColor: '#dbeafe',
        bgGradient: 'linear-gradient(168.87749438250933deg, rgb(239, 246, 255) 0%, rgb(236, 254, 255) 100%)',
    },
    {
        id: 'telepon',
        icon: '/images/contact/icon-telepon.svg',
        iconBgColor: '#ad46ff',
        title: 'Telepon',
        lines: [sitePhone.value, 'Suwanto ,S.T.'],
        borderColor: '#f3e8ff',
        bgGradient: 'linear-gradient(168.87749438250933deg, rgb(250, 245, 255) 0%, rgb(253, 242, 248) 100%)',
    },
    {
        id: 'sosmed',
        icon: 'https://api.iconify.design/lucide/share-2.svg?color=white',
        iconBgColor: '#06b6d4',
        title: 'Media Sosial',
        lines: [],
        borderColor: '#dbeefe',
        bgGradient: 'linear-gradient(168.87749438250933deg, rgba(230, 250, 255, 0.6) 0%, rgba(240, 255, 255, 0.4) 100%)',
    },
];

</script>

<template>
    <section id="kunjungi" class="bg-white px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-7xl">
            <!-- Header -->
            <div class="mx-auto max-w-4xl text-center">
                <span class="inline-flex rounded-full bg-[#dcfce7] px-4 py-1 text-sm font-semibold text-[#008236]">Hubungi Kami</span>
                <h1 class="mt-3 text-6xl font-bold leading-12 text-[#101828]">Mari Berkunjung</h1>
                <p class="mx-auto mt-4 max-w-2xl text-lg leading-7 text-[#4a5565]">
                    Kami terbuka untuk kolaborasi, kunjungan, dan pertanyaan
                </p>
            </div>

            <!-- Content Grid -->
            <div class="mt-16 grid gap-8 md:grid-cols-2 md:gap-12">
                <!-- Left Column: Contact Cards -->
                <div class="flex flex-col gap-6">
                    <ContactCard
                        v-for="item in contactItems"
                        :key="item.id"
                        :icon="item.icon"
                        :icon-bg-color="item.iconBgColor"
                        :title="item.title"
                        :lines="item.lines"
                        :border-color="item.borderColor"
                        :bg-gradient="item.bgGradient"
                    >
                        <template v-if="item.id === 'sosmed'">
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a :href="socialInstagramLink || '#'" target="_blank" rel="noreferrer" class="group flex items-center gap-2.5 rounded-full border border-pink-200 bg-pink-50/80 px-4 py-2.5 transition-all duration-300 hover:border-pink-300 hover:bg-pink-100 hover:shadow-sm">
                                    <svg class="h-5 w-5 text-pink-600 transition-transform duration-300 group-hover:scale-110 group-hover:text-pink-700" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                      <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-semibold text-pink-700">Instagram</span>
                                </a>
                                <a :href="socialTiktokLink || '#'" target="_blank" rel="noreferrer" class="group flex items-center gap-2.5 rounded-full border border-slate-200 bg-slate-50/80 px-4 py-2.5 transition-all duration-300 hover:border-slate-300 hover:bg-slate-100 hover:shadow-sm">
                                    <svg class="h-5 w-5 text-slate-700 transition-transform duration-300 group-hover:scale-110 group-hover:text-black" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                      <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 2.23-1.13 4.47-2.92 5.88-1.68 1.34-3.93 1.85-6.04 1.4-2.31-.47-4.32-2.18-5.22-4.34-.95-2.22-.84-4.88.35-6.99 1.11-2.02 3.12-3.4 5.37-3.79 1.23-.23 2.5-.16 3.69.21v4.18c-1.39-.36-2.93-.15-4.08.73-1.15.84-1.79 2.29-1.74 3.73.04 1.57 1.06 3.06 2.54 3.63 1.62.64 3.59.39 4.93-.72 1.07-.86 1.7-2.2 1.7-3.58.01-4.73.01-9.45.02-14.18h.02z" />
                                    </svg>
                                    <span class="text-sm font-semibold text-slate-700">TikTok</span>
                                </a>
                                <a :href="socialFacebookLink || '#'" target="_blank" rel="noreferrer" class="group flex items-center gap-2.5 rounded-full border border-blue-200 bg-blue-50/80 px-4 py-2.5 transition-all duration-300 hover:border-blue-300 hover:bg-blue-100 hover:shadow-sm">
                                    <svg class="h-5 w-5 text-blue-600 transition-transform duration-300 group-hover:scale-110 group-hover:text-blue-700" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                      <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-semibold text-blue-700">Facebook</span>
                                </a>
                                <a :href="socialYoutubeLink || '#'" target="_blank" rel="noreferrer" class="group flex items-center gap-2.5 rounded-full border border-red-200 bg-red-50/80 px-4 py-2.5 transition-all duration-300 hover:border-red-300 hover:bg-red-100 hover:shadow-sm">
                                    <svg class="h-5 w-5 text-red-600 transition-transform duration-300 group-hover:scale-110 group-hover:text-red-700" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                      <path fill-rule="evenodd" d="M21.582 6.186a2.665 2.665 0 00-1.87-1.878C17.981 3.8 12 3.8 12 3.8s-5.981 0-7.712.508a2.665 2.665 0 00-1.87 1.878C1.908 8.01 1.908 12 1.908 12s0 3.99.51 5.814a2.665 2.665 0 001.87 1.878C6.019 20.2 12 20.2 12 20.2s5.981 0 7.712-.508a2.665 2.665 0 001.87-1.878c.51-1.824.51-5.814.51-5.814s0-3.99-.51-5.814zM9.945 15.111V8.889l5.65 3.111-5.65 3.111z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-semibold text-red-700">YouTube</span>
                                </a>
                            </div>
                        </template>
                    </ContactCard>
                </div>

                <!-- Right Column: Google Maps -->
                <div class="flex items-start">
                    <div class="relative w-full overflow-hidden rounded-[16px] bg-[#e5e7eb] pt-[100%] h-80">
                        <iframe
                            class="absolute inset-0 h-full w-full border-0"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.7862836839497!2d117.17370732343072!3d-1.1963639987066637!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de43fabe0e00001%3A0x1234567890abc!2sJl.%20Ahmad%20Yani%2C%20Gunungsari%2C%20Balikpapan%2C%20East%20Kalimantan!5e0!3m2!1sen!2sid!4v1713350000000"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi Kampung Bungas"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
