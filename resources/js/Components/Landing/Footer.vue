<script setup>
const logoUrl = '/images/program/logo.png';

const socialItems = ['IG', 'TT', 'YT', 'FB'];
</script>

<template>
    <footer id="footer" class="bg-linear-to-b from-slate-900 to-black text-white">
        <div class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-2 lg:items-start">
                <div>
                    <div class="flex items-center gap-3">
                        <img :src="logoUrl" alt="Kampung Bungas" class="h-12 w-12 object-contain" />
                        <div>
                            <p class="text-2xl font-bold">Kampung Bungas</p>
                            <p class="text-sm text-emerald-400">Buah • Bunga • Sayur</p>
                        </div>
                    </div>
                    <p class="mt-4 max-w-lg text-base leading-relaxed text-slate-300">
                        Komunitas hijau berbasis masyarakat yang mengubah lingkungan perkotaan menjadi ruang hijau
                        produktif dan berkelanjutan.
                    </p>
                </div>

                <div class="lg:justify-self-end">
                    <h3 class="text-2xl font-bold">Ikuti Kami</h3>
                    <div class="mt-4 flex items-center gap-3">
                        <a
                            v-for="item in socialItems"
                            :key="item"
                            href="#"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-800 text-xs font-bold text-slate-100 transition hover:bg-emerald-600"
                        >
                            {{ item }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-10 rounded-2xl border border-emerald-500/30 bg-linear-to-r from-emerald-600/20 to-emerald-500/10 px-4 py-5 text-center">
                <p class="text-base italic text-slate-200 sm:text-xl">
                    "Merawat Pertiwi dari Halaman Rumah Sendiri - Bersama Membangun Masa Depan yang Berkelanjutan"
                </p>
            </div>

            <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-slate-800 pt-6 text-sm text-slate-400">
                <p>© 2026 Kampung Bungas. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-slate-200">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-slate-200">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>
</template>
