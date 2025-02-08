<template>
    <div>
        <el-card class="!border-none" shadow="never">
            <el-form ref="formRef" class="mb-[-16px]" :model="queryParams" :inline="true">
                <el-form-item label="接口类型">
                    <el-select v-model="queryParams.channel" style="width: 280px">
                        <!-- v-if="isChatModel" -->
                        <el-option
                            v-for="item in modelList"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        />
                        <!-- <template v-else>
                            <el-option
                                v-for="(item, key) in modelList"
                                :key="key"
                                :label="item"
                                :value="key"
                            />
                        </template> -->
                    </el-select>
                </el-form-item>
                <el-form-item label="状态">
                    <el-select
                        style="width: 280px"
                        v-model="queryParams.status"
                        placeholder="请选择"
                    >
                        <el-option label="全部" value />
                        <el-option label="开启" :value="1" />
                        <el-option label="关闭" :value="0" />
                    </el-select>
                </el-form-item>

                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card shadow="never" class="!border-none mt-4">
            <el-tabs v-model="queryParams.type" @tabChange="changeTabs">
                <el-tab-pane
                    v-for="(item, index) in tabLists"
                    :label="`${item.name}`"
                    :name="item.type"
                    :key="index"
                />
            </el-tabs>

            <div class="mb-[16px]">
                <el-button v-perms="['key.keyPool/add']" type="primary" @click="handleEdit('add')">
                    + 新增密钥
                </el-button>

                <!-- <el-button
                    class="ml-2"
                    type="default"
                    :plain="true"
                    @click="importsRef.open(queryParams.type)"
                >
                    批量导入
                </el-button> -->
            </div>

            <el-table size="large" v-loading="pager.loading" :data="pager.lists">
                <!-- <el-table-column label="接口类型" prop="channel" min-width="100" /> -->
                <!-- <el-table-column label="登录账号" prop="account" min-width="120" /> -->
                <el-table-column label="密钥  " prop="key" min-width="200">
                    <template #default="{ row }">
                        <div class="line-clamp-3 cursor-pointer" v-copy="row.key">
                            {{ row.key }}
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="状态" min-width="100" v-perms="['key.keyPool/status']">
                    <template #default="{ row }">
                        <el-switch
                            v-model="row.status"
                            :active-value="1"
                            :inactive-value="0"
                            @change="changeStatus(row.id)"
                        />
                    </template>
                </el-table-column>
                <el-table-column label="备注" min-width="150">
                    <template #default="{ row }">
                        {{ row.remark }}
                    </template>
                </el-table-column>

                <el-table-column label="创建时间" prop="create_time" min-width="180" />
                <el-table-column label="更新时间" prop="update_time" min-width="180" />
                <el-table-column label="操作" fixed="right" min-width="180">
                    <template #default="{ row }">
                        <el-button
                            v-if="row.change_btn"
                            v-perms="['key.keyPool/edit']"
                            type="primary"
                            link
                            @click="handleEdit('edit', row)"
                        >
                            更换
                        </el-button>
                        <el-button
                            v-else
                            v-perms="['key.keyPool/edit']"
                            type="primary"
                            link
                            @click="handleEdit('edit', row)"
                        >
                            编辑
                        </el-button>
                        <el-button
                            v-perms="['key.keyPool/del']"
                            type="danger"
                            :link="true"
                            @click="handleDelete(row.id)"
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

        <!-- <balancePop ref="balanceRef" /> -->
        <edit-popup v-if="showEdit" ref="editRef" @success="getLists" />
        <!-- <imports ref="importsRef" :template="pager.extend.template_url" @success="getLists" /> -->
    </div>
</template>
<script setup lang="ts">
import { changeKey, delKey, getkeyLists } from '@/api/ai_setting/key_setting'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'

import EditPopup from './edit.vue'
// import Imports from './components/imports.vue'

// const importsRef = shallowRef()
//是/否显示编辑弹框
const showEdit = ref(true)
//编辑弹框ref
const editRef = shallowRef<InstanceType<typeof EditPopup>>()
const modelList = ref<any[]>([
    {
        id: 1,
        name: '创客API'
    }
])
const queryParams = reactive({
    type: 1,
    channel: '',
    keyword: '',
    start_time: '',
    end_time: '',
    status: ''
})

const tabLists = [
    {
        name: '声音克隆',
        type: 1
    },
    {
        name: '声音合成',
        type: 2
    },
    {
        name: '数字人合成',
        type: 3
    },
    {
        name: '内容审核',
        type: 4
    }
]

// const isChatModel = computed(() => [1, 2].includes(Number(queryParams.type)))

const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: getkeyLists,
    params: queryParams
})

const changeTabs = () => {
    getLists()
    // getAiModel()
}

// const getAiModel = async () => {
//     try {
//         const data = await getmoduleList({
//             type: queryParams.type
//         })

//         modelList.value = data
//     } catch (error) {
//         console.log('获取ai模型失败=>', error)
//     }
// }

//修改状态
const changeStatus = async (id: any) => {
    await changeKey({ id })
    feedback.msgSuccess('操作成功')
    getLists()
}

// 编辑
const handleEdit = (mode: string, value?: any) => {
    // queryParams.type | 当前类型 1对话 2绘画，mode: add|edit
    editRef.value?.open(queryParams.type, mode, value)
}

//  删除
const handleDelete = async (id: number) => {
    await feedback.confirm('确定要删除？')
    await delKey({ id })
    feedback.msgSuccess('操作成功')
    getLists()
}

getLists()
// getAiModel()
</script>
