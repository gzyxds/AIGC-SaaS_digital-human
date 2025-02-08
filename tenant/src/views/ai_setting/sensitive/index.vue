<template>
    <div>
        <el-card shadow="never" class="!border-none">
            <el-form class="ls-form" :model="formData" label-width="120px">
                <div class="text-xl font-medium mb-[20px]">内容审核</div>
                <el-form-item label="在线审核" prop="name">
                    <div>
                        <el-radio-group v-model="formData.sensitive_mode" @change="handleSubmit">
                            <el-radio :value="1">极速审核</el-radio>
                            <!-- <el-radio :value="2">严谨审核</el-radio> -->
                        </el-radio-group>
                        <div class="form-tips">
                            将声音合成文案提交至在线审核接口，审核违规将无法合成音频。必须设置，否则禁止声音合成。
                            <a
                                href="https://api.hihookeji.com"
                                target="_blank"
                                rel=""
                                class="text-primary"
                            >
                                前往开通</a
                            >
                        </div>
                    </div>
                </el-form-item>
                <el-form-item label="敏感词库" prop="name">
                    <div>
                        <el-switch
                            :active-value="1"
                            :inactive-value="0"
                            v-model="formData.sensitive_switch"
                            @change="handleSubmit"
                        />
                        <div class="form-tips">
                            默认关闭，开启后，声音合成文案会优先进行自定义敏感词库过滤，命中后无法合成音频。
                        </div>
                    </div>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card shadow="never" class="!border-none mt-4">
            <el-form ref="formRef" class="mb-[16px]" :model="queryParams" :inline="true">
                <el-form-item label="敏感词">
                    <el-input
                        class="w-[280px]"
                        v-model="queryParams.words"
                        placeholder="请输入"
                        clearable
                    />
                </el-form-item>
                <el-form-item label="状态">
                    <el-select class="w-[280px]" v-model="queryParams.status" style="width: 280px">
                        <el-option value>全部</el-option>
                        <el-option :value="1" label="已开启">已开启</el-option>
                        <el-option :value="0" label="已关闭">已关闭</el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
            <el-button v-perms="['sensitive.words/add']" type="primary" @click="handleedit('add')">
                新增敏感词
            </el-button>
            <el-table size="large" v-loading="pager.loading" :data="pager.lists" class="mt-4">
                <el-table-column label="ID" prop="id" width="80" />
                <el-table-column label="敏感词" prop="words" min-width="280" />
                <el-table-column label="状态" min-width="100">
                    <template #default="{ row }">
                        <el-tag type="success" v-if="row.status == 1">已开启</el-tag>
                        <el-tag type="danger" v-if="row.status == 0">已关闭</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="创建时间" prop="create_time" min-width="180" />
                <el-table-column label="操作" fixed="right" width="120">
                    <template #default="{ row }">
                        <el-button
                            v-perms="['sensitive.words/edit']"
                            link
                            type="primary"
                            @click="handleedit('edit', row)"
                            >编辑
                        </el-button>
                        <el-button
                            v-perms="['sensitive.words/delete']"
                            link
                            type="danger"
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
        <editPop ref="editPopref" v-if="showedit" @close="handlecolse" />
    </div>
</template>
<script setup lang="ts" name="sensitive">
import {
    delSensitive,
    getSensitiveConfig,
    getsensitiveLists,
    setSensitiveConfig
} from '@/api/ai_setting/sensitive'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'

import editPop from './edit.vue'

const queryParams = reactive({
    words: '',
    status: ''
})
const formData = ref({
    sensitive_mode: 1,
    sensitive_switch: 1
})
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: getsensitiveLists,
    params: queryParams
})
getLists()

//弹框ref
const editPopref = shallowRef<InstanceType<typeof editPop>>()
//打开弹框
const showedit = ref(false)
const handleedit = async (type: any, row?: any) => {
    showedit.value = true
    await nextTick()
    editPopref.value?.open(type)
    if (row) {
        editPopref.value?.setFormData(row)
    }
}
const handlecolse = () => {
    showedit.value = false
    getLists()
}
//删除
const handledel = async (id: number) => {
    await feedback.confirm('确定删除？')
    await delSensitive({ id })
    getLists()
}

const getSensitive = async () => {
    formData.value = await getSensitiveConfig()
}

const handleSubmit = async () => {
    try {
        await setSensitiveConfig(formData.value)
    } finally {
        getSensitive()
    }
}
getSensitive()
</script>
