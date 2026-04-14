<template>
    <section id="tentang" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Tentang Kami</h2>
                <div class="w-16 h-1 bg-green-600 mx-auto rounded"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-2xl font-semibold text-green-700 mb-4">Siapa KP Bungas?</h3>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        KP Bungas adalah kelompok pemuda yang bergerak di bidang sosial, budaya, dan pemberdayaan masyarakat.
                        Kami berkomitmen untuk menjadi agen perubahan positif di lingkungan sekitar.
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Dengan semangat kebersamaan dan gotong royong, kami terus berupaya menciptakan program-program
                        yang bermanfaat bagi seluruh lapisan masyarakat.
                    </p>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div v-for="stat in stats" :key="stat.label" class="bg-green-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-green-700">{{ stat.value }}</div>
                            <div class="text-sm text-gray-600 mt-1">{{ stat.label }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 rounded-2xl p-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Visi & Misi</h3>
                    <div class="mb-6">
                        <h4 class="font-semibold text-green-700 mb-2">🌟 Visi</h4>
                        <p class="text-gray-600">Menjadi kelompok pemuda yang unggul, inovatif, dan berdaya dalam membangun masyarakat yang sejahtera.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-green-700 mb-2">🎯 Misi</h4>
                        <ul class="text-gray-600 space-y-2">
                            <li v-for="misi in misiList" :key="misi" class="flex items-start gap-2">
                                <span class="text-green-500 mt-1">✓</span>
                                <span>{{ misi }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const stats = [
    { value: '50+', label: 'Anggota' },
    { value: '20+', label: 'Program' },
    { value: '5+', label: 'Tahun' },
];

const misiList = [
    'Meningkatkan kualitas sumber daya pemuda',
    'Mengembangkan kreativitas dan inovasi',
    'Mempererat tali silaturahmi antar anggota',
    'Berkontribusi nyata bagi masyarakat',
];
</script>
