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
