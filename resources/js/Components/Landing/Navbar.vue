<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { useUIStore } from '../../Stores/uiStore';

const uiStore = useUIStore();
const page = usePage();

const settings = computed(() => page.props.settings || {});
const siteName = computed(() => settings.value.site_name || 'Kampung Bungas');
const siteTagline = computed(() => settings.value.site_tagline || 'Buah • Bunga • Sayur');

const navItems = [
    { label: 'Tentang', href: route('about'), routeName: 'about' },
    { label: 'Program', href: route('program'), routeName: 'program' },
    { label: 'Perjalanan', href: route('perjalanan'), routeName: 'perjalanan' },
    { label: 'Dampak', href: route('dampak'), routeName: 'dampak' },
    { label: 'Galeri', href: route('galeri'), routeName: 'galeri' },
    { label: 'Berita Artikel', href: route('news.index'), routeName: 'news' },
];

const logoUrl = '/images/program/logo.png';
</script>

<template>
    <header class="sticky top-0 z-40 border-b border-slate-100 bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-20 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <Link href="/" class="flex items-center gap-3 select-none">
                <img :src="logoUrl" alt="Kampung Bungas" class="h-12 w-12 object-contain" />
                <div class="leading-tight">
                    <p class="text-lg font-bold text-slate-900">{{ siteName }}</p>
                    <p class="text-xs font-medium text-emerald-500">{{ siteTagline }}</p>
                </div>
            </Link>

            <nav class="hidden items-center gap-6 lg:flex">
                <Link
                    v-for="item in navItems"
                    :key="item.label"
                    :href="item.href"
                    class="relative py-2 text-base font-medium transition-colors duration-200 group cursor-pointer"
                    :class="route().current(item.routeName + '*') ? 'text-emerald-600' : 'text-slate-800 hover:text-emerald-600'"
                    @click="uiStore.setActiveSection(item.label.toLowerCase())"
                >
                    {{ item.label }}
                    <!-- Sliding Underline Indicator -->
                    <span 
                        class="absolute bottom-0 left-0 h-0.5 bg-emerald-500 transition-all duration-300 ease-out"
                        :class="route().current(item.routeName + '*') ? 'w-full' : 'w-0 group-hover:w-full'"
                    />
                </Link>
                <Link
                    :href="route('kunjungi-kami')"
                    class="rounded-full bg-emerald-600 px-6 py-3 text-base font-semibold text-white transition hover:bg-emerald-700 active:scale-95 duration-200"
                >
                    Kunjungi Kami
                </Link>
            </nav>

            <div class="flex items-center gap-4 lg:hidden">
                <Link
                    :href="route('kunjungi-kami')"
                    class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white"
                >
                    Kunjungi
                </Link>
                <button
                    class="text-slate-600 focus:outline-none w-6 h-6 relative cursor-pointer group"
                    @click="uiStore.toggleNavbar"
                    aria-label="Toggle Menu"
                >
                    <!-- First morphing line -->
                    <span 
                        class="absolute block h-0.5 w-6 bg-slate-600 rounded-full transition-all duration-300 left-0"
                        :class="uiStore.isNavbarOpen ? 'rotate-45 top-3 bg-emerald-600 font-bold' : 'top-1.5 group-hover:bg-emerald-500'"
                    />
                    <!-- Second morphing line (middle) -->
                    <span 
                        class="absolute block h-0.5 w-6 bg-slate-600 rounded-full transition-all duration-300 left-0 top-3"
                        :class="uiStore.isNavbarOpen ? 'opacity-0 scale-x-0' : 'group-hover:bg-emerald-500'"
                    />
                    <!-- Third morphing line -->
                    <span 
                        class="absolute block h-0.5 w-6 bg-slate-600 rounded-full transition-all duration-300 left-0"
                        :class="uiStore.isNavbarOpen ? '-rotate-45 top-3 bg-emerald-600 font-bold' : 'top-4.5 group-hover:bg-emerald-500'"
                    />
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-4"
        >
            <div v-if="uiStore.isNavbarOpen" class="border-b border-slate-100 bg-white/95 backdrop-blur-md lg:hidden shadow-lg shadow-slate-100/40 relative z-30">
                <div class="space-y-1.5 px-4 pb-6 pt-3">
                    <Link
                        v-for="item in navItems"
                        :key="item.label"
                        :href="item.href"
                        class="flex items-center justify-between rounded-xl px-4 py-3 text-base font-semibold transition-all duration-200 active:scale-[0.98] cursor-pointer"
                        :class="route().current(item.routeName + '*') 
                            ? 'bg-emerald-50 text-emerald-700 shadow-xs border-l-4 border-emerald-500' 
                            : 'text-slate-700 hover:bg-slate-50 hover:text-emerald-600 border-l-4 border-transparent'"
                        @click="uiStore.isNavbarOpen = false"
                    >
                        <span>{{ item.label }}</span>
                        <!-- Custom indicator arrow -->
                        <svg 
                            class="w-4 h-4 text-emerald-500 transition-all duration-300 transform" 
                            :class="route().current(item.routeName + '*') ? 'translate-x-0 opacity-100 scale-100' : '-translate-x-2 opacity-0 scale-50'"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>
            </div>
        </transition>
    </header>
</template>
