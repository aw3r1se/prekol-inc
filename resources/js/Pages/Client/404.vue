<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-900 text-gray-100 px-4 py-8 relative overflow-hidden">
        <!-- Background Pattern -->
        <div
            class="absolute inset-0 bg-fixed bg-center bg-cover opacity-10"
            style="background-image: url('https://www.transparenttextures.com/patterns/asfalt-dark.png');"
        />

        <div class="relative z-10 text-center">
            <h1 class="text-9xl font-extrabold mb-4 text-gray-300 animate-pulse">
                404
            </h1>
            <p class="text-2xl mb-8 text-gray-400">
                Oops! The page you're looking for doesn't exist.
            </p>
            <div
                class="
                  inline-block
                  bg-blue-900
                  text-white
                  px-6
                  py-3
                  rounded-lg
                  shadow-md
                  hover:bg-gray-600
                  transition
                  duration-300
                  ease-in-out
                  transform
                  hover:scale-105
                  cursor-pointer
                "
                @click="goHome"
            >
                Go Home
            </div>
        </div>
    </div>
</template>

<script setup>
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';

const goHome = () => {
    router.visit(route('client.main'));
};
</script>

<style scoped>
/* Additional animations */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.7;
    }
    50% {
        transform: scale(1.1);
        opacity: 1;
    }
}

.animate-pulse {
    animation: pulse 2s infinite;
}
</style>
