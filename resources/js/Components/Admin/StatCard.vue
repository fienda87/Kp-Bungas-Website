<script setup>
defineProps({
    title: String,
    value: [String, Number],
    icon: String,
    trend: String,
    trendType: {
        type: String,
        default: 'up' // up, down, neutral
    }
});
</script>

<template>
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ title }}</p>
                <h3 class="mt-2 text-3xl font-bold text-gray-900">{{ value }}</h3>
            </div>
            <div class="p-3 bg-primary-extralight rounded-xl text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icon" />
                </svg>
            </div>
        </div>
        <div v-if="trend" class="mt-4 flex items-center">
            <span 
                :class="[
                    'text-xs font-semibold px-2 py-1 rounded-full',
                    trendType === 'up' ? 'bg-green-100 text-green-700' : 
                    trendType === 'down' ? 'bg-red-100 text-red-700' : 
                    'bg-gray-100 text-gray-700'
                ]"
            >
                {{ trend }}
            </span>
            <span class="ml-2 text-xs text-gray-400">vs bulan lalu</span>
        </div>
    </div>
</template>
