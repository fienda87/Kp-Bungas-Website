<script setup>
import { Link } from '@inertiajs/vue3';

const logoUrl = '/images/program/logo.png';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-50 to-neutral-200 text-slate-900">
        <div class="mx-auto flex min-h-screen w-full max-w-6xl flex-col items-center justify-center gap-8 px-6 py-10">
            <Link href="/" class="flex items-center gap-4">
                <img :src="logoUrl" alt="Kampung Bungas" class="h-16 w-16 rounded-full bg-white p-1 shadow" />
                <div class="leading-tight">
                    <p class="text-xl font-semibold">Kampung Bungas</p>
                    <p class="text-sm font-medium text-emerald-600">Buah • Bunga • Sayur</p>
                </div>
            </Link>

            <div class="w-full max-w-xl rounded-2xl bg-white/90 p-10 text-slate-900 shadow-2xl backdrop-blur">
                <slot />
            </div>
        </div>
    </div>
</template>
