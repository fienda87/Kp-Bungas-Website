<script setup>
import { Link } from '@inertiajs/vue3';
import { useUIStore } from '../../Stores/uiStore';

const uiStore = useUIStore();

const navItems = [
    { label: 'Tentang', href: '/about' },
    { label: 'Program', href: '/program' },
    { label: 'Perjalanan', href: '/perjalanan' },
    { label: 'Dampak', href: '/dampak' },
    { label: 'Galeri', href: '/galeri' },
    { label: 'Berita Artikel', href: '#berita' },
];

const logoUrl = '/images/program/logo.png';
</script>

<template>
    <header class="sticky top-0 z-40 border-b border-slate-100 bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-20 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <Link href="/" class="flex items-center gap-3">
                <img :src="logoUrl" alt="Kampung Bungas" class="h-12 w-12 object-contain" />
                <div class="leading-tight">
                    <p class="text-lg font-bold text-slate-900">Kampung Bungas</p>
                    <p class="text-xs font-medium text-emerald-500">Buah • Bunga • Sayur</p>
                </div>
            </Link>

            <nav class="hidden items-center gap-6 lg:flex">
                <Link
                    v-for="item in navItems"
                    :key="item.label"
                    :href="item.href"
                    class="text-base font-medium text-slate-800 transition hover:text-emerald-600"
                    @click="uiStore.setActiveSection(item.label.toLowerCase())"
                >
                    {{ item.label }}
                </Link>
                <Link
                    href="/kunjungi-kami"
                    class="rounded-full bg-emerald-600 px-6 py-3 text-base font-semibold text-white transition hover:bg-emerald-700"
                >
                    Kunjungi Kami
                </Link>
            </nav>

            <div class="flex items-center gap-4 lg:hidden">
                <Link
                    href="/kunjungi-kami"
                    class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white"
                >
                    Kunjungi
                </Link>
                <button
                    class="text-slate-600 focus:outline-none"
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
