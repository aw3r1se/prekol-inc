<template>
    <EditLayout
        ref="parent"
        entity="products"
        :entry-id="id"
        @init="handleChange"
        @change="handleChange"
        @submit="clearUpload"
    >
        <template #main>
            <el-form-item
                v-if="entry.uuid"
                label="UUID"
            >
                <el-input
                    v-model="entry.uuid"
                    disabled
                />
            </el-form-item>
            <el-form-item label="Name">
                <el-input v-model="entry.name"/>
            </el-form-item>
        </template>
        <el-tab-pane
            v-if="id"
            name="prices"
            label="Prices"
        >
            <ProductPrices
                :entry="entry"
                @price-added="refresh"
            />
        </el-tab-pane>
        <el-tab-pane
            v-if="id"
            name="media"
            label="Media"
        >
            <ProductMedia
                ref="productMedia"
                :entry="entry"
                :upload-route="route('api.products.upload', id)"
                @media-added="addMedia"
                @media-removed="removeMedia"
            />
        </el-tab-pane>
    </EditLayout>
</template>

<script setup>
import EditLayout from '@/Layouts/EditLayout.vue';
import ProductPrices from "@/Pages/Products/ProductPrices.vue";
import ProductMedia from "@/Pages/Products/ProductMedia.vue";
import { ref } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps({
   id: {
       type: String,
       required: false,
   },
});

const entry = ref({ upload: [] });
const media = ref([]);

const handleChange = (data) => {
    entry.value = data;
};

const parent = ref();
const refresh = (async () => {
    await parent.value.get();
    entry.value.upload = media.value;
});

const addMedia = (name) => {
    media.value.push(name);
    entry.value.upload = media.value;
};

const removeMedia = (media) => {
    axios.delete(route('api.media.remove', media))
        .then(async () => {
            await refresh();
        }).catch(() => {

        }).finally(() => {

        },
    );
};

const productMedia = ref();
const clearUpload = () => {
    productMedia.value.clearUpload();
};

</script>

<style scoped>

</style>
