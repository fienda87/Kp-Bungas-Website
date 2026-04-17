<script setup>
import ContactCard from './ContactCard.vue';

const contactItems = [
    {
        id: 'lokasi',
        icon: '/images/contact/icon-lokasi.svg',
        iconBgColor: '#00c950',
        title: 'Lokasi',
        lines: [
            'Kampung Bungas, Jl. Ahmad Yani',
            'Kelurahan Gunungsari Ilir, Kecamatan Balikpapan Tengah',
            'Balikpapan, Kalimantan Timur',
        ],
        borderColor: '#dcfce7',
        bgGradient: 'linear-gradient(166.93426981689493deg, rgb(240, 253, 244) 0%, rgb(236, 253, 245) 100%)',
    },
    {
        id: 'email',
        icon: '/images/contact/icon-email.svg',
        iconBgColor: '#2b7fff',
        title: 'Email',
        lines: ['kampungbungasbalikpapan@gmail.com'],
        borderColor: '#dbeafe',
        bgGradient: 'linear-gradient(168.87749438250933deg, rgb(239, 246, 255) 0%, rgb(236, 254, 255) 100%)',
    },
    {
        id: 'telepon',
        icon: '/images/contact/icon-telepon.svg',
        iconBgColor: '#ad46ff',
        title: 'Telepon',
        lines: ['+62 812 5422 777', 'Suwanto ,S.T.'],
        borderColor: '#f3e8ff',
        bgGradient: 'linear-gradient(168.87749438250933deg, rgb(250, 245, 255) 0%, rgb(253, 242, 248) 100%)',
    },
    {
        id: 'jam',
        icon: '/images/contact/icon-jam.svg',
        iconBgColor: '#fe9a00',
        title: 'Jam Kunjungan',
        lines: ['Senin - Jumat: 08:00 - 16:00', 'Sabtu: 08:00 - 12:00', '* Harap konfirmasi sebelum berkunjung'],
        borderColor: '#fef3c6',
        bgGradient: 'linear-gradient(166.61322743486988deg, rgb(255, 251, 235) 0%, rgb(255, 247, 237) 100%)',
    },
];

</script>

<template>
    <section id="kunjungi" class="bg-white px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-7xl">
            <!-- Header -->
            <div class="mx-auto max-w-4xl text-center">
                <span class="inline-flex rounded-full bg-[#dcfce7] px-4 py-1 text-sm font-semibold text-[#008236]">Hubungi Kami</span>
                <h1 class="mt-3 text-6xl font-bold leading-12 text-[#101828]">Mari Berkunjung</h1>
                <p class="mx-auto mt-4 max-w-2xl text-lg leading-7 text-[#4a5565]">
                    Kami terbuka untuk kolaborasi, kunjungan, dan pertanyaan
                </p>
            </div>

            <!-- Content Grid -->
            <div class="mt-16 grid gap-8 md:grid-cols-2 md:gap-12">
                <!-- Left Column: Contact Cards -->
                <div class="flex flex-col gap-6">
                    <ContactCard
                        v-for="item in contactItems"
                        :key="item.id"
                        :icon="item.icon"
                        :icon-bg-color="item.iconBgColor"
                        :title="item.title"
                        :lines="item.lines"
                        :border-color="item.borderColor"
                        :bg-gradient="item.bgGradient"
                    />
                </div>

                <!-- Right Column: Google Maps -->
                <div class="flex items-start">
                    <div class="relative w-full overflow-hidden rounded-[16px] bg-[#e5e7eb] pt-[100%] h-80">
                        <iframe
                            class="absolute inset-0 h-full w-full border-0"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.7862836839497!2d117.17370732343072!3d-1.1963639987066637!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de43fabe0e00001%3A0x1234567890abc!2sJl.%20Ahmad%20Yani%2C%20Gunungsari%2C%20Balikpapan%2C%20East%20Kalimantan!5e0!3m2!1sen!2sid!4v1713350000000"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi Kampung Bungas"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
