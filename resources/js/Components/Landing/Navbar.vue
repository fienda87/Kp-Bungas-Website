<script setup>
import { Link } from '@inertiajs/vue3';

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

            <Link
                href="/kunjungi-kami"
                class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white lg:hidden"
            >
                Kunjungi
            </Link>
        </div>
    </header>
</template>
