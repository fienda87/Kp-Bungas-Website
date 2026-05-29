<template>
    <div class="flex flex-col gap-3 rounded-lg border border-gray-100 bg-white p-4 shadow-sm md:flex-row md:items-center md:justify-between">
        <div class="flex flex-1 flex-col gap-3 md:flex-row md:items-center">
            <slot />
        </div>
        <div v-if="$slots.actions" class="flex items-center gap-2">
            <slot name="actions" />
        </div>
    </div>
</template>
