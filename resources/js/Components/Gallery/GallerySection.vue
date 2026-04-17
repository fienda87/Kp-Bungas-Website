<script setup>
import { computed, ref } from 'vue';
import GalleryPagination from './GalleryPagination.vue';

const galleryItems = [
    { id: 1, src: '/images/gallery/galeri-1.jpg', alt: 'Bibit tanaman di media tanam hidroponik 1' },
    { id: 2, src: '/images/gallery/galeri-2.jpg', alt: 'Tanaman cabai dengan buah berwarna 1' },
    { id: 3, src: '/images/gallery/galeri-3.jpg', alt: 'Deretan tanaman hias kampung 1' },
    { id: 4, src: '/images/gallery/galeri-4.jpg', alt: 'Rumah kaca dan area tanam komunitas 1' },
    { id: 5, src: '/images/gallery/galeri-5.jpg', alt: 'Ruang hijau dan papan edukasi kebun 1' },
    { id: 6, src: '/images/gallery/galeri-6.jpg', alt: 'Tanaman kaktus dan dedaunan tropis 1' },
    { id: 7, src: '/images/gallery/galeri-2.jpg', alt: 'Tanaman cabai dengan buah berwarna 2' },
    { id: 8, src: '/images/gallery/galeri-1.jpg', alt: 'Bibit tanaman di media tanam hidroponik 2' },
    { id: 9, src: '/images/gallery/galeri-4.jpg', alt: 'Rumah kaca dan area tanam komunitas 2' },
    { id: 10, src: '/images/gallery/galeri-3.jpg', alt: 'Deretan tanaman hias kampung 2' },
    { id: 11, src: '/images/gallery/galeri-6.jpg', alt: 'Tanaman kaktus dan dedaunan tropis 2' },
    { id: 12, src: '/images/gallery/galeri-5.jpg', alt: 'Ruang hijau dan papan edukasi kebun 2' },
];

const cardClasses = [
    'col-span-2 h-[520px]',
    'col-span-1 h-[520px]',
    'col-span-1 h-[520px]',
    'col-span-2 h-[520px]',
    'col-span-1 h-[520px]',
    'col-span-2 h-[520px]',
];

const pageSize = 6;
const currentPage = ref(1);

const totalPages = computed(() => Math.ceil(galleryItems.length / pageSize));

const pagedImages = computed(() => {
    const start = (currentPage.value - 1) * pageSize;
    const slice = galleryItems.slice(start, start + pageSize);

    return slice.map((item, index) => ({
        ...item,
        classes: cardClasses[index] ?? 'col-span-1 h-[520px]',
    }));
});

const goToPage = (page) => {
    if (page < 1 || page > totalPages.value) {
        return;
    }

    currentPage.value = page;
};

const goToPrevious = () => {
    goToPage(currentPage.value - 1);
};

const goToNext = () => {
    goToPage(currentPage.value + 1);
};
</script>

<template>
    <section id="galeri" class="bg-white px-4 pb-20 pt-20 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-7xl">
            <div class="mx-auto max-w-4xl text-center">
                <span class="inline-flex rounded-full bg-[#dcfce7] px-4 py-1 text-sm font-semibold text-[#008236]">Galeri</span>
                <h1 class="mt-3 text-6xl font-bold leading-12 text-[#101828]">Momen Berharga Kami</h1>
                <p class="mx-auto mt-4 max-w-2xl text-lg leading-7 text-[#4a5565]">
                    Tangkapan visual dari perjalanan dan pencapaian komunitas
                </p>
            </div>

            <div class="mt-16 grid grid-cols-3 gap-4">
                <article
                    v-for="image in pagedImages"
                    :key="image.id"
                    class="overflow-hidden rounded-2xl"
                    :class="image.classes"
                >
                    <img :src="image.src" :alt="image.alt" class="h-full w-full object-cover" loading="lazy" />
                </article>
            </div>

            <GalleryPagination
                :current-page="currentPage"
                :total-pages="totalPages"
                @prev="goToPrevious"
                @next="goToNext"
                @go-to="goToPage"
            />

            <div class="mt-6 text-center text-base text-[#4a5565]">Ingin melihat lebih banyak?</div>
            <div class="mt-4 flex justify-center">
                <a
                    href="https://www.instagram.com/"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="rounded-full bg-[#00a63e] px-8 py-3 text-base font-medium text-white shadow-[0_10px_15px_rgba(0,0,0,0.1),0_4px_6px_rgba(0,0,0,0.1)]"
                >
                    Kunjungi Instagram Kami
                </a>
            </div>
        </div>
    </section>
</template>
