<template>
    <IndexPageLayout entity="users">
        <el-table-column
            property="uuid"
            label="UUID"
        />
        <el-table-column
            property="name"
            label="Name"
        />
        <el-table-column
            property="createdAt"
            label="Created at"
        />
    </IndexPageLayout>
</template>

<script setup>

import IndexPageLayout from '@/Layouts/IndexPageLayout.vue';
</script>

<style scoped>

</style>
