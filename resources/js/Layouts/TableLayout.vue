<template>
    <div class="p-2 flex">
        <el-input
            class="mx-2"
            v-model="q"
            placeholder="Type something..."
            :prefix-icon="Search"
            @input="handleSearchInput"
        />
        <el-button
            class="mx-2"
            type="primary"
            @click="create"
        >
            Create entry
        </el-button>
    </div>
    <el-table
        :data="items"
        @sort-change="handleSort"
    >
        <slot />
        <el-table-column
            v-if="editRoute || destroyRoute"
            label="Actions"
        >
            <template #default="{ row }">
                <div class="flex">
                    <div
                        v-if="editRoute"
                        class="cursor-pointer mr-3"
                        @click="edit(row)"
                    >
                        <el-icon :size="22">
                            <Edit />
                        </el-icon>
                    </div>
                    <div
                        v-if="destroyRoute && !row.deletedAt"
                        class="cursor-pointer"
                        @click="destroy(row)"
                    >
                        <el-icon
                            :size="22"
                            color="red"
                        >
                            <Delete />
                        </el-icon>
                    </div>
                    <div
                        v-if="restoreRoute && row.deletedAt"
                        class="cursor-pointer"
                        @click="restore(row)"
                    >
                        <el-icon :size="22">
                            <RefreshLeft />
                        </el-icon>
                    </div>
                </div>
            </template>
        </el-table-column>
    </el-table>
    <el-pagination
        :page-size="pageSize"
        :pager-count="pagerCount"
        :current-page="currentPage"
        :total="total"
        layout="prev, pager, next"
        @currentChange="handlePaginationClick"
    />
</template>

<script setup>
import { Edit, Delete, Search, RefreshLeft } from '@element-plus/icons-vue'
import { ref, defineEmits } from 'vue';
import { debounce } from 'lodash';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
    pageSize: {
        type: Number,
        default: 15,
    },
    pagerCount: {
        type: Number,
        default: 5,
    },
    currentPage: {
        type: Number,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },
    primaryKey: {
        type: String,
        required: true,
    },
    createRoute: {
        type: String,
        required: true,
    },
    editRoute: {
        type: String,
        required: true,
    },
    destroyRoute: {
        type: String,
        required: true,
    },
    restoreRoute: {
        type: String,
        required: false,
    },
});

const emit = defineEmits([
    'onSearch',
    'onPagination',
    'onSort',
]);

const q = ref();
const handleSearchInput = debounce(() => {
    emit('onSearch', q.value);
}, 500);

const handlePaginationClick = (page) => {
    emit('onPagination', page);
};

const handleSort = (sort) => {
    emit('onSort', sort);
};

const create = () => {
    router.visit(
        route(props.createRoute),
    );
};

const edit = (row) => {
    router.visit(
        route(
            props.editRoute,
            row[props.primaryKey],
        ),
    );
};

const destroy = async (row) => {
    await axios.delete(
        route(
            props.destroyRoute,
            row[props.primaryKey],
        ),
    ).then(() => {
        emit('onSearch', q.value);
    }).catch(() => {

    });
};

const restore = async (row) => {
    await axios.patch(
        route(
            props.restoreRoute,
            row[props.primaryKey],
        ),
    ).then(() => {
        emit('onSearch', q.value);
    }).catch(() => {

    });
};

</script>

<style scoped>

</style>
