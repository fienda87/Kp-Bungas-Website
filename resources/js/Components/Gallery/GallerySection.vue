<script setup>
import { computed, ref } from 'vue';
import GalleryPagination from './GalleryPagination.vue';

const props = defineProps({
    galleries: {
        type: Array,
        default: () => [],
    }
});

const allPhotos = computed(() => {
    let photos = [];
    props.galleries.forEach(gallery => {
        if (gallery.photos && gallery.photos.length > 0) {
            gallery.photos.forEach(photo => {
                photos.push({
                    id: photo.id,
                    src: photo.image_url,
                    alt: photo.caption || gallery.album_name || 'Foto Galeri'
                });
            });
        } else if (gallery.cover_image) {
             photos.push({
                 id: 'cover-' + gallery.id,
                 src: gallery.cover_image_url,
                 alt: gallery.album_name || 'Cover Galeri'
             });
        }
    });
    
    // Fallback if no photos at all
    if (photos.length === 0) {
        return [
            { id: 'dummy-1', src: '/images/gallery/galeri-1.jpg', alt: 'Belum ada foto' }
        ];
    }
    
    return photos;
});

const selectedImage = ref(null);

const openImage = (image) => {
    selectedImage.value = image;
    document.body.style.overflow = 'hidden';
};

const closeImage = () => {
    selectedImage.value = null;
    document.body.style.overflow = '';
};

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

const totalPages = computed(() => Math.ceil(allPhotos.value.length / pageSize) || 1);

const pagedImages = computed(() => {
    const start = (currentPage.value - 1) * pageSize;
    const slice = allPhotos.value.slice(start, start + pageSize);

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
                    class="group relative overflow-hidden rounded-2xl cursor-pointer"
                    :class="image.classes"
                    @click="openImage(image)"
                >
                    <img :src="image.src" :alt="image.alt" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" />
                    
                    <div class="absolute inset-0 bg-black/0 transition-colors duration-300 group-hover:bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100">
                        <svg class="w-12 h-12 text-white drop-shadow-md transform scale-50 transition-transform duration-300 group-hover:scale-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                    </div>
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

        <Teleport to="body">
            <Transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="selectedImage" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4 sm:p-8 backdrop-blur-sm" @click="closeImage">
                    <button @click="closeImage" class="absolute top-4 right-4 z-10 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    
                    <img 
                        :src="selectedImage.src" 
                        :alt="selectedImage.alt" 
                        class="max-h-full max-w-full object-contain rounded-lg shadow-[0_0_50px_rgba(0,0,0,0.5)] transform scale-100 transition-transform" 
                        @click.stop
                    />
                    
                    <div v-if="selectedImage.alt" class="absolute bottom-8 left-0 right-0 text-center pointer-events-none px-4">
                        <span class="bg-black/60 text-white px-6 py-2 rounded-full backdrop-blur-md inline-block max-w-3xl truncate text-sm font-medium shadow-lg">
                            {{ selectedImage.alt }}
                        </span>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </section>
</template>
