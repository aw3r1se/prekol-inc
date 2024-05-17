<template>
    <ClientLayout>
        <template #content>
            <div
                class="
                    container
                    grid
                    gap-5
                    products
                    justify-items-center
                "
            >
                <div
                    v-for="product in products"
                    class="product w-full h-[60vh] cursor-pointer"
                    @click="follow(product)"
                >
                    <div
                        class="w-full h-full"
                        :style="{
                            'background-image': `url(${product.image?.url})`,
                            'background-size': 'cover',
                            'background-position': 'center center',
                            'background-repeat': 'no-repeat no-repeat'
                        }"
                    />
                </div>
            </div>
        </template>
    </ClientLayout>
</template>

<script setup>
import axios from 'axios';
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { ref, onBeforeMount } from 'vue';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';

const products = ref();
onBeforeMount(async () => {
    await axios.get(route('api.products.search'))
        .then((res) => {
            products.value = res.data.data
                .filter((p) => {
                    return !!p.image;
                });
        }).catch(() => {

        }).finally(() => {

        });
});

const follow = (product) => {
    router.visit(
        route('client.products.detail', {
            product: product.uuid,
        })
    );
};


</script>

<style scoped>
    @media (min-width: 1280px) {
        .products {
            grid-template-columns: repeat(6, 1fr);
        }

        .product {
            grid-column: auto / span 2;
        }

        .products .product:nth-child(5n + 1),
        .products .product:nth-child(5n + 2) {
            grid-column: auto / span 3;
        }
    }

    .product {
        grid-template-columns: 1fr;
    }
</style>
