<template>
    <ClientLayout>
        <template #content>
            <div class="container flex mb-5">
                <carousel
                    class="w-full"
                    :items-to-show="1"
                >
                    <slide
                        v-for="media in product.media"
                        :key="media"
                    >
                        <div class="flex justify-center w-full">
                            <el-image :src="media.url" />
                        </div>
                    </slide>

                    <template #addons>
                        <navigation class="fill-white-500" />
                        <pagination />
                    </template>
                </carousel>
            </div>
            <div class="container grid gap-3">
                <div>
                    <h2>{{ product.name }}</h2>
                </div>
                <div
                    class="
                        flex
                        justify-between
                    "
                >
                    <div>
                        Tags:
                        <div v-for="tag in product.tags">
                            <el-tag class="my-1 cursor-pointer">
                                {{ tag.name }}
                            </el-tag>
                        </div>
                    </div>
                    <!-- DESCRIPTION DESKTOP -->
                    <div class="max-sm:hidden mx-10">
                        {{ product.description }}
                    </div>
                    <div class="flex flex-col align-baseline gap-2">
                        <div
                            v-if="product.relevantPrice"
                            class="text-right"
                        >
                            {{ product.relevantPrice.amount }} {{ product.relevantPrice.currency }}
                        </div>
                        <el-button
                            v-if="product.isInOrder"
                            v-loading="isCartButtonLoading"
                            type="success"
                            class="
                                flex
                                justify-between
                                w-full
                            "
                            @click="fromCart"
                        >
                            <div class="mx-1">
                                REMOVE FROM THE CART :(
                            </div>
                            <el-icon size="18">
                                <ShoppingCart />
                            </el-icon>
                        </el-button>
                        <el-button
                            v-else
                            v-loading="isCartButtonLoading"
                            type="success"
                            class="
                                flex
                                justify-between
                                w-full
                            "
                            @click="toCart"
                        >
                            <div class="mx-1">
                                ADD TO THE CART
                            </div>
                            <el-icon size="18">
                                <ShoppingCart />
                            </el-icon>
                        </el-button>
                        <div />
                        <el-button
                            type="primary"
                            class="
                                flex
                                justify-between
                                w-full
                            "
                        >
                            <div class="mx-1">
                                TO FAVORITES
                            </div>
                            <el-icon size="18">
                                <StarFilled />
                            </el-icon>
                        </el-button>
                    </div>
                </div>
                <!-- DESCRIPTION MOBILE -->
                <div class="xl:hidden">
                    {{ product.description }}
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

const product = ref({ });
const routeParam = ref(null);

const defineProduct = async (productId) => {
    let data = { };
    await axios.get(route('api.products.show', productId))
        .then((res) => {
            data = res.data.data;
        }).catch((e) => {
            /** TODO: handle 404 */
        }).finally(() => {

        });

    return data;
};

onBeforeMount(async () => {
    routeParam.value = route().params.product;
    product.value = await defineProduct(routeParam.value);
});

const isCartButtonLoading = ref(false);
const toCart = async () => {
    isCartButtonLoading.value = true;
    await axios.post(
        route('api.products.add-to-cart', product.value.uuid)
    ).then(async () => {
        product.value.isInCart = await defineProduct(routeParam.value).isInCart;
        toast('Successfully added to the cart!', {
            autoClose: 1000,
        });
    }).catch((e) => {
        console.log(e);
    }).finally(() => {
        isCartButtonLoading.value = false;
    });
};

const fromCart = async () => {
    isCartButtonLoading.value = true;
    await axios.post(
        route('api.products.remove-from-cart', product.value.uuid)
    ).then(async () => {
        product.value.isInCart = await defineProduct(routeParam.value).isInCart;
        toast('Successfully removed from the cart!', {
            autoClose: 1000,
        });
    }).catch(() => {

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
