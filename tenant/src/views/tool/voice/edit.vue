<template>
    <el-drawer v-model="drawer" destroy-on-close title="详情" direction="rtl" size="50%">
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
                <!-- <el-form-item label="金额" prop="cost">
                    <el-input
                        v-model="formData.cost"
                        placeholder="请输入充值金额"
                        style="max-width: 250px"
                        :maxlength="100"
                    />
                </el-form-item> -->
                <!-- <el-form-item label="算力数" prop="power">
                    <el-input
                        v-model="formData.power"
                        placeholder="请输入算力数"
                        style="max-width: 250px"
                        :maxlength="100"
                    />
                </el-form-item> -->
                <el-form-item label="状态" prop="status">
                    <el-switch v-model="formData.status" active-value="1" inactive-value="0">
                    </el-switch>
                </el-form-item>
            </el-form>
        </div>
    </el-drawer>
</template>

<script lang="ts" setup>
import type { FormInstance, FormRules } from 'element-plus'

import { getAvatarvoiceDetail } from '@/api/tool'

interface DetailType {
    cost: string
    power: string
    status: string
}

const drawer = ref(false)
const formRef = shallowRef<FormInstance>()

const editStatus = ref<boolean>(true)
const loading = ref<boolean>(false)
const formData = ref<DetailType>({
    cost: '',
    power: '',
    status: '1'
})

const formRules: FormRules = {
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
// const emits = defineEmits(['refresh'])
const getDetails = async (id: number) => {
    loading.value = true
    const data: DetailType = await getAvatarvoiceDetail({
        id: id
    })
    formData.value = data
    loading.value = false
}
const openHandle = (id: number) => {
    drawer.value = true

    getDetails(id)
}

// const beforeClose = (done: () => void) => {
//     if (editStatus.value) {
//         ElMessageBox.confirm('修改还未保存，确认退出编辑吗？')
//             .then(() => {
//                 done()
//             })
//             .catch(() => {
//                 console.log('取消')
//             })
//     } else {
//         done()
//     }
// }

// const afterClose = () => {
//     formRef.value?.resetFields()
// }

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
