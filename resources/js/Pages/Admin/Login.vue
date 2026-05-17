<script setup>
import { useForm, Head } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    // form.post(route('login'));
    console.log('Login attempt', form.data());
};
</script>

<template>
    <Head title="Login Admin" />
    
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 font-sans">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <div class="mx-auto h-16 w-16 bg-primary rounded-2xl flex items-center justify-center text-white text-2xl font-bold">
                    KB
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Admin Dashboard
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Masuk untuk mengelola konten Kampung Bungas
                </p>
            </div>
            
            <form class="mt-8 space-y-6" @submit.prevent="submit">
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input 
                            v-model="form.email"
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-primary focus:border-primary sm:text-sm transition-all"
                            placeholder="admin@kampungbungas.id"
                        />
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input 
                            v-model="form.password"
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-primary focus:border-primary sm:text-sm transition-all"
                            placeholder="••••••••"
                        />
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            v-model="form.remember"
                            id="remember-me" 
                            name="remember-me" 
                            type="checkbox" 
                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                        />
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <div>
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all disabled:opacity-50"
                    >
                        Masuk Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
