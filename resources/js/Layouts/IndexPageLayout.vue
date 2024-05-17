<template>
    <AppLayout :title="entity.label">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ entity.label }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    v-loading="isLoading"
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <TableLayout
                        :total="data.meta.total"
                        :current-page="data.meta.current_page"
                        :items="data.data"
                        :create-route="createRoute"
                        :edit-route="editRoute"
                        :destroy-route="destroyRoute"
                        :restore-route="restoreRoute"
                        :primary-key="primaryKey"
                        @on-search="handleSearchInput"
                        @on-pagination="handlePaginationChange"
                        @on-sort="handleSort"
                    >
                        <slot />
                    </TableLayout>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TableLayout from '@/Layouts/TableLayout.vue';
import { computed, onBeforeMount, onMounted, ref } from 'vue';
import { route } from 'ziggy-js';
import { defineEntity } from '@/helpers.js';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    entity: {
        type: String,
        required: true,
    },
});

const data = ref({
    data: [],
    meta: {
        current_page: 1,
        total: 1,
    }
});

const isLoading = ref(false);

const searchParams = ref({
    q: null,
    sort: null,
    page: 1,
});

const handleSearchInput = async (q) => {
    searchParams.value.q = q;
    await search();
};

const handlePaginationChange = async (page) => {
    searchParams.value.page = page;
    await search();
};

const handleSort = async (sort) => {
    searchParams.value.sort = {
        column: sort.prop,
        direction: sort.order,
    };
    await search();
};

const entity = ref({})
onBeforeMount(() => {
    entity.value = defineEntity(props.entity, usePage());
});

onMounted(async () => {
    await search();
});

const searchRoute = computed(() => {
   return entity.value.searchRoute ?? `api.${entity.value.name}.search`;
});

const createRoute = computed(() => {
    return entity.value.createRoute ?? `dashboard.${entity.value.name}.create`;
});

const editRoute = computed(() => {
    return entity.value.editRoute ?? `dashboard.${entity.value.name}.edit`;
});

const destroyRoute = computed(() => {
    return entity.value.destroyRoute ?? `api.${entity.value.name}.destroy`;
});

const restoreRoute = computed(() => {
    return entity.value.restoreRoute ?? `api.${entity.value.name}.restore`;
});

const primaryKey = computed(() => {
    return entity.value.primaryKey;
});

async function search() {
    isLoading.value = true;
    const url = route(
        searchRoute.value, {
            q: searchParams.value.q,
            page: searchParams.value.page,
            sort: searchParams.value.sort,
            withTrashed: true,
        }
    );

    await axios.get(url)
        .then((response) => {
            data.value = response.data;
        }).catch((e) => {
            console.log(e);
        }).finally(() => {
            isLoading.value = false;
        });
}

</script>

<style scoped>

</style>
