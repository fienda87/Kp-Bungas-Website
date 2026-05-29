<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const logoUrl = '/images/program/logo.png';
const page = usePage();

const settings = computed(() => page.props.settings || {});

const siteName = computed(() => settings.value.site_name || 'Kampung Bungas');
const siteTagline = computed(() => settings.value.site_tagline || 'Buah • Bunga • Sayur');
const aboutText = computed(() => settings.value.about_footer || 'Komunitas hijau berbasis masyarakat yang mengubah lingkungan perkotaan menjadi ruang hijau produktif dan berkelanjutan.');
const contactEmail = computed(() => settings.value.contact_email || 'kampungbungasbalikpapan@gmail.com');
const contactPhone = computed(() => settings.value.contact_phone || '+62 812 5422 777');
const socialInstagram = computed(() => settings.value.social_instagram || 'https://instagram.com/kpbungas');
const socialFacebook = computed(() => settings.value.social_facebook || 'https://facebook.com/kpbungas');
const socialTiktok = computed(() => settings.value.social_tiktok || 'https://www.tiktok.com/@kpbungas');
const socialYoutube = computed(() => settings.value.social_youtube || 'https://www.youtube.com/@kpbungas');

const quickLinks = [
    { label: 'Tentang Kami', href: route('about') },
    { label: 'Program', href: route('program') },
    { label: 'Galeri', href: route('galeri') },
    { label: 'Berita', href: route('news.index') },
];

const actionLinks = [
    { label: 'Kunjungi Kami', href: route('kunjungi-kami') },
    { label: 'Masuk Admin', href: route('login') },
];
</script>

<template>
    <footer id="footer" class="bg-linear-to-b from-slate-900 to-black text-white">
        <div class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr_0.8fr]">
                <div>
                    <div class="flex items-center gap-3">
                        <img :src="logoUrl" alt="Kampung Bungas" class="h-12 w-12 object-contain" />
                        <div>
                            <p class="text-2xl font-bold">{{ siteName }}</p>
                            <p class="text-sm text-emerald-400">{{ siteTagline }}</p>
                        </div>
                    </div>
                    <p class="mt-4 max-w-lg text-base leading-relaxed text-slate-300">
                        {{ aboutText }}
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white">Jelajahi</h3>
                    <div class="mt-4 grid gap-3">
                        <Link
                            v-for="item in quickLinks"
                            :key="item.label"
                            :href="item.href"
                            class="text-sm text-slate-300 transition hover:text-emerald-300"
                        >
                            {{ item.label }}
                        </Link>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white">Akses Cepat</h3>
                    <div class="mt-4 grid gap-3">
                        <Link
                            v-for="item in actionLinks"
                            :key="item.label"
                            :href="item.href"
                            class="text-sm text-slate-300 transition hover:text-emerald-300"
                        >
                            {{ item.label }}
                        </Link>
                        <a :href="`mailto:${contactEmail}`" class="text-sm text-slate-300 transition hover:text-emerald-300">{{ contactEmail }}</a>
                        <a :href="`tel:${contactPhone}`" class="text-sm text-slate-300 transition hover:text-emerald-300">{{ contactPhone }}</a>
                    </div>
                </div>
            </div>

            <div class="mt-10 rounded-2xl border border-emerald-500/30 bg-linear-to-r from-emerald-600/20 to-emerald-500/10 px-4 py-5 text-center">
                <p class="text-base italic text-slate-200 sm:text-xl">
                    "Merawat Pertiwi dari Halaman Rumah Sendiri - Bersama Membangun Masa Depan yang Berkelanjutan"
                </p>
            </div>

            <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-slate-800 pt-6 text-sm text-slate-400">
                <p>© 2026 {{ siteName }}. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-6">
                    <Link :href="route('news.index')" class="hover:text-slate-200">Berita Terbaru</Link>
                    <Link :href="route('kunjungi-kami')" class="hover:text-slate-200">Hubungi Kami</Link>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-4 text-sm text-slate-400">
                <a :href="socialInstagram" target="_blank" rel="noreferrer" class="hover:text-emerald-300">Instagram</a>
                <a :href="socialFacebook" target="_blank" rel="noreferrer" class="hover:text-emerald-300">Facebook</a>
                <a :href="socialTiktok" target="_blank" rel="noreferrer" class="hover:text-emerald-300">TikTok</a>
                <a :href="socialYoutube" target="_blank" rel="noreferrer" class="hover:text-emerald-300">YouTube</a>
            </div>
        </div>
    </footer>
</template>
