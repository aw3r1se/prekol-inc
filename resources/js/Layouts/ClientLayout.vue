<template>
    <div class="min-h-screen flex flex-col">
        <div class="flex-grow flex flex-col xl:flex-row">
            <Header class="xl:w-1/6" />
            <main class="flex-grow m-3 p-3 dark:bg-black-800 xl:w-5/6">
                <slot name="content" />
            </main>
        </div>
        <Footer class="w-full" />
    </div>
</template>

<script setup>
import Header from '@/Components/Client/Header.vue';
import Footer from '@/Components/Client/Footer.vue';
</script>

<script>
import { ref, defineExpose } from 'vue';

const isLoading = ref(false);
defineExpose({
    isLoading,
});
</script>

<style>
::-webkit-scrollbar {
    width: 12px;
    height: 12px;
}

::-webkit-scrollbar-track {
    background: #2d2d2d;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
    border: 3px solid #2d2d2d;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

* {
    scrollbar-width: thin;
    scrollbar-color: #888 #2d2d2d;
}

body {
    overflow-y: scroll;
}
</style>
