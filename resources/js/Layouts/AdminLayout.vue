<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ErrorBoundary from '../Components/ErrorBoundary.vue';

const page = usePage();
const isSidebarOpen = ref(true);
const isMounted = ref(false);

const navItems = computed(() => {
    const items = [
        { name: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', route: 'admin.dashboard' },
        { name: 'Berita & Artikel', icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', route: 'admin.news.index' },
        { name: 'Program', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', route: 'admin.programs.index' },
        { name: 'Galeri', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', route: 'admin.galleries.index' },
        { name: 'Pengguna', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', route: 'admin.users.index' },
        { name: 'Kelola Website', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', route: 'admin.settings.index' },
    ];

    return items;
});

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

onMounted(() => {
    // Prevent sidebar from covering viewport on mobile by default
    if (window.innerWidth < 768) {
        isSidebarOpen.value = false;
    }
    
    // Trigger entrance transition
    setTimeout(() => {
        isMounted.value = true;
    }, 50);
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex font-sans antialiased text-gray-800">
        <!-- Desktop Sidebar -->
        <aside
            class="hidden md:flex bg-primary text-white transition-all duration-300 flex-col shrink-0 border-r border-primary-dark/20 shadow-lg sticky top-0 h-screen"
            :class="isSidebarOpen ? 'w-64' : 'w-20'"
        >
            <div class="p-6 flex items-center justify-between h-16 shrink-0 border-b border-primary-dark/30 bg-primary-dark/10">
                <span 
                    class="text-xl font-bold tracking-tight transition-all duration-300 overflow-hidden whitespace-nowrap bg-gradient-to-r from-emerald-100 to-white bg-clip-text text-transparent"
                    :class="isSidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 pointer-events-none'"
                >
                    Kampung Bungas
                </span>
                <span 
                    class="text-xl font-black transition-all duration-300 overflow-hidden whitespace-nowrap mx-auto text-emerald-200"
                    :class="!isSidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 pointer-events-none'"
                >
                    KB
                </span>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
                <Link
                    v-for="item in navItems"
                    :key="item.name"
                    :href="route(item.route)"
                    :class="[
                        'relative flex items-center p-3 rounded-xl transition-all duration-300 group overflow-hidden',
                        route().current(item.route + '*') 
                            ? 'bg-primary-dark/60 text-white font-semibold shadow-inner' 
                            : 'text-white/80 hover:text-white hover:bg-primary-light/20'
                    ]"
                    :title="item.name"
                >
                    <!-- Left Active Indicator Pill -->
                    <span 
                        class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-400 rounded-r-full transition-all duration-300"
                        :class="route().current(item.route + '*') ? 'scale-y-100 opacity-100' : 'scale-y-0 opacity-0'"
                    ></span>

                    <svg
                        class="w-6 h-6 shrink-0 transition-transform duration-300 group-hover:scale-110"
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
                    <span 
                        class="ml-3.5 text-sm font-semibold transition-all duration-300 whitespace-nowrap overflow-hidden"
                        :class="isSidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 pointer-events-none'"
                    >
                        {{ item.name }}
                    </span>
                </Link>
            </nav>

            <div class="p-3 border-t border-primary-dark/30 bg-primary-dark/10">
                <Link 
                    :href="route('logout')" 
                    method="post" 
                    as="button"
                    class="flex items-center w-full p-3 rounded-xl hover:bg-red-600/90 transition-all duration-300 group text-white/80 hover:text-white cursor-pointer"
                >
                    <svg class="w-6 h-6 shrink-0 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span 
                        class="ml-3.5 font-medium transition-all duration-300 whitespace-nowrap overflow-hidden"
                        :class="isSidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0 pointer-events-none'"
                    >
                        Keluar
                    </span>
                </Link>
            </div>
        </aside>

        <!-- Mobile Sidebar (slide-over) -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="isSidebarOpen" class="md:hidden fixed inset-0 z-40 flex">
                <!-- Backdrop blur overlay -->
                <div class="fixed inset-0 bg-black/45 backdrop-blur-xs transition-opacity" @click="isSidebarOpen = false"></div>
                
                <aside 
                    class="relative w-64 max-w-xs bg-primary text-white flex flex-col shadow-2xl transition duration-300 ease-out transform"
                    :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                >
                    <div class="p-6 flex items-center justify-between border-b border-primary-dark/30 bg-primary-dark/10">
                        <span class="text-xl font-bold tracking-tight">Kampung Bungas</span>
                        <button @click="isSidebarOpen = false" class="text-white p-1 hover:bg-primary-dark/40 rounded-xl transition-colors cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
                        <Link
                            v-for="item in navItems"
                            :key="item.name"
                            :href="route(item.route)"
                            :class="[
                                'relative flex items-center p-3 rounded-xl transition-all duration-200 group',
                                route().current(item.route + '*') 
                                    ? 'bg-primary-dark/80 text-white font-semibold' 
                                    : 'text-white/80 hover:text-white hover:bg-primary-light/20'
                            ]"
                            @click="isSidebarOpen = false"
                        >
                            <svg class="w-6 h-6 shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                            </svg>
                            <span class="ml-3.5 text-sm font-semibold">{{ item.name }}</span>
                        </Link>
                    </nav>
                    <div class="p-3 border-t border-primary-dark/30 bg-primary-dark/10">
                        <Link :href="route('logout')" method="post" as="button" class="flex items-center w-full p-3 rounded-xl hover:bg-red-600/90 transition-all duration-200 text-white/80 hover:text-white group cursor-pointer">
                            <svg class="w-6 h-6 shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="ml-3.5 font-medium">Keluar</span>
                        </Link>
                    </div>
                </aside>
            </div>
        </transition>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Topbar (Glassmorphism design) -->
            <header class="bg-white/85 backdrop-blur-md border-b border-gray-100 h-16 flex items-center justify-between px-6 shrink-0 z-30 shadow-xs">
                <button @click="toggleSidebar" class="p-2 rounded-xl hover:bg-gray-50 text-gray-500 hover:text-primary transition-all duration-200 cursor-pointer active:scale-95">
                    <svg class="w-5 h-5 transition-transform duration-300" :class="!isSidebarOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900 leading-tight">Administrator</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $page.props.auth.user.email }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold hover:bg-primary/20 hover:scale-105 active:scale-95 cursor-pointer shadow-inner transition-all duration-300">
                        {{ $page.props.auth.user.name[0] }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 bg-gray-50/50">
                <div 
                    class="max-w-7xl mx-auto transition-all duration-500 ease-out transform"
                    :class="isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                >
                    <!-- Header Slot for page specific title/actions -->
                    <div v-if="$slots.header" class="mb-8">
                        <slot name="header" />
                    </div>
                    
                    <ErrorBoundary>
                        <slot />
                    </ErrorBoundary>
                </div>
            </main>
        </div>
    </div>
</template>

