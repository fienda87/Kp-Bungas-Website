import { defineStore } from 'pinia';

export const useProgramStore = defineStore('program', {
    state: () => ({
        features: [
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
        ],
        programs: [
            {
                id: 'hidroponik',
                title: 'Pertanian Hidroponik',
                description: 'Sistem pertanian modern tanpa tanah yang efisien dan produktif untuk menghasilkan sayuran segar berkualitas tinggi.',
                image: '/images/program/hidroponik.jpg',
                icon: '/images/program/icon-hidroponik.svg',
                iconGradient: 'linear-gradient(135deg, rgb(43, 127, 255) 0%, rgb(0, 184, 219) 100%)',
            },
            {
                id: 'sampah',
                title: 'Pengelolaan Sampah',
                description: 'Program daur ulang dan kompos untuk mengurangi limbah dan mengubahnya menjadi sumber daya yang bermanfaat.',
                image: '/images/program/sampah.jpg',
                icon: '/images/program/icon-sampah.svg',
                iconGradient: 'linear-gradient(135deg, rgb(254, 154, 0) 0%, rgb(255, 105, 0) 100%)',
            },
            {
                id: 'pendidikan',
                title: 'Kegiatan Pendidikan',
                description: 'Workshop dan pelatihan untuk anak-anak dan dewasa tentang pertanian berkelanjutan dan kesadaran lingkungan.',
                image: '/images/program/pendidikan.jpg',
                icon: '/images/program/icon-pendidikan.svg',
                iconGradient: 'linear-gradient(135deg, rgb(173, 70, 255) 0%, rgb(246, 51, 154) 100%)',
            },
            {
                id: 'berkebun',
                title: 'Berkebun Komunitas',
                description: 'Kegiatan berkebun bersama yang mempererat hubungan antar warga dan menciptakan ruang hijau bersama.',
                image: '/images/program/berkebun.jpg',
                icon: '/images/program/icon-berkebun.svg',
                iconGradient: 'linear-gradient(135deg, rgb(0, 201, 80) 0%, rgb(0, 188, 125) 100%)',
            },
        ],
    }),
});
