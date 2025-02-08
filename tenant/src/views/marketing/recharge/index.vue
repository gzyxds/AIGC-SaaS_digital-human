<template>
    <div>
        <el-card class="!border-none mt-4" shadow="never">
            <el-button
                v-perms="['power.powerPackage/add']"
                type="primary"
                @click="editRef?.openHandle('add')"
            >
                新增充值套餐</el-button
            >
            <el-table size="large" v-loading="pager.loading" :data="pager.lists" class="mt-5">
                <el-table-column label="套餐名称" prop="title" min-width="100" />
                <el-table-column label="套餐价格" prop="cost" min-width="100" />
                <el-table-column label="套餐状态" prop="cost" min-width="100">
                    <template #default="{ row }">
                        <el-switch
                            v-model="row.status"
                            :active-value="1"
                            :inactive-value="0"
                            @change="handleChange(row)"
                        />
                    </template>
                </el-table-column>
                <!-- <el-table-column label="是否推荐" prop="cost" min-width="100">
                    <template #default="{ row }">
                        <el-switch
                            v-model="row.recommend"
                            :active-value="1"
                            :inactive-value="0"
                            @change="handleChange(row)"
                        />
                    </template>
                </el-table-column> -->
                <el-table-column label="套餐内容" prop="power" min-width="120">
                    <template #default="{ row }"> 算力数量:{{ row.power }} </template>
                </el-table-column>
                <el-table-column label="额外赠送" prop="power" min-width="120">
                    <template #default="{ row }">
                        <el-tag type="success" v-if="row.gift == 1">开启</el-tag>
                        <el-tag type="danger" v-if="row.gift == 0">关闭</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="赠送内容" prop="power" min-width="120">
                    <template #default="{ row }"> 赠送数量:{{ row.gift_power }} </template>
                </el-table-column>
                <el-table-column label="排序" prop="sort" min-width="120" />

                <el-table-column label="操作" width="120" fixed="right">
                    <template #default="{ row }">
                        <el-button
                            v-perms="['power.powerPackage/detail']"
                            type="primary"
                            link
                            @click="editRef?.openHandle('edit', row.id)"
                        >
                            编辑
                        </el-button>
                        <el-button
                            v-perms="['power.powerPackage/delete']"
                            type="danger"
                            link
                            @click="handledel(row.id)"
                        >
                            删除
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div class="flex justify-end mt-4">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
    </div>
    <Add ref="addRef" @refresh="getLists()" />
    <Edit ref="editRef" @refresh="getLists()" />
</template>
<script lang="ts" setup name="powerPackageLists">
import { delpowerPackage, editpowerPackage, getpowerPackage } from '@/api/marketing/recharge'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'
import { useComponentRef } from '@/utils/getExposeType'

import Edit from './edit.vue'
const editRef = useComponentRef(Edit)

const { pager, getLists } = usePaging({
    fetchFun: getpowerPackage
})
const handledel = async (id: number) => {
    await feedback.confirm('确定要删除？')
    await delpowerPackage({ id })
    getLists()
}
onActivated(() => {
    getLists()
})
const handleChange = async (row: any) => {
    await editpowerPackage(row)
    getLists()
}

getLists()
</script>
