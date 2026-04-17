<script setup>
defineProps({
    icon: { type: String, required: true },
    iconBgColor: { type: String, required: true },
    title: { type: String, required: true },
    lines: { type: Array, default: () => [] },
    borderColor: { type: String, required: true },
    bgGradient: { type: String, required: true },
});
</script>

<template>
    <article
        class="content-stretch flex flex-col rounded-[16px] border px-[25px] py-[25px] relative shrink-0"
        :style="{ borderColor: borderColor, backgroundImage: bgGradient }"
    >
        <div class="content-stretch flex gap-[16px] items-start relative shrink-0 w-full">
            <!-- Icon Badge -->
            <div
                class="flex h-[48px] w-[48px] items-center justify-center rounded-full shrink-0"
                :style="{ backgroundColor: iconBgColor }"
                aria-hidden="true"
            >
                <img :src="icon" alt="" class="h-[24px] w-[24px]" />
            </div>

            <!-- Content -->
            <div class="h-auto relative shrink-0 w-full">
                <div class="bg-clip-padding border-0 border-transparent border-solid relative">
                    <!-- Title -->
                    <div class="h-[27px] relative shrink-0 w-full">
                        <p class="font-['Inter:Bold',sans-serif] font-bold leading-[27px] left-0 not-italic text-[#101828] text-[18px] top-[-1px]">
                            {{ title }}
                        </p>
                    </div>

                    <!-- Lines -->
                    <div v-if="lines.length > 0" class="mt-[8px] space-y-[0px]">
                        <p
                            v-for="(line, idx) in lines"
                            :key="idx"
                            class="font-['Inter:Regular',sans-serif] font-normal leading-[24px] not-italic text-[#4a5565] text-[16px]"
                        >
                            {{ line }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </article>
</template>
