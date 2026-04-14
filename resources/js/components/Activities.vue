<template>
    <section id="kegiatan" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Kegiatan Terbaru</h2>
                <div class="w-16 h-1 bg-green-600 mx-auto rounded mb-4"></div>
                <p class="text-gray-600 max-w-xl mx-auto">Ikuti terus kegiatan dan agenda terbaru dari KP Bungas.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    v-for="activity in activities"
                    :key="activity.title"
                    class="border border-gray-200 rounded-xl overflow-hidden hover:border-green-400 transition-colors duration-200"
                >
                    <div class="bg-green-700 text-white text-center py-4">
                        <div class="text-3xl font-bold">{{ activity.day }}</div>
                        <div class="text-sm text-green-200">{{ activity.month }}</div>
                    </div>
                    <div class="p-6">
                        <span class="inline-block bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full mb-3">
                            {{ activity.category }}
                        </span>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ activity.title }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ activity.description }}</p>
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="mr-1">📍</span>
                            {{ activity.location }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const activities = [
    {
        day: '20',
        month: 'April 2025',
        category: 'Pendidikan',
        title: 'Workshop Kepemimpinan Pemuda',
        description: 'Workshop interaktif untuk mengembangkan jiwa kepemimpinan dan kemampuan manajerial anggota muda.',
        location: 'Balai Desa Bungas',
    },
    {
        day: '05',
        month: 'Mei 2025',
        category: 'Lingkungan',
        title: 'Aksi Tanam Pohon Bersama',
        description: 'Penanaman 500 pohon di area lingkungan sekitar sebagai wujud kepedulian terhadap alam.',
        location: 'Hutan Kota Bungas',
    },
    {
        day: '17',
        month: 'Mei 2025',
        category: 'Olahraga',
        title: 'Turnamen Sepak Bola Antar Desa',
        description: 'Kompetisi sepak bola tahunan yang mempertemukan tim dari berbagai desa untuk mempererat persaudaraan.',
        location: 'Lapangan Serbaguna Bungas',
    },
];
</script>
