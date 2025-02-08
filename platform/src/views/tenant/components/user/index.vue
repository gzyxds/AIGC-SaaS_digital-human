<template>
    <div class="h-full flex flex-col">
        <el-form
            ref="formRef"
            class="-mb-4 mt-1 flex flex-wrap gap-x-4"
            :model="queryParams"
            :inline="true"
        >
            <el-form-item label="账号信息" class="flex-1 min-w-[200px] max-w-[300px] !mr-0">
                <el-input
                    v-model="queryParams.keyword"
                    placeholder="账号/名称"
                    clearable
                    @keyup.enter="resetPage"
                />
            </el-form-item>
            <el-form-item label="创建时间" class="flex-1 min-w-[350px] max-w-full !mr-0">
                <daterange-picker
                    v-model:startTime="queryParams.create_time_start"
                    v-model:endTime="queryParams.create_time_end"
                />
            </el-form-item>
            <el-form-item class="!mr-0">
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetParams">重置</el-button>
                <export-data
                    class="ml-2.5"
                    :fetch-fun="getTenantUserList"
                    :params="queryParams"
                    :page-size="pager.size"
                />
            </el-form-item>
        </el-form>
        <el-table class="mt-4 flex-1" v-loading="pager.loading" :data="pager.lists" height="100%">
            <el-table-column label="头像" width="90" align="center">
                <template #default="{ row }">
                    <el-avatar :src="row.avatar" :size="50" />
                </template>
            </el-table-column>
            <el-table-column label="昵称" prop="nickname" min-width="100" />
            <el-table-column label="来源" prop="channel" min-width="80" />
            <el-table-column label="性别" prop="sex" min-width="60" />
            <el-table-column label="状态" min-width="80">
                <template #default="{ row }">
                    <el-tag :type="row.is_disable === 0 ? 'primary' : 'danger'">
                        {{ row.is_disable === 0 ? '开启' : '关闭' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="创建时间" prop="create_time" min-width="120" />
            <el-table-column label="操作" width="auto" min-width="90" fixed="right">
                <template #default="{ row }">
                    <el-button
                        v-perms="['user.user/detail']"
                        type="primary"
                        link
                        @click="detialRef?.open(row.id)"
                    >
                        详情
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <div class="flex justify-end my-4">
            <pagination v-model="pager" @change="getLists" />
        </div>

        <Detail ref="detialRef" :tenant_id="props.tenant_id" />
    </div>
</template>

<script lang="ts" setup>
import { getTenantUserList } from '@/api/consumer'
import { usePaging } from '@/hooks/usePaging'
import { useComponentRef } from '@/utils/getExposeType'

import Detail from './detail.vue'

const detialRef = useComponentRef(Detail)

const props = defineProps({
    tenant_id: {
        type: Number,
        default: 0
    }
})

const queryParams = reactive({
    keyword: '',
    channel: '',
    create_time_start: '',
    create_time_end: '',
    tenant_id: props.tenant_id
})

const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: getTenantUserList,
    params: queryParams
})
onActivated(() => {
    getLists()
})

onMounted(() => {
    getLists()
})
</script>
