<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { useUIStore } from '../../Stores/uiStore';
import { useDark, useToggle } from '@vueuse/core';

const uiStore = useUIStore();
const page = usePage();

const isDark = useDark();
const toggleDark = useToggle(isDark);

const settings = computed(() => page.props.settings || {});
const siteName = computed(() => settings.value.site_name || 'Kampung Bungas');
const siteTagline = computed(() => settings.value.site_tagline || 'Buah • Bunga • Sayur');

const navItems = [
    { label: 'Tentang', href: route('about') },
    { label: 'Program', href: route('program') },
    { label: 'Perjalanan', href: route('perjalanan') },
    { label: 'Dampak', href: route('dampak') },
    { label: 'Galeri', href: route('galeri') },
    { label: 'Berita Artikel', href: route('news.index') },
];

const logoUrl = '/images/program/logo.png';
</script>

<template>
    <header class="sticky top-0 z-40 border-b border-slate-100 bg-white/95 backdrop-blur dark:border-gray-800 dark:bg-gray-900/95 transition-colors duration-300">
        <div class="mx-auto flex h-20 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <Link href="/" class="flex items-center gap-3 select-none">
                <img :src="logoUrl" alt="Kampung Bungas" class="h-12 w-12 object-contain dark:brightness-110" />
                <div class="leading-tight">
                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ siteName }}</p>
                    <p class="text-xs font-medium text-emerald-500 dark:text-emerald-400">{{ siteTagline }}</p>
                </div>
            </Link>

            <nav class="hidden items-center gap-6 lg:flex">
                <Link
                    v-for="item in navItems"
                    :key="item.label"
                    :href="item.href"
                    class="text-base font-medium text-slate-800 transition hover:text-emerald-600 dark:text-gray-300 dark:hover:text-emerald-400"
                    @click="uiStore.setActiveSection(item.label.toLowerCase())"
                >
                    {{ item.label }}
                </Link>
                <div class="h-6 w-px bg-slate-200 dark:bg-gray-700"></div>
                <button @click="toggleDark()" class="p-2 text-slate-500 hover:text-emerald-600 dark:text-gray-400 dark:hover:text-emerald-400 transition-colors" aria-label="Toggle Dark Mode">
                    <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
                <Link
                    :href="route('kunjungi-kami')"
                    class="rounded-full bg-emerald-600 px-6 py-3 text-base font-bold text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-lg active:scale-95"
                >
                    Kunjungi Kami
                </Link>
            </nav>

            <div class="flex items-center gap-3 lg:hidden">
                <button @click="toggleDark()" class="p-2 text-slate-500 dark:text-gray-400" aria-label="Toggle Dark Mode">
                    <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
                <Link
                    :href="route('kunjungi-kami')"
                    class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-bold text-white transition-all active:scale-95"
                >
                    Kunjungi
                </Link>
                <button
                    class="text-slate-600 focus:outline-none dark:text-gray-300"
                    @click="uiStore.toggleNavbar"
                    aria-label="Toggle Menu"
                >
                    <svg v-if="!uiStore.isNavbarOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="-translate-y-full opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="-translate-y-full opacity-0"
        >
            <div v-if="uiStore.isNavbarOpen" class="border-b border-slate-100 bg-white lg:hidden">
                <div class="space-y-1 px-4 pb-6 pt-2">
                    <Link
                        v-for="item in navItems"
                        :key="item.label"
                        :href="item.href"
                        class="block rounded-lg px-3 py-3 text-base font-medium text-slate-800 hover:bg-slate-50 hover:text-emerald-600"
                        @click="uiStore.isNavbarOpen = false"
                    >
                        {{ item.label }}
                    </Link>
                </div>
            </div>
        </transition>
    </header>
</template>
