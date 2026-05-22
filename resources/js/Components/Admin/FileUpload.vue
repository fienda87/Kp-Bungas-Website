<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    modelValue: [File, String],
    label: String,
    error: String,
    previewUrl: String,
    accept: {
        type: String,
        default: 'image/*'
    }
});

const emit = defineEmits(['update:modelValue']);

const fileInput = ref(null);
const localPreview = ref(null);

const isImage = computed(() => {
    const file = props.modelValue;
    const url = props.previewUrl;
    
    if (file && file instanceof File) {
        return file.type.startsWith('image/');
    }
    
    if (url) {
        return url.match(/\.(jpeg|jpg|gif|png|webp)$/i);
    }
    
    return props.accept.includes('image');
});

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        emit('update:modelValue', file);
        localPreview.value = URL.createObjectURL(file);
    }
};

const removeFile = () => {
    emit('update:modelValue', null);
    localPreview.value = null;
    if (fileInput.value) fileInput.value.value = '';
};
</script>

<template>
    <div class="space-y-1">
        <label v-if="label" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>
        
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl transition-all hover:border-primary">
            <div class="space-y-1 text-center" v-if="!modelValue && !previewUrl">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600">
                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none">
                        <span>Upload a file</span>
                        <input id="file-upload" name="file-upload" type="file" class="sr-only" :accept="accept" @change="onFileChange" ref="fileInput">
                    </label>
                    <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500">
                    PNG, JPG, GIF up to 10MB
                </p>
            </div>
            
            <div v-else class="relative w-full">
                <template v-if="isImage">
                    <img 
                        :src="localPreview || previewUrl" 
                        class="max-h-64 mx-auto rounded-lg object-contain"
                    />
                </template>
                <div v-else class="flex flex-col items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-600 font-medium truncate max-w-xs">
                        {{ modelValue?.name || previewUrl?.split('/').pop() }}
                    </p>
                </div>
                <button 
                    @click="removeFile"
                    type="button" 
                    class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 shadow-md hover:bg-red-700 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        
        <p v-if="error" class="text-sm text-red-600 mt-1">{{ error }}</p>
    </div>
</template>
