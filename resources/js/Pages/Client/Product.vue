<template>
    <ClientLayout>
        <template #content>
            <div class="container mx-auto px-4 py-6">
                <carousel
                    class="w-full"
                    :items-to-show="1"
                >
                    <slide
                        v-for="media in product.media"
                        :key="media"
                    >
                        <div class="flex justify-center w-full">
                            <el-image
                                :src="media.url"
                                class="rounded-lg shadow-lg"
                            />
                        </div>
                    </slide>
                    <template #addons>
                        <navigation class="fill-gray-500" />
                        <pagination />
                    </template>
                </carousel>
            </div>

            <div class="container mx-auto px-4 py-6 grid gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">
                        {{ product.name }}
                    </h2>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                    <div>
                        <div class="flex flex-wrap gap-2">
                            <el-tag
                                v-for="tag in product.tags"
                                :key="tag.name"
                                class="my-1 cursor-pointer"
                            >
                                {{ tag.name }}
                            </el-tag>
                        </div>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="text-gray-300 md:mx-10">
                        {{ product.description }}
                    </div>

                    <div class="flex flex-col items-end gap-4">
                        <div
                            v-if="product.relevantPrice"
                            class="text-right text-lg font-semibold text-gray-100"
                        >
                            {{ product.relevantPrice.amount }} {{ product.relevantPrice.currency }}
                        </div>

                        <el-button
                            v-if="product.isInOrder"
                            v-loading="isCartButtonLoading"
                            type="success"
                            class="w-full flex items-center justify-between"
                            @click="fromCart"
                        >
                            <span class="flex-grow text-left">REMOVE FROM CART</span>
                            <el-icon size="18">
                                <ShoppingCart />
                            </el-icon>
                        </el-button>

                        <el-button
                            v-else
                            v-loading="isCartButtonLoading"
                            type="success"
                            class="w-full flex items-center justify-between"
                            @click="toCart"
                        >
                            <span class="flex-grow text-left">ADD TO CART</span>
                            <el-icon size="18">
                                <ShoppingCart />
                            </el-icon>
                        </el-button>

                        <el-button
                            type="primary"
                            class="w-full flex items-center justify-between"
                        >
                            <span class="flex-grow text-left">TO FAVORITES</span>
                            <el-icon size="18">
                                <StarFilled />
                            </el-icon>
                        </el-button>
                    </div>
                </div>
            </div>
        </template>
    </ClientLayout>
</template>

<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import axios from 'axios';
import { onBeforeMount, ref } from 'vue';
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';
import { route } from 'ziggy-js';
import { ShoppingCart, StarFilled } from '@element-plus/icons-vue';
import { toast } from 'vue3-toastify';
import { router } from '@inertiajs/vue3';

const product = ref({});
const routeParam = ref(null);

const defineProduct = async (productId) => {
    let data = {};
    await axios.get(route('api.products.show', productId))
        .then((res) => {
            data = res.data.data;
        }).catch((e) => {
            router.visit(route('client.404'));
        });

    console.log(data);

    return data;
};

onBeforeMount(async () => {
    routeParam.value = route().params.product;
    product.value = await defineProduct(routeParam.value);
});

const isCartButtonLoading = ref(false);
const toCart = async () => {
    isCartButtonLoading.value = true;
    await axios.post(route('api.products.add-to-cart', product.value.uuid))
        .then(async () => {
            product.value.isInOrder = (await defineProduct(routeParam.value)).isInOrder;
            toast('Successfully added to the cart!', { autoClose: 1000 });
        }).catch((e) => {
            console.log(e);
        }).finally(() => {
            isCartButtonLoading.value = false;
        });
};

const fromCart = async () => {
    isCartButtonLoading.value = true;
    await axios.post(route('api.products.remove-from-cart', product.value.uuid))
        .then(async () => {
            product.value.isInOrder = (await defineProduct(routeParam.value)).isInOrder;
            toast('Successfully removed from the cart!', { autoClose: 1000 });
        }).catch((e) => {
            console.log(e);
        }).finally(() => {
            isCartButtonLoading.value = false;
        });
};
</script>

<style>
.carousel__icon {
    fill: gray;
}

.carousel__pagination-button {
    background-color: gray;
    padding: 0;
    margin: 0 4px;
}

.carousel__pagination-button--active,
.carousel__pagination-button--active::after {
    background-color: white;
}

.carousel__pagination-button,
.carousel__pagination-button:hover,
.carousel__pagination-button:hover::after {
    background-color: white;
}
</style>
