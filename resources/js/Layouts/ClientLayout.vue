<template>
    <div class="min-h-screen flex flex-col">
        <div class="flex-grow grid grid-cols-1 xl:grid-cols-12">
            <Header class="xl:col-span-2" />
            <main class="col-span-1 xl:col-span-10 m-3 p-3 dark:bg-black-800">
                <slot name="content" />
            </main>
        </div>
        <Footer class="xl:col-span-12" />
    </div>
</template>

<script setup>
import Header from '@/Components/Client/Header.vue';
import Footer from '@/Components/Client/Footer.vue';
</script>
