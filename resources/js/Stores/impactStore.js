import { defineStore } from 'pinia';

export const useImpactStore = defineStore('impact', {
    state: () => ({
        stats: [
            {
                id: 'lubang',
                icon: '/images/impact/stat-lubang.svg',
                iconGradient: 'linear-gradient(135deg, rgb(0, 201, 80) 0%, rgb(0, 153, 102) 100%)',
                value: '5,000+',
                label: 'Lubang Tanam',
            },
            {
                id: 'ruang',
                icon: '/images/impact/stat-ruang.svg',
                iconGradient: 'linear-gradient(135deg, rgb(43, 127, 255) 0%, rgb(0, 146, 184) 100%)',
                value: '2,500 m²',
                label: 'Ruang Hijau',
            },
            {
                id: 'anggota',
                icon: '/images/impact/stat-anggota.svg',
                iconGradient: 'linear-gradient(135deg, rgb(173, 70, 255) 0%, rgb(230, 0, 118) 100%)',
                value: '500+',
                label: 'Anggota Aktif',
            },
            {
                id: 'juara',
                icon: '/images/impact/stat-juara.svg',
                iconGradient: 'linear-gradient(135deg, rgb(254, 154, 0) 0%, rgb(245, 73, 0) 100%)',
                value: '2025',
                label: 'Juara 1 lomba Clean, Green, and',
                description: 'Healthy (CGH) tingkat Kota Balikpapan',
            },
        ],
        environmentalImpact: [
            '🌿 Mengurangi emisi karbon lokal sebesar 30%',
            '♻️ Mendaur ulang 2 ton sampah organik per bulan',
            '💧 Menghemat 40% penggunaan air melalui hidroponik',
            '🦋 Meningkatkan biodiversitas lokal',
        ],
        socialImpact: [
            '👥 Mempererat hubungan antar warga',
            '🎓 Melatih 200+ peserta dalam workshop',
            '🥗 Menyediakan sayuran segar untuk 100+ keluarga',
            '💼 Menciptakan peluang usaha mikro lokal',
        ],
        challenges: [
            {
                id: 'partisipasi',
                icon: '/images/impact/challenge-partisipasi.svg',
                title: 'Partisipasi Masyarakat',
                challenge: 'Awalnya sulit mengajak warga untuk aktif terlibat dalam kegiatan hijau.',
                solution: 'Mengadakan acara komunitas rutin, workshop gratis, dan program reward untuk partisipan aktif.',
            },
            {
                id: 'keuangan',
                icon: '/images/impact/challenge-keuangan.svg',
                title: 'Masalah Keuangan',
                challenge: 'Terbatasnya dana untuk infrastruktur dan peralatan pertanian modern.',
                solution: 'Kerjasama dengan CSR perusahaan, crowdfunding komunitas, dan penjualan hasil panen organik.',
            },
            {
                id: 'pemuda',
                icon: '/images/impact/challenge-pemuda.svg',
                title: 'Keterlibatan Pemuda',
                challenge: 'Generasi muda kurang tertarik dengan kegiatan pertanian dan lingkungan.',
                solution: 'Program edukasi kreatif, media sosial aktif, dan event yang menarik untuk kalangan muda.',
            },
        ],
    }),
});
