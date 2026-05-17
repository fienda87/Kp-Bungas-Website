import { defineStore } from 'pinia';

export const useGalleryStore = defineStore('gallery', {
    state: () => ({
        galleryItems: [
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
        ],
    }),
});
