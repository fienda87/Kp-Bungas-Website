<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const isSidebarOpen = ref(true);

const navItems = [
    { name: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', route: 'admin.dashboard' },
    { name: 'Berita & Artikel', icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', route: 'admin.news.index' },
    { name: 'Program', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', route: 'admin.programs.index' },
    { name: 'Galeri', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', route: 'admin.galleries.index' },
];

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Sidebar -->
        <aside
            :class="[
                'bg-primary text-white transition-all duration-300 flex flex-col',
                isSidebarOpen ? 'w-64' : 'w-20'
            ]"
        >
            <div class="p-6 flex items-center justify-between">
                <span v-if="isSidebarOpen" class="text-xl font-bold tracking-tight">Kampung Bungas</span>
                <span v-else class="text-xl font-bold mx-auto">KB</span>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-2">
                <Link
                    v-for="item in navItems"
                    :key="item.name"
                    :href="route(item.route)"
                    :class="[
                        'flex items-center p-3 rounded-lg hover:bg-primary-dark transition-colors',
                        route().current(item.route + '*') ? 'bg-primary-dark shadow-inner' : ''
                    ]"
                    :title="item.name"
                >
                    <svg
                        class="w-6 h-6 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            :d="item.icon"
                        />
                    </svg>
                    <span v-if="isSidebarOpen" class="ml-3 font-medium">{{ item.name }}</span>
                </Link>
            </nav>

            <div class="p-4 border-t border-primary-dark">
                <Link 
                    :href="route('logout')" 
                    method="post" 
                    as="button"
                    class="flex items-center w-full p-3 rounded-lg hover:bg-red-600 transition-colors text-white"
                >
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span v-if="isSidebarOpen" class="ml-3 font-medium">Keluar</span>
                </Link>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 shrink-0">
                <button @click="toggleSidebar" class="p-2 rounded-md hover:bg-gray-100 text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900">Administrator</p>
                        <p class="text-xs text-gray-500">{{ $page.props.auth.user.email }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                        {{ $page.props.auth.user.name[0] }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Header Slot for page specific title/actions -->
                    <div v-if="$slots.header" class="mb-8">
                        <slot name="header" />
                    </div>
                    
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
