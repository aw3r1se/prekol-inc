import { fetchProducts } from '@/utils/client.js';
import { toast } from 'vue3-toastify';

const searchProducts = async (q, loading) => {
    return await getProducts(q, loading)
};

const getProducts = async (q, loading) => {
    loading.value = true;
    let collection = [];
    await fetchProducts(
        q.value.query,
        q.value.page,
        q.value.perPage,
    ).then((data) => {
        collection = data;
    }).catch((e) => {
        toast(e.message, {
            type: 'error',
        })
    }).finally(() => {
        loading.value = false;
    });

    return collection;
};

export {
    getProducts,
    searchProducts,
};
