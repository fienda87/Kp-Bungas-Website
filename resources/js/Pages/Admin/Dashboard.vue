<script setup>
import AppLayout from '@/Layouts/Admin/AppLayout.vue';
import StatCard from '@/Components/Admin/StatCard.vue';
import { Head } from '@inertiajs/vue3';

const stats = [
    { title: 'Total Berita', value: '24', icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', trend: '+12%', trendType: 'up' },
    { title: 'Program Aktif', value: '8', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', trend: 'Stabil', trendType: 'neutral' },
    { title: 'Total Galeri', value: '15', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', trend: '+3', trendType: 'up' },
    { title: 'User Terdaftar', value: '1', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', trend: 'Admin Only', trendType: 'neutral' },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, Admin</h1>
            <p class="text-gray-500">Berikut adalah ringkasan konten website Anda hari ini.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <StatCard 
                v-for="stat in stats" 
                :key="stat.title"
                v-bind="stat"
            />
        </div>

        <!-- Recent Activities / Quick Links -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Berita Terbaru</h3>
                <div class="space-y-4">
                    <div v-for="i in 3" :key="i" class="flex items-center p-3 hover:bg-gray-50 rounded-xl transition-colors cursor-pointer border border-transparent hover:border-gray-100">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg shrink-0"></div>
                        <div class="ml-4">
                            <p class="font-semibold text-gray-900">Judul Berita Ke-{{ i }}</p>
                            <p class="text-xs text-gray-500">Dipublikasikan pada 14 Mei 2026 • Kategori Berita</p>
                        </div>
                    </div>
                </div>
                <button class="mt-6 text-primary font-bold text-sm hover:underline">Lihat Semua Berita →</button>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-2 gap-4">
                    <button class="p-4 bg-primary-extralight text-primary rounded-xl font-bold text-sm hover:bg-primary hover:text-white transition-all flex flex-col items-center justify-center space-y-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tulis Berita</span>
                    </button>
                    <button class="p-4 bg-primary-extralight text-primary rounded-xl font-bold text-sm hover:bg-primary hover:text-white transition-all flex flex-col items-center justify-center space-y-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Upload Foto</span>
                    </button>
                    <button class="p-4 bg-primary-extralight text-primary rounded-xl font-bold text-sm hover:bg-primary hover:text-white transition-all flex flex-col items-center justify-center space-y-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Bantuan</span>
                    </button>
                    <button class="p-4 bg-primary-extralight text-primary rounded-xl font-bold text-sm hover:bg-primary hover:text-white transition-all flex flex-col items-center justify-center space-y-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Pengaturan</span>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
