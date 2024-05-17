<template>
    <AppLayout :title="entity.label">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ isNew ? `New ${entity.singular}` : entry.name }}
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <el-container
                    v-loading="isLoading"
                    class="p-2 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <el-header class="flex justify-between">
                        <el-button
                            class="my-2"
                            type="info"
                            @click="back"
                        >
                            Back
                        </el-button>
                        <el-button
                            class="my-2"
                            type="danger"
                        >
                            Delete
                        </el-button>
                    </el-header>
                    <el-main>
                        <el-tabs v-model="activeTab">
                            <el-tab-pane
                                name="main"
                                label="Main"
                            >
                                <el-form
                                    class="my-2"
                                    v-model="formData"
                                    @change="handleChange"
                                >
                                    <slot name="main"/>
                                </el-form>
                            </el-tab-pane>
                            <slot />
                        </el-tabs>
                    </el-main>
                    <el-footer class="flex justify-end">
                        <el-button
                            class="my-5"
                            type="primary"
                            @click="submit"
                        >
                            Submit
                        </el-button>
                    </el-footer>
                </el-container>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, onBeforeMount, ref, defineExpose } from 'vue';
import { defineEntity } from '@/helpers.js';
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    entity: {
        type: String,
        required: true,
    },
    entryId: {
        type: String,
        required: false,
    },
});

const isNew = computed(() => {
    return !props.entryId;
});

const activeTab = ref('main');
const isLoading = ref(false);

const entity = ref({})
onBeforeMount(async () => {
    entity.value = defineEntity(props.entity, usePage());

    if (!isNew.value) {
        await get();
    }
});

const emit = defineEmits(['init', 'change', 'submit']);

const formData = ref({ });
const entry = ref({ });

const get = async () => {
    isLoading.value = true;
    axios.get(
        route(`api.${entity.value.name}.show`, props.entryId)
    ).then((res) => {
        const result = res.data.data;
        entry.value = { ...result };
        formData.value = { ...result };
        emit('init', formData.value);
    }).catch((e) => {
        //TODO: need toast
    }).finally(() => {
        isLoading.value = false;
    });
};

const back = () => {
    router.visit(route(`dashboard.${entity.value.name}.index`));
};

const handleChange = () => {
    emit('change', formData.value);
};

const submit = async () => {
    isLoading.value = true;
    emit('submit', formData.value);

    const method = isNew.value
        ? 'post'
        : 'put';

    const path = isNew.value
        ? 'store'
        : 'update';

    await axios.post(
        route(
            `api.${entity.value.name}.${path}`,
            !isNew.value ? props.entryId : null,
        ), {
            _method: method,
            ...formData.value,
        }).then(async () => {
            await get();
        }).catch(() => {

        }).finally(() => {
            isLoading.value = false;
        },
    );
};

defineExpose({
    get,
});

</script>

<style scoped>

</style>
