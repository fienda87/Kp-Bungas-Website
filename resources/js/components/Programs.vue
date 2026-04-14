<template>
    <section id="program" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Program Kami</h2>
                <div class="w-16 h-1 bg-green-600 mx-auto rounded mb-4"></div>
                <p class="text-gray-600 max-w-xl mx-auto">Berbagai program unggulan yang kami jalankan untuk pemberdayaan pemuda dan masyarakat.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="program in programs"
                    :key="program.title"
                    class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden"
                >
                    <div :class="[program.bgClass, 'p-6 text-center']">
                        <span class="text-5xl">{{ program.icon }}</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ program.title }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ program.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const programs = [
    {
        icon: '📚',
        title: 'Pendidikan & Pelatihan',
        description: 'Program pelatihan keterampilan dan pengembangan diri untuk anggota dan masyarakat sekitar.',
        bgClass: 'bg-blue-100',
    },
    {
        icon: '🌱',
        title: 'Lingkungan Hidup',
        description: 'Kegiatan penghijauan, kebersihan lingkungan, dan kesadaran ekologi bagi generasi muda.',
        bgClass: 'bg-green-100',
    },
    {
        icon: '🎨',
        title: 'Seni & Budaya',
        description: 'Pelestarian dan pengembangan seni budaya lokal melalui berbagai kegiatan kreatif.',
        bgClass: 'bg-yellow-100',
    },
    {
        icon: '⚽',
        title: 'Olahraga & Kesehatan',
        description: 'Turnamen olahraga dan program kesehatan untuk mendorong gaya hidup aktif.',
        bgClass: 'bg-red-100',
    },
    {
        icon: '💼',
        title: 'Kewirausahaan',
        description: 'Bimbingan dan pendampingan usaha bagi pemuda yang ingin memulai bisnis.',
        bgClass: 'bg-purple-100',
    },
    {
        icon: '🤝',
        title: 'Sosial & Kemasyarakatan',
        description: 'Bakti sosial, santunan, dan kegiatan gotong royong untuk mempererat kebersamaan.',
        bgClass: 'bg-orange-100',
    },
];
</script>
