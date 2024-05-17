<template>
    <div
        class="
            grid
            h-screen
            xl:grid-cols-12
            grid-cols-1
            body
        "
    >
        <Header class="col-span-2" />
        <main
            class="
                mb-auto
                col-span-10
                m-3
                p-3
                z-50
                dark:bg-black-800
            "
        >
            <slot name="content" />
        </main>
        <Footer class="xl:col-span-12" />
    </div>
</template>

<script setup>

import Header from '@/Components/Client/Header.vue';
import Footer from '@/Components/Client/Footer.vue';

</script>

<style scoped>
    @media (min-width: 1280px) {
        .body {
            grid-template-rows: 1fr auto;
        }
    }

    @media (max-width: 640px) {
        .body {
            grid-template-rows: 1fr 5fr;
        }
    }
</style>
