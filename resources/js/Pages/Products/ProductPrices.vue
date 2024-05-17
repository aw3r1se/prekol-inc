<template>
    <div v-loading="isLoading">
        <el-table :data="entry.prices">
            <el-table-column
                property="currency"
                label="Currency"
            />
            <el-table-column
                property="amount"
                label="Amount"
            />
            <el-table-column
                property="createdAt"
                label="Created at"
            />
        </el-table>
        <div class="my-5">
            <el-button
                v-if="!hasNewItem"
                type="primary"
                @click="hasNewItem = true"
            >
                New price
            </el-button>
            <el-form v-if="hasNewItem">
                <el-form-item label="Currency">
                    <el-select v-model="newPrice.currency">
                        <el-option
                            v-for="currency in currencies"
                            :label="currency"
                            :value="currency"
                        />
                    </el-select>
                </el-form-item>
                <el-form-item label="Amount">
                    <el-input
                        v-model="newPrice.amount"
                        type="number"
                    />
                </el-form-item>
                <el-form-item class="flex justify-end">
                    <el-button
                        type="success"
                        @click="addPrice"
                    >
                        Add
                    </el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>

<script setup>
import { route } from 'ziggy-js';
import { ref, onBeforeMount, defineEmits } from 'vue';

const props = defineProps({
    entry: {
        type: Object,
        required: true,
    },
});

const isLoading = ref(false);
const hasNewItem = ref(false);
const newPrice = ref({ });

const currencies = ref([]);
onBeforeMount( async () => {
    await getCurrencies();
});

const getCurrencies = (async () => {
    axios.get(route('api.currencies.index'))
        .then((res) => {
            currencies.value = res.data;
        }).catch(() => {

        }).finally(() => {

        }
    );
});

const emit = defineEmits(['priceAdded']);
const addPrice = (async () => {
    isLoading.value = true;
    axios.post(
        route(
            'api.products.add-price',
            props.entry.uuid
        ), {
            ...newPrice.value
        },
    ).then(() => {

    }).catch(() => {

    }).finally(async () => {
        isLoading.value = false;
        emit('priceAdded');
    })
});

</script>

<style scoped>

</style>
