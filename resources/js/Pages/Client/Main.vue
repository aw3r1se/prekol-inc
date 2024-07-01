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
                    v-if="!products.length"
                    class="grid gap-5 justify-center items-center h-96"
                >
                    <h2 class="text-4xl font-bold text-gray-500">
                        Nothing found
                    </h2>
                </div>
                <div
                    v-else
                    v-loading="isLoading"
                    class="grid gap-5 grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6"
                >
                    <lazy-component
                        v-for="(product, index) in products"
                        :key="product.id"
                        class="relative w-full cursor-pointer group"
                        :class="getClass(index)"
                        @click="follow(product)"
                        @show="lazyHandler"
                    >
                        <div
                            class="w-full h-full bg-cover bg-center bg-no-repeat rounded-lg transition-transform transform group-hover:scale-105 group-hover:brightness-50 duration-300"
                            :style="{
                                'background-image': `url(${product.image?.url})`,
                            }"
                        />
                        <div class="absolute inset-0 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h2 class="text-xl font-semibold text-white drop-shadow-lg mb-2">
                                {{ product.name }}
                            </h2>
                            <p class="text-lg text-white px-2 py-1">
                                {{ product.price?.amount }} {{ product.price?.currency }}
                            </p>
                        </div>
                    </lazy-component>
                </div>
            </div>
        </template>
    </ClientLayout>
</template>

<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { ref, onBeforeMount } from 'vue';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';
import { config } from '/resources/js/utils/config.js';
import { getProducts, searchProducts } from '@/utils/service.js';
import { debounce } from 'lodash';
//import { lazyLoad } from '@/utils/lazy.js';

const q = ref({
    s: '',
    page: 1,
    per_page: config('fetchLimit'),
});

const products = ref([]);
const isLoading = ref(false);

onBeforeMount(async () => {
    products.value = await getProducts(q, isLoading);
});

const search = debounce(async (v) => {
    q.value.query = v.target.value;
    products.value = await searchProducts(q, isLoading);
}, 500);

const follow = (product) => {
    router.visit(
        route(
            'client.products.detail',
            { product: product.slug }
        ));
};

const getClass = (index) => {
    return index % 5 === 0 || index % 5 === 1
        ? 'lg:col-span-3 md:col-span-3 col-span-2 h-80'
        : 'lg:col-span-2 md:col-span-1 col-span-2 h-80';
};

// const lazyHandler = async () => {
//     products.value.push(...await lazyLoad(
//         async (q, l) => {
//             return await searchProducts(q, l);
//         }, isLoading, 0,
//     ));
// };

const showedItemsCounter = ref(0);
const lazyHandler = async () => {
    showedItemsCounter.value++;
    const limit = config('fetchLimit')
    if (showedItemsCounter.value % limit ===  0) {
        q.value.page = showedItemsCounter.value / limit + 1;
        products.value.push(...await getProducts(q, isLoading));
    }
};

</script>

<style scoped>

</style>
