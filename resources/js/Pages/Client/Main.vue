<template>
    <ClientLayout>
        <template #content>
            <div class="container mx-auto w-full p-6">
                <div class="mb-6 align-top">
                    <div class="relative w-full">
                        <input
                            type="text"
                            placeholder="Search for products..."
                            class="w-full h-12 px-4 pl-10 pr-4 rounded-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            @keyup="search"
                        >
                        <svg
                            class="w-6 h-6 text-gray-500 dark:text-gray-400 absolute left-3 top-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35M16.65 10.3a6.35 6.35 0 1 1-12.7 0 6.35 6.35 0 0 1 12.7 0z"
                            />
                        </svg>
                    </div>
                </div>
                <div
                    v-if="isLoading"
                    class="flex justify-center items-center h-96"
                >
                    <span class="text-gray-500">Loading...</span>
                </div>
                <div
                    v-else-if="products.length === 0"
                    class="flex justify-center items-center h-96"
                >
                    <h2 class="text-4xl font-bold text-gray-500">
                        Nothing found
                    </h2>
                </div>
                <div
                    v-else
                    class="grid gap-5 grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6"
                >
                    <div
                        v-for="(product, index) in products"
                        :key="product.id"
                        class="relative w-full cursor-pointer group"
                        :class="getClass(index)"
                        @click="follow(product)"
                    >
                        <div
                            class="w-full h-full bg-cover bg-center bg-no-repeat rounded-lg transition-transform transform group-hover:scale-105 group-hover:brightness-50 duration-300"
                            :style="{
                                'background-image': `url(${product.image?.url})`,
                            }"
                        />
                        <div
                            class="absolute inset-0 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                        >
                            <h2 class="text-xl font-semibold text-white drop-shadow-lg mb-2">
                                {{ product.name }}
                            </h2>
                            <p class="text-lg text-white px-2 py-1">
                                {{ product.price?.amount }} {{ product.price?.currency }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </ClientLayout>
</template>

<script setup>
import axios from 'axios';
import { debounce } from 'lodash';
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { ref, onBeforeMount } from 'vue';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';

const products = ref([]);
onBeforeMount(async () => {
    await axios.get(route('api.products.search'))
        .then((res) => {
            products.value = res.data.data;
        }).catch(() => {

        });
});

const isLoading = ref(false);
const q = ref();
const search = debounce(async (v) => {
    v = v.target.value;
    isLoading.value = true;
    const url = route('api.products.search', { q: v });

    await axios.get(url)
        .then((response) => {
            products.value = response.data.data;
        }).catch((e) => {
            console.log(e);
        }).finally(() => {
            isLoading.value = false;
        });
}, 500);

const follow = (product) => {
    router.visit(route('client.products.detail', { product: product.uuid }));
};

const getClass = (index) => {
    return index % 5 === 0 || index % 5 === 1
        ? 'lg:col-span-3 md:col-span-3 col-span-2 h-80'
        : 'lg:col-span-2 md:col-span-1 col-span-2 h-80';
};
</script>

<style scoped>
</style>
