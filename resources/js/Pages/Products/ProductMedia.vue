<template>
    <div class="grid grid-cols-2 gap-6">
        <el-upload
            drag
            multiple
            :action="uploadRoute"
            :on-success="loaded"
            :file-list="upload"
        >
            <el-icon class="el-icon--upload">
                <upload-filled />
            </el-icon>
            <div class="el-upload__text">
                Drop file here or <em>click to upload</em>
            </div>
            <template #tip>
                <div class="el-upload__tip">
                    jpg/png files with a size less than ...
                </div>
            </template>
        </el-upload>
        <el-carousel
            v-if="!!entry.media?.length"
            :autoplay="false"
        >
            <el-carousel-item
                v-for="media in entry.media"
            >
                <div class="flex justify-center">
                    <div>
                        <el-image
                            class="justify-center"
                            :src="media.url"
                        />
                        <el-icon
                            :size="25"
                            color="red"
                            style="position: absolute;"
                            class="align-top cursor-pointer right-0"
                            @click="removed(media.id)"
                        >
                            <Close />
                        </el-icon>
                    </div>
                </div>
            </el-carousel-item>
        </el-carousel>
    </div>
</template>

<script setup>
import { Close, UploadFilled } from '@element-plus/icons-vue';
import {  ref } from 'vue';

const props = defineProps({
    entry: {
        type: Object,
        required: true,
    },
    uploadRoute: {
        type: String,
        required: true,
    },
});

const upload = ref([]);
const emit = defineEmits(['mediaAdded', 'mediaRemoved']);

const loaded = (name) => {
    emit('mediaAdded', name)
};

const removed = (id) => {
    emit('mediaRemoved', id)
};

const clearUpload = () => {
    upload.value = [];
};

defineExpose({
    clearUpload,
});

</script>

<style scoped>

</style>
