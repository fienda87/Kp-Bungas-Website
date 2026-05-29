<script setup>
import { ref, computed } from 'vue';
import { describeAcceptedTypes, validateSelectedFile } from '@/utils/file';

const fileInputId = `file-upload-${Math.random().toString(36).slice(2, 10)}`;

const props = defineProps({
    modelValue: [File, String],
    label: String,
    error: String,
    previewUrl: String,
    preview: {
        type: [Boolean, String],
        default: false,
    },
    accept: {
        type: String,
        default: 'image/*'
    },
    maxSizeMb: {
        type: Number,
        default: 10,
    },
    helperText: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue', 'validation-error']);

const fileInput = ref(null);
const localPreview = ref(null);
const localError = ref('');

const resolvedPreviewUrl = computed(() => {
    if (typeof props.preview === 'string' && props.preview) {
        return props.preview;
    }

    return props.previewUrl || '';
});

const isImage = computed(() => {
    const file = props.modelValue;
    const url = resolvedPreviewUrl.value;
    
    if (file && file instanceof File) {
        return file.type.startsWith('image/');
    }
    
    if (url) {
        return url.match(/\.(jpeg|jpg|gif|png|webp)$/i);
    }
    
    return props.accept.includes('image');
});

const fileHelpText = computed(() => {
    if (props.helperText) {
        return props.helperText;
    }

    return `${describeAcceptedTypes(props.accept)} hingga ${props.maxSizeMb}MB`;
});

const onFileChange = (e) => {
    const file = e.target.files[0];

    if (!file) {
        return;
    }

    const validationError = validateSelectedFile(file, props.accept, props.maxSizeMb);

    if (validationError) {
        localError.value = validationError;
        localPreview.value = null;
        emit('update:modelValue', null);
        emit('validation-error', validationError);

        if (fileInput.value) {
            fileInput.value.value = '';
        }

        return;
    }

    localError.value = '';
    emit('validation-error', '');
    emit('update:modelValue', file);

    if (file.type.startsWith('image/')) {
        localPreview.value = URL.createObjectURL(file);
    } else {
        localPreview.value = null;
    }
};

const removeFile = () => {
    emit('update:modelValue', null);
    emit('validation-error', '');
    localPreview.value = null;
    localError.value = '';
    if (fileInput.value) fileInput.value.value = '';
};
</script>

<template>
    <div class="space-y-1">
        <label v-if="label" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>
        
        <label :for="fileInputId" class="mt-1 flex cursor-pointer justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 py-6 transition-all hover:border-primary hover:bg-gray-50">
            <div class="space-y-1 text-center" v-if="!modelValue && !resolvedPreviewUrl">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex flex-col items-center text-sm text-gray-600">
                    <span class="inline-flex rounded-md bg-primary px-3 py-2 font-semibold text-white shadow-sm">
                        Upload a file
                    </span>
                    <p class="mt-2">atau drag and drop</p>
                </div>
                <p class="text-xs text-gray-500">{{ fileHelpText }}</p>
            </div>
            
            <div v-else class="relative w-full">
                <template v-if="isImage">
                    <img 
                        :src="localPreview || resolvedPreviewUrl" 
                        class="max-h-64 mx-auto rounded-lg object-contain"
                    />
                </template>
                <div v-else class="flex flex-col items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-600 font-medium truncate max-w-xs">
                        {{ modelValue?.name || resolvedPreviewUrl?.split('/').pop() }}
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
            <input :id="fileInputId" name="file-upload" type="file" class="sr-only" :accept="accept" @change="onFileChange" ref="fileInput">
        </label>
        
        <p v-if="localError" class="text-sm text-red-600 mt-1">{{ localError }}</p>
        <p v-if="error" class="text-sm text-red-600 mt-1">{{ error }}</p>
    </div>
</template>
