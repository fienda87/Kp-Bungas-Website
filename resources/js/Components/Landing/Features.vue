<script setup>
const features = [
    {
        title: 'Urban Farming',
        description: 'Lahan kecil kota dimaksimalkan untuk menghasilkan pangan sehat dan segar setiap hari.',
    },
    {
        title: 'Hidroponik Komunitas',
        description: 'Program bersama warga untuk belajar, menanam, dan panen dalam sistem hidroponik berkelanjutan.',
    },
    {
        title: 'Edukasi Hijau',
        description: 'Kelas dan aktivitas lingkungan untuk anak muda, keluarga, dan komunitas sekitar.',
    },
];
</script>

<template>
    <section id="program" class="bg-slate-50 py-20">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Program Kampung Bungas</p>
                <h2 class="mt-3 text-3xl font-bold text-slate-900 sm:text-4xl">Inisiatif Hijau Berbasis Warga</h2>
                <p class="mt-4 text-lg text-slate-600">
                    Bagian ini ditambahkan sebagai section fitur agar struktur page lebih mudah dikembangkan ke depan,
                    tetap selaras dengan semangat visual dan konten dari desain Figma.
                </p>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="feature in features"
                    :key="feature.title"
                    class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                >
                    <h3 class="text-xl font-bold text-slate-900">{{ feature.title }}</h3>
                    <p class="mt-3 text-base leading-relaxed text-slate-600">{{ feature.description }}</p>
                </article>
            </div>
        </div>
    </section>
</template>
