<template>
    <footer class="bg-gray-900 text-gray-400 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-center md:text-left">
                    <span class="text-white font-bold text-xl">KP Bungas</span>
                    <p class="text-sm mt-1">Kelompok Pemuda Bungas — Bersatu, Berkarya, Berdaya</p>
                </div>
                <div class="text-sm text-center">
                    &copy; {{ currentYear }} KP Bungas. Semua hak dilindungi.
                </div>
                <div class="flex gap-4">
                    <a
                        v-for="social in socials"
                        :key="social.label"
                        :href="social.href"
                        :aria-label="social.label"
                        class="hover:text-white transition-colors duration-200 text-xl"
                    >
                        {{ social.icon }}
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
const currentYear = new Date().getFullYear();

const socials = [
    { icon: '📘', label: 'Facebook', href: '#' },
    { icon: '📸', label: 'Instagram', href: '#' },
    { icon: '🐦', label: 'Twitter / X', href: '#' },
];
</script>
