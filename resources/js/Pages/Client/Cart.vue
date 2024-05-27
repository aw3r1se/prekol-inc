<template>
    <ClientLayout>
        <template #content>
            <div class="container mx-auto xl:p-6">
                <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100 text-center md:text-left">
                    Shopping Cart
                </h1>

                <div
                    v-if="items.length > 0"
                    class="grid grid-cols-1 gap-6"
                >
                    <div
                        v-for="item in items"
                        :key="item.id"
                        class="flex flex-col md:flex-row items-center justify-between p-4 bg-white dark:bg-gray-800 shadow-lg rounded-lg"
                    >
                        <div class="flex items-center w-full md:w-2/3">
                            <img
                                :src="item.image.url"
                                alt="Product Image"
                                class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-lg shadow-md"
                            >
                            <div class="ml-4 flex flex-col text-left w-full">
                                <h2 class="text-lg md:text-2xl font-semibold text-gray-900 dark:text-gray-100 break-words">
                                    {{ item.name }}
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ item.price.amount }} {{ item.price.currency }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-end w-full md:w-1/3 space-x-2 mt-4 md:mt-0">
                            <button
                                class="px-3 py-1 bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded hover:bg-gray-400 dark:hover:bg-gray-600"
                                @click="change(item, false)"
                            >
                                -
                            </button>
                            <span class="text-lg md:text-xl text-gray-900 dark:text-gray-100">{{ item.quantity }}</span>
                            <button
                                class="px-3 py-1 bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded hover:bg-gray-400 dark:hover:bg-gray-600"
                                @click="change(item)"
                            >
                                +
                            </button>
                            <button
                                class="ml-4 w-8 h-8 bg-red-500 text-white rounded hover:bg-red-600 transition duration-300 flex items-center justify-center"
                                @click="removeItem(item)"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between items-center p-6 shadow-lg rounded-lg">
                        <div class="mb-4 md:mb-0 text-left w-full md:w-auto">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                Total
                            </h2>
                            <p class="text-lg text-gray-600 dark:text-gray-400">
                                {{ total }} {{ currency }}
                            </p>
                        </div>
                        <button
                            class="w-full md:w-auto px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300"
                            @click="checkout"
                        >
                            Proceed to Checkout
                        </button>
                    </div>
                </div>

                <div
                    v-else
                    class="text-center py-16"
                >
                    <h2 class="text-2xl text-gray-900 dark:text-gray-100">
                        Your cart is empty.
                    </h2>
                </div>
            </div>
        </template>
    </ClientLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { route } from 'ziggy-js';
import axios from 'axios';

const getCartItems = async () => {
    let data = [];
    await axios.get(route('api.cart.list'))
        .then((res) => {
            data = res.data.data;
        })
        .catch(() => {

        })
        .finally(() => {

        });

    return data;
};

const calcTotal = () => {
    return items.value.reduce((acc, item) => {
        return acc + item.price.amount * item.quantity;
    }, 0).toFixed(2);
};

const total = ref(0);
const items = ref([]);
onMounted(async () => {
    items.value = await getCartItems();
    total.value = calcTotal();
});

const currency = 'USD';

const change = async (product, add = true) => {
    console.log(product);
    const operation = add ? 'add' : 'sub';
    await axios.post(route(`api.cart.${operation}`, { product: product.uuid }))
        .then(async () => {
            items.value = await getCartItems();
            total.value = calcTotal();
        })
        .catch(() => {

        })
        .finally(() => {

        });
};

const incrementQuantity = (item) => {
    // item.quantity++;
    // updateTotal();
};

const decrementQuantity = (item) => {
    // if (item.quantity > 1) {
    //     item.quantity--;
    //     updateTotal();
    // }
};

const removeItem = (item) => {
    //cartItems.value = cartItems.value.filter(cartItem => cartItem.id !== item.id);
    updateTotal();
};

const totalAmount = ref(0);

const updateTotal = () => {
    //totalAmount.value = cartItems.value.reduce((sum, item) => sum + item.price * item.quantity, 0);
};

const checkout = () => {
    // Logic for proceeding to checkout
    console.log('Proceeding to checkout');
};

updateTotal();
</script>

<style scoped>
/* Additional styling can go here */
</style>
