<script setup>
defineProps({
    programs: {
        type: Array,
        default: () => [],
    }
});

const defaultGradients = [
    'linear-gradient(135deg, rgb(43, 127, 255) 0%, rgb(0, 184, 219) 100%)',
    'linear-gradient(135deg, rgb(254, 154, 0) 0%, rgb(255, 105, 0) 100%)',
    'linear-gradient(135deg, rgb(173, 70, 255) 0%, rgb(246, 51, 154) 100%)',
    'linear-gradient(135deg, rgb(0, 201, 80) 0%, rgb(0, 188, 125) 100%)',
];
</script>

<template>
    <section id="program-detail" class="bg-gradient-to-b from-slate-50 to-white py-24">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-20 max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Program Kampung Bungas</p>
                <h2 class="mt-3 text-4xl font-bold text-slate-900 sm:text-5xl">Inisiatif Hijau Kami</h2>
                <p class="mt-4 text-lg leading-relaxed text-slate-600">
                    Kampung Bungas menjalankan berbagai program yang dirancang untuk memberdayakan komunitas lokal dan menciptakan lingkungan hijau yang berkelanjutan.
                </p>
            </div>

            <!-- Programs Grid -->
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-2">
                <article
                    v-for="(program, index) in programs"
                    :key="program.id"
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-md transition hover:-translate-y-2 hover:shadow-xl"
                >
                    <!-- Program Image -->
                    <div class="relative h-48 overflow-hidden bg-slate-200">
                        <img
                            v-if="program.image_url"
                            :src="program.image_url"
                            :alt="program.title"
                            class="h-full w-full object-cover"
                        />
                        <div v-else class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-500 font-medium">Belum ada gambar</div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent" />
                    </div>

                    <!-- Program Content -->
                    <div class="p-6">
                        <div class="mb-3 flex items-center gap-3">
                            <div 
                                class="flex h-12 w-12 items-center justify-center rounded-full shadow-sm text-xl text-white"
                                :style="{ backgroundImage: defaultGradients[index % defaultGradients.length] }"
                            >
                                <img
                                    v-if="program.icon && (program.icon.includes('/') || program.icon.includes('.'))"
                                    :src="program.icon"
                                    :alt="`${program.title} icon`"
                                    class="h-6 w-6 object-contain"
                                />
                                <span v-else>{{ program.icon || '🌱' }}</span>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">{{ program.title }}</h3>
                        <p class="mt-2 text-slate-600">{{ program.description }}</p>

                        <a
                            href="#"
                            class="mt-4 inline-block text-sm font-semibold text-emerald-600 transition hover:text-emerald-700"
                        >
                            Pelajari Lebih Lanjut →
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
