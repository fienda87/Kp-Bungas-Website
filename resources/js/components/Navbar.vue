<template>
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="#" class="text-2xl font-bold text-green-700">KP Bungas</a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a
                        v-for="item in navItems"
                        :key="item.href"
                        :href="item.href"
                        class="text-gray-600 hover:text-green-700 font-medium transition-colors duration-200"
                    >
                        {{ item.label }}
                    </a>
                </div>
                <div class="md:hidden">
                    <button @click="isOpen = !isOpen" class="text-gray-600 hover:text-green-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path v-if="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div v-if="isOpen" class="md:hidden pb-4">
                <div class="flex flex-col space-y-2">
                    <a
                        v-for="item in navItems"
                        :key="item.href"
                        :href="item.href"
                        @click="isOpen = false"
                        class="text-gray-600 hover:text-green-700 font-medium py-2 transition-colors duration-200"
                    >
                        {{ item.label }}
                    </a>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue';

const isOpen = ref(false);

const navItems = [
    { label: 'Beranda', href: '#beranda' },
    { label: 'Tentang Kami', href: '#tentang' },
    { label: 'Program', href: '#program' },
    { label: 'Kegiatan', href: '#kegiatan' },
    { label: 'Kontak', href: '#kontak' },
];
</script>
