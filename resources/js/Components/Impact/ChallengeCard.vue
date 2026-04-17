<script setup>
defineProps({
    icon: { type: String, required: true },
    title: { type: String, required: true },
    challenge: { type: String, required: true },
    solution: { type: String, required: true },
});
</script>

<template>
    <article class="rounded-2xl bg-white p-6 shadow-[0_10px_15px_rgba(0,0,0,0.1),0_4px_6px_rgba(0,0,0,0.1)]">
        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-linear-to-br from-[#fb2c36] to-[#ff6900]">
            <img :src="icon" alt="" class="h-7 w-7" aria-hidden="true" />
        </div>

        <h3 class="mt-4 text-2xl font-bold leading-7 text-[#101828]">{{ title }}</h3>
        <p class="mt-2 text-base leading-6 text-[#4a5565]">{{ challenge }}</p>

        <div class="my-4 flex items-center gap-2">
            <div class="h-px flex-1 bg-linear-to-r from-[#e5e7eb] to-[#b9f8cf]" />
            <img src="/images/impact/challenge-arrow.svg" alt="" class="h-5 w-5" aria-hidden="true" />
            <div class="h-px flex-1 bg-linear-to-r from-[#b9f8cf] to-[#e5e7eb]" />
        </div>

        <div class="rounded-xl border-l-4 border-[#00c950] bg-[#f0fdf4] px-5 py-4">
            <p class="text-sm font-semibold text-[#008236]">Solusi:</p>
            <p class="mt-1 text-sm leading-5 text-[#364153]">{{ solution }}</p>
        </div>
    </article>
</template>
