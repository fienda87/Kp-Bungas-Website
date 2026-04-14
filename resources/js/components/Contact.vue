<template>
    <section id="kontak" class="py-20 bg-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold mb-4">Hubungi Kami</h2>
                <div class="w-16 h-1 bg-yellow-300 mx-auto rounded mb-4"></div>
                <p class="text-green-100 max-w-xl mx-auto">Punya pertanyaan atau ingin bergabung? Jangan ragu untuk menghubungi kami.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-xl font-semibold mb-6">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div v-for="info in contactInfo" :key="info.label" class="flex items-start gap-4">
                            <div class="bg-green-600 rounded-full p-3 flex-shrink-0">
                                <span class="text-xl">{{ info.icon }}</span>
                            </div>
                            <div>
                                <div class="font-medium text-green-200 text-sm">{{ info.label }}</div>
                                <div class="text-white mt-1">{{ info.value }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-6">Kirim Pesan</h3>
                    <form @submit.prevent="submitForm" class="space-y-4">
                        <div>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Nama Lengkap"
                                class="w-full bg-green-600 border border-green-500 text-white placeholder-green-300 rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-300"
                                required
                            />
                        </div>
                        <div>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="Alamat Email"
                                class="w-full bg-green-600 border border-green-500 text-white placeholder-green-300 rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-300"
                                required
                            />
                        </div>
                        <div>
                            <textarea
                                v-model="form.message"
                                rows="4"
                                placeholder="Pesan Anda..."
                                class="w-full bg-green-600 border border-green-500 text-white placeholder-green-300 rounded-lg px-4 py-3 focus:outline-none focus:border-yellow-300 resize-none"
                                required
                            ></textarea>
                        </div>
                        <button
                            type="submit"
                            class="w-full bg-yellow-400 text-green-900 font-semibold py-3 rounded-lg hover:bg-yellow-300 transition-colors duration-200"
                        >
                            {{ submitting ? 'Mengirim...' : 'Kirim Pesan' }}
                        </button>
                        <p v-if="submitted" class="text-yellow-300 text-center text-sm">✓ Pesan berhasil terkirim! Kami akan segera menghubungi Anda.</p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, reactive } from 'vue';

const submitting = ref(false);
const submitted = ref(false);

const form = reactive({
    name: '',
    email: '',
    message: '',
});

const contactInfo = [
    { icon: '📍', label: 'Alamat', value: 'Desa Bungas, Kecamatan Bungas, Indonesia' },
    { icon: '📞', label: 'Telepon', value: '+62 812-3456-7890' },
    { icon: '📧', label: 'Email', value: 'info@kpbungas.id' },
    { icon: '🕒', label: 'Jam Aktif', value: 'Senin – Sabtu, 08:00 – 17:00 WIB' },
];

async function submitForm() {
    submitting.value = true;
    // Simulate form submission
    await new Promise(resolve => setTimeout(resolve, 1000));
    submitting.value = false;
    submitted.value = true;
    form.name = '';
    form.email = '';
    form.message = '';
    setTimeout(() => { submitted.value = false; }, 5000);
}
</script>
