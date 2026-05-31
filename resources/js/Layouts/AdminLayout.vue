<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import ErrorBoundary from '../Components/ErrorBoundary.vue';
import SkeletonLoader from '../Components/SkeletonLoader.vue';

const page = usePage();
const isSidebarOpen = ref(true);
const isLoading = ref(false);

let removeStartEventListener = null;
let removeFinishEventListener = null;

onMounted(() => {
    removeStartEventListener = router.on('start', () => isLoading.value = true);
    removeFinishEventListener = router.on('finish', () => isLoading.value = false);
});

onUnmounted(() => {
    if (removeStartEventListener) removeStartEventListener();
    if (removeFinishEventListener) removeFinishEventListener();
});

const navItems = computed(() => {
    const items = [
        { name: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', route: 'admin.dashboard' },
        { name: 'Berita & Artikel', icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', route: 'admin.news.index' },
        { name: 'Program', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', route: 'admin.programs.index' },
        { name: 'Galeri', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', route: 'admin.galleries.index' },
        { name: 'Pengguna', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', route: 'admin.users.index' },
        { name: 'Kelola Informasi Website', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', route: 'admin.settings.index' },
    ];

    return items;
});

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Desktop Sidebar -->
        <aside
            class="hidden md:flex bg-primary text-white transition-all duration-300 flex-col"
            :class="isSidebarOpen ? 'w-64' : 'w-20'"
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

        <!-- Mobile Sidebar (slide-over) -->
        <transition name="fade">
            <div v-if="isSidebarOpen" class="md:hidden fixed inset-0 z-40">
                <div class="absolute inset-0 bg-black/40" @click="isSidebarOpen = false"></div>
                <aside class="absolute left-0 top-0 bottom-0 w-64 bg-primary text-white flex flex-col shadow-lg">
                    <div class="p-6 flex items-center justify-between">
                        <span class="text-xl font-bold tracking-tight">Kampung Bungas</span>
                        <button @click="isSidebarOpen = false" class="text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
                        <Link
                            v-for="item in navItems"
                            :key="item.name"
                            :href="route(item.route)"
                            class="flex items-center p-3 rounded-lg hover:bg-primary-dark transition-colors"
                        >
                            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                            </svg>
                            <span class="ml-3 font-medium">{{ item.name }}</span>
                        </Link>
                    </nav>
                    <div class="p-4 border-t border-primary-dark">
                        <Link :href="route('logout')" method="post" as="button" class="flex items-center w-full p-3 rounded-lg hover:bg-red-600 transition-colors text-white">
                            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="ml-3 font-medium">Keluar</span>
                        </Link>
                    </div>
                </aside>
            </div>
        </transition>

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
                    
                    <ErrorBoundary>
                        <div v-if="isLoading" class="space-y-6">
                            <!-- Skeleton layout for common admin pages -->
                            <SkeletonLoader type="card" class="h-24" />
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <SkeletonLoader type="card" class="h-32" />
                                <SkeletonLoader type="card" class="h-32" />
                                <SkeletonLoader type="card" class="h-32" />
                            </div>
                            <SkeletonLoader type="card" class="h-64" />
                        </div>
                        <div v-else>
                            <slot />
                        </div>
                    </ErrorBoundary>
                </div>
            </main>
        </div>
    </div>
</template>
