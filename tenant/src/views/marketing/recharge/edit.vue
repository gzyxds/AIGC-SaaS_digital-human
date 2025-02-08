<template>
    <el-drawer
        v-model="drawer"
        destroy-on-close
        :title="mode == 'edit' ? '编辑套餐' : '新增套餐'"
        direction="rtl"
        size="50%"
        @close="afterClose"
        :before-close="beforeClose"
    >
        <div
            class="h-full flex flex-col"
            v-loading="loading"
            element-loading-text="加载中..."
            element-loading-background="var(--el-bg-color)"
        >
            <el-form
                ref="formRef"
                class="profile grid grid-cols-2 gap-x-4 pt-2"
                :class="{
                    '!grid-cols-1': editStatus
                }"
                label-position="right"
                :model="formData"
                label-width="100px"
                :rules="formRules"
            >
                <el-form-item label="套餐名称" prop="title">
                    <el-input
                        v-model="formData.title"
                        placeholder="请输入套餐名称"
                        style="max-width: 250px"
                        :maxlength="100"
                    />
                </el-form-item>
                <el-form-item label="套餐价格" prop="cost">
                    <el-input
                        v-model="formData.cost"
                        placeholder="请输入套餐价格"
                        style="max-width: 250px"
                        :maxlength="100"
                    >
                        <template #append>元</template>
                    </el-input>
                </el-form-item>
                <el-form-item label="原价" prop="original_cost">
                    <el-input
                        v-model="formData.original_cost"
                        placeholder="请输入原价"
                        style="max-width: 250px"
                        :maxlength="100"
                    />
                </el-form-item>
                <el-form-item label="套餐标签" prop="note">
                    <el-input
                        v-model="formData.note"
                        placeholder="请输入标签"
                        style="max-width: 250px"
                        :maxlength="100"
                    />
                </el-form-item>
                <el-form-item label="排序" prop="sort">
                    <div>
                        <el-input
                            v-model="formData.sort"
                            placeholder="请输入"
                            style="max-width: 250px"
                            :maxlength="100"
                        />
                        <div class="form-tips">默认为0，排序值越大越排前面</div>
                    </div>
                </el-form-item>

                <el-form-item label="套餐状态" prop="status">
                    <div>
                        <el-switch v-model="formData.status" :active-value="1" :inactive-value="0">
                        </el-switch>
                        <div class="form-tips">默认为开启</div>
                    </div>
                </el-form-item>
                <el-form-item label="算力数量" prop="power">
                    <el-input
                        v-model="formData.power"
                        placeholder="请输入算力数量"
                        style="max-width: 250px"
                        :maxlength="100"
                    >
                        <template #append>算力</template>
                    </el-input>
                </el-form-item>
                <el-form-item label="开启赠送" prop="gift">
                    <el-switch v-model="formData.gift" :active-value="1" :inactive-value="0">
                    </el-switch>
                </el-form-item>
                <el-form-item label="赠送数量" prop="gift_power">
                    <el-input
                        v-model="formData.gift_power"
                        placeholder="请输入赠送数量"
                        style="max-width: 250px"
                        :maxlength="100"
                    >
                        <template #append>算力</template>
                    </el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" :loading="isLock" @click="lockSubmit">
                        保存
                    </el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-drawer>
</template>

<script lang="ts" setup>
import type { FormInstance, FormRules } from 'element-plus'
import { cloneDeep } from 'lodash-es'
import * as _ from 'lodash-es'

import { addpowerPackage, editpowerPackage, getpowerPackageDetail } from '@/api/marketing/recharge'
import { useLockFn } from '@/hooks/useLockFn'

interface DetailType {
    title: string
    cost: string
    power: string
    status: number
    original_cost: string
    note: string
    sort: number
    gift: number
    gift_power: string
}

const drawer = ref(false)
const formRef = shallowRef<FormInstance>()

const editStatus = ref<boolean>(true)
const loading = ref<boolean>(false)

const initData = ref<DetailType>()

const formData = ref<DetailType>({
    title: '',
    cost: '',
    power: '',
    status: 1,
    original_cost: '',
    note: '',
    sort: 0,
    gift: 0,
    gift_power: ''
})

const formRules: FormRules = {
    title: [
        {
            required: true,
            message: '请输入套餐名称',
            trigger: ['blur']
        }
    ],
    cost: [
        {
            required: true,
            message: '请输入充值金额',
            trigger: ['blur']
        }
    ],
    power: [
        {
            required: true,
            message: '请输入算力数',
            trigger: ['blur']
        }
    ]
}
const mode = ref<string>('')
const emits = defineEmits(['refresh'])
const getDetails = async (id: number) => {
    loading.value = true
    const data: DetailType = await getpowerPackageDetail({
        id: id
    })
    formData.value = data
    initData.value = cloneDeep(data)
    loading.value = false
}
const openHandle = (modevalue: string, id?: number) => {
    drawer.value = true
    mode.value = modevalue
    if (modevalue == 'edit' && id) {
        getDetails(id)
    }
}

const beforeClose = (done: () => void) => {
    if (_.isEqual(initData.value, formData.value)) {
        done()
    } else {
        ElMessageBox.confirm('修改还未保存，确认退出编辑吗？')
            .then(() => {
                done()
            })
            .catch(() => {})
    }
}

const afterClose = () => {
    formRef.value?.resetFields()
}

const submitEdit = async () => {
    await formRef.value?.validate()
    const api = mode.value == 'edit' ? editpowerPackage : addpowerPackage
    await api(formData.value)
    drawer.value = false
    emits('refresh')
}

const { isLock, lockFn: lockSubmit } = useLockFn(submitEdit)

defineExpose({
    openHandle
})
</script>

<style lang="scss" scoped>
:deep(.el-tabs__content) {
    flex: 1;

    .el-tab-pane {
        height: 100%;
    }
}

.profile {
    :deep(.el-form-item__content) {
        align-items: center;
    }
}
</style>
