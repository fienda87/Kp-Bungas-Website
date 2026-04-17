<script setup>
import { Link } from '@inertiajs/vue3';

const heroImage = '/images/impact/hero-image.png';
</script>

<template>
    <section id="home" class="relative isolate min-h-[calc(100vh-5rem)] overflow-hidden">
        <img
            :src="heroImage"
            alt="Kebun Kampung Bungas"
            class="absolute inset-0 h-full w-full object-cover"
        />
        <div class="absolute inset-0 bg-linear-to-b from-black/50 via-black/40 to-black/70" />

        <div class="relative mx-auto flex min-h-[calc(100vh-5rem)] w-full max-w-7xl items-center px-4 py-16 text-white sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <p class="mx-auto mb-6 inline-flex items-center rounded-full bg-white/20 px-4 py-2 text-sm font-medium backdrop-blur">
                    Balikpapan, Indonesia
                </p>

                <h1 class="text-4xl font-extrabold leading-tight sm:text-5xl lg:text-7xl">
                    Merawat Pertiwi dari
                    <span class="block text-emerald-400">Halaman Rumah Sendiri</span>
                </h1>

                <p class="mx-auto mt-6 max-w-3xl text-base leading-relaxed text-slate-200 sm:text-lg lg:text-2xl">
                    Kampung hijau berbasis komunitas yang mengubah daerah perkotaan menjadi ruang hijau produktif melalui
                    pertanian perkotaan dan hidroponik.
                </p>

                <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                    <Link
                        href="/program"
                        class="rounded-full bg-emerald-600 px-8 py-3 text-base font-semibold text-white shadow-lg shadow-black/25 transition hover:bg-emerald-700"
                    >
                        Jelajahi Kampung
                    </Link>
                    <a
                        id="kunjungi"
                        href="#footer"
                        class="rounded-full border-2 border-white/60 bg-white/20 px-8 py-3 text-base font-semibold text-white backdrop-blur transition hover:bg-white/30"
                    >
                        Kunjungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
