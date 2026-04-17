<script setup>
const programs = [
    {
        id: 1,
        title: 'Urban Farming',
        description: 'Program penanaman di lahan kota yang terbatas dengan teknik maksimal untuk menghasilkan buah, bunga, dan sayur organik berkualitas.',
        icon: '🌾',
        image: 'https://via.placeholder.com/400x300?text=Urban+Farming',
    },
    {
        id: 2,
        title: 'Hidroponik Komunitas',
        description: 'Sistem pertanian berbasis air yang efisien, mudah dipelajari, dan bisa dikerjakan bersama-sama dalam komunitas.',
        icon: '💧',
        image: 'https://via.placeholder.com/400x300?text=Hidroponik',
    },
    {
        id: 3,
        title: 'Edukasi Lingkungan',
        description: 'Workshop, kelas, dan aktivitas pembelajaran untuk anak muda, keluarga, dan komunitas tentang keberlanjutan.',
        icon: '📚',
        image: 'https://via.placeholder.com/400x300?text=Edukasi',
    },
    {
        id: 4,
        title: 'Pengolahan Sampah',
        description: 'Program pengurangan sampah melalui kompos, daur ulang, dan manajemen limbah berkelanjutan berbasis rumah tangga.',
        icon: '♻️',
        image: 'https://via.placeholder.com/400x300?text=Sampah',
    },
];
</script>

<template>
    <section id="program-detail" class="bg-gradient-to-b from-slate-50 to-white py-24">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-20 max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Program Kampung Bungas</p>
                <h2 class="mt-3 text-4xl font-bold text-slate-900 sm:text-5xl">Inisiatif Hijau Kami</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-600">
                    Kampung Bungas menjalankan berbagai program yang dirancang untuk memberdayakan komunitas lokal dan menciptakan lingkungan hijau yang berkelanjutan.
                </p>
            </div>

            <!-- Programs Grid -->
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-2">
                <article
                    v-for="program in programs"
                    :key="program.id"
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-md transition hover:-translate-y-2 hover:shadow-xl"
                >
                    <!-- Program Image -->
                    <div class="relative h-48 overflow-hidden bg-slate-200">
                        <img
                            :src="program.image"
                            :alt="program.title"
                            class="h-full w-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent" />
                    </div>

                    <!-- Program Content -->
                    <div class="p-6">
                        <div class="mb-3 flex items-center gap-3">
                            <span class="text-4xl">{{ program.icon }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">{{ program.title }}</h3>
                        <p class="mt-2 text-slate-600">{{ program.description }}</p>

                        <a
                            href="#"
                            class="mt-4 inline-block text-sm font-semibold text-emerald-600 transition hover:text-emerald-700"
                        >
                            Pelajari Lebih Lanjut →
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
