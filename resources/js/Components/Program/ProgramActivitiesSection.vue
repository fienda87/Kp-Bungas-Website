<script setup>
import ProgramCard from './ProgramCard.vue';

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
    <section id="program" class="bg-white px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-7xl">
            <!-- Header Section -->
            <div class="mx-auto max-w-4xl text-center">
                <span class="inline-flex rounded-full bg-[#dcfce7] px-4 py-1 text-sm font-semibold text-[#008236]">
                    Program &amp; Kegiatan
                </span>
                <h1 class="mt-3 text-4xl font-bold leading-12 text-[#101828] sm:text-5xl sm:leading-12">
                    Apa yang Kami Lakukan
                </h1>
                <p class="mx-auto mt-4 max-w-2xl text-lg leading-7 text-[#4a5565] sm:text-lg sm:leading-8">
                    Berbagai program inovatif untuk menciptakan komunitas yang berkelanjutan dan ramah lingkungan
                </p>
            </div>

            <!-- Programs Grid -->
            <div class="mt-20 grid gap-8 md:grid-cols-2 md:gap-10 lg:mt-24 lg:gap-12">
                <ProgramCard
                    v-for="(program, index) in programs"
                    :key="program.id"
                    :title="program.title"
                    :description="program.description"
                    :image="program.image_url"
                    :icon="program.icon"
                    :icon-gradient="defaultGradients[index % defaultGradients.length]"
                />
            </div>
        </div>
    </section>
</template>
