<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-900 text-white px-4">
        <div class="text-center">
            <h1 class="text-6xl font-bold mb-4">
                404
            </h1>
            <p class="text-xl mb-4">
                Oops! The page you're looking for doesn't exist.
            </p>
            <el-button
                type="primary"
                class="mt-4"
                @click="goHome"
            >
                Go Home
            </el-button>
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
body {
    background-color: #1a202c; /* Tailwind's gray-900 */
    color: #f7fafc; /* Tailwind's white */
}
</style>
