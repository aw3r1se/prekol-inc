<template>
    <IndexPageLayout entity="products">
        <el-table-column
            property="uuid"
            label="UUID"
        />
        <el-table-column
            property="name"
            label="Name"
        />
        <el-table-column
            property="prices"
            label="Price"
            type="expand"
            width="100"
        >
            <template #default="{ row }">
                <div class="py-3 px-2">
                    <el-table :data="row.prices">
                        <el-table-column
                            property="currency"
                            label="Currency"
                        />
                        <el-table-column
                            property="amount"
                            label="Amount"
                        />
                    </el-table>
                </div>
            </template>
        </el-table-column>
        <el-table-column
            property="createdAt"
            label="Created at"
            sortable
        />
        <el-table-column
            property="updatedAt"
            label="Updated at"
            sortable
        />
    </IndexPageLayout>
</template>

<script setup>
import IndexPageLayout from '@/Layouts/IndexPageLayout.vue';
</script>

<style scoped>

</style>
