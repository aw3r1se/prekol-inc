import { ref } from 'vue';
import { config } from '@/utils/config.js';

const q = ref({
    page: 1,
});

export const lazyLoad = async (callback, isLoading, init) => {
    let num;
    if (!num) {
        num = init;
    }

    num++;
    const limit = config('fetchLimit')
    if (num % limit ===  0) {
        q.value.page = num / limit + 1;
        return callback(q, isLoading);
    }
};

export default {
    lazyLoad,
}
