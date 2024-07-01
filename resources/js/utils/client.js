import { config } from '@/utils/config.js';
import { route } from 'ziggy-js';
import axios from 'axios';

const defaultPerPage = config('fetchLimit');

const fetchProducts = async (
    query,
    page = 1,
    perPage = defaultPerPage,
) => {
    let data = [];
    await axios.get(
        route('api.products.search', {
            q: query,
            page: page,
            per_page: perPage,
        }),
    ).then((res) => {
        data = res.data.data;
    })

    return data;
};

export {
    fetchProducts,
}
