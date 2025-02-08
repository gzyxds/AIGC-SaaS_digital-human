<template>
    <div class="edit-popup">
        <popup ref="popupRef" title="用户详情" :async="true" width="550px">
            <div
                v-loading="loading"
                element-loading-text="加载中..."
                element-loading-background="var(--el-bg-color)"
                :data="formData.avatar"
            >
                <el-descriptions :column="1">
                    <el-descriptions-item label="头像：">
                        <el-avatar v-if="formData.avatar" :src="formData.avatar" :size="70" />
                        <el-avatar v-else :size="70"> {{ formData.nickname || '--' }} </el-avatar>
                    </el-descriptions-item>
                    <el-descriptions-item label="昵称：">
                        {{ formData.nickname || '--' }}
                        <el-icon v-if="formData.sexCode == 1" color="bule"><Male /></el-icon>
                        <el-icon v-if="formData.sexCode == 2" color="red"><Female /></el-icon>
                    </el-descriptions-item>
                    <el-descriptions-item label="账号：">
                        {{ formData.account || '--' }}
                    </el-descriptions-item>
                    <el-descriptions-item label="状态：">
                        <el-tag :type="formData.is_disable === 0 ? 'primary' : 'danger'">
                            {{ formData.is_disable === 0 ? '开启' : '关闭' }}
                        </el-tag>
                    </el-descriptions-item>
                    <el-descriptions-item :width="140" label="手机号：">
                        {{ formData.mobile || '--' }}
                    </el-descriptions-item>
                    <el-descriptions-item label="来源渠道：">
                        {{ formData.channel || '--' }}
                    </el-descriptions-item>
                    <el-descriptions-item label="真实姓名：">
                        {{ formData.real_name || '--' }}
                    </el-descriptions-item>
                    <el-descriptions-item label="创建时间：">
                        {{ formData.create_time || '--' }}
                    </el-descriptions-item>
                    <el-descriptions-item v-if="formData.login_time" label="最近活跃：">
                        {{ formData.login_time }}
                    </el-descriptions-item>
                </el-descriptions>
            </div>
        </popup>
    </div>
</template>

<script lang="ts" setup>
import { getTenantUserDetail } from '@/api/consumer'
import Popup from '@/components/popup/index.vue'

const props = defineProps({
    tenant_id: {
        type: Number,
        default: 0
    }
})
const popupRef = shallowRef<InstanceType<typeof Popup>>()

interface FormDataType {
    id: number
    sn: number
    account: string
    nickname: string
    avatar: string
    real_name: string
    sex: string
    mobile: string
    create_time: number
    login_time: string
    channel: string
    user_money: string
    is_disable: number
    sexCode: number
}

const formData = ref<FormDataType>({
    id: 0,
    sn: 0,
    account: '',
    nickname: '',
    avatar: '',
    real_name: '',
    sex: '',
    mobile: '',
    create_time: 0,
    login_time: '',
    channel: '',
    user_money: '',
    is_disable: 1,
    sexCode: 1
})

const loading = ref<boolean>(true)

const open = (id: number) => {
    loading.value = true
    setFormData(id)
    popupRef.value?.open()
}

const setFormData = async (id: number) => {
    const data: FormDataType = await getTenantUserDetail({
        id: id,
        tenant_id: props.tenant_id
    })
    formData.value = data
    loading.value = false
}

defineExpose({
    open,
    setFormData
})
</script>

<style lang="scss" scoped>
:deep(.el-descriptions__label) {
    display: inline-block;
    min-width: 5rem;
    text-align: right;
}
</style>
