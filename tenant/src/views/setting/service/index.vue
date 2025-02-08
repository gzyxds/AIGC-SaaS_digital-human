<template>
    <div>
        <el-form
            ref="formRef"
            :rules="rules"
            class="ls-form"
            :model="formData"
            label-width="120px"
            scroll-to-error
        >
            <el-card shadow="never" class="!border-none">
                <div class="text-xl font-medium mb-[20px]">人工客服</div>
                <el-form-item label="是否开启" prop="status">
                    <div>
                        <el-switch v-model="formData.status" :active-value="1" :inactive-value="0">
                        </el-switch>
                        <div class="form-tips">开启后，启用客服功能</div>
                    </div>
                </el-form-item>
                <el-form-item label="客服二维码">
                    <div>
                        <material-picker v-model="formData.qr_code" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
                <el-form-item label="客服标题">
                    <el-input
                        class="w-[280px]"
                        v-model.trim="formData.title.value"
                        placeholder="请输入客服标题"
                        maxlength="30"
                        show-word-limit
                    />
                    <el-checkbox
                        class="ml-4"
                        v-model="formData.title.status"
                        true-value="1"
                        false-value="0"
                        >显示</el-checkbox
                    >
                </el-form-item>
                <el-form-item label="服务时间">
                    <el-input
                        class="w-[280px]"
                        v-model.trim="formData.service_time.value"
                        placeholder="请输入服务时间"
                        maxlength="30"
                        show-word-limit
                    />
                    <el-checkbox
                        class="ml-4"
                        v-model="formData.service_time.status"
                        true-value="1"
                        false-value="0"
                        >显示</el-checkbox
                    >
                </el-form-item>
                <el-form-item label="联系电话">
                    <el-input
                        class="w-[280px]"
                        v-model.trim="formData.phone.value"
                        placeholder="请输入联系电话"
                        maxlength="30"
                        show-word-limit
                    />
                    <el-checkbox
                        class="ml-4"
                        v-model="formData.phone.status"
                        true-value="1"
                        false-value="0"
                        >显示</el-checkbox
                    >
                </el-form-item>
            </el-card>
        </el-form>
        <footer-btns v-perms="['setting.web.web_setting/setWebsite']">
            <el-button type="primary" @click="handleSubmit">保存</el-button>
        </footer-btns>
    </div>
</template>
<script lang="ts" setup name="webInformation">
import type { FormInstance } from 'element-plus'

import { getCustomer, setCustomer } from '@/api/setting/service'
import useAppStore from '@/stores/modules/app'

const formRef = ref<FormInstance>()

const appStore = useAppStore()
// 表单数据
const formData = reactive({
    status: 1,
    title: {
        status: '1',
        value: '我是客服'
    },
    service_time: {
        status: '1',
        value: '上午09:00-下午18:00'
    },
    phone: {
        status: '1',
        value: '021-008829474'
    },
    qr_code: ''
})

// 表单验证
const rules = {
    name: [
        {
            required: true,
            message: '请输入网站名称',
            trigger: ['blur']
        }
    ]
}

// 获取备案信息
const getData = async () => {
    const { manual_kf } = await getCustomer()
    for (const key in formData) {
        //@ts-ignore
        formData[key] = manual_kf[key]
    }
}

// 设置备案信息
const handleSubmit = async () => {
    await formRef.value?.validate()
    await setCustomer(formData)
    appStore.getConfig()
    getData()
}

getData()
</script>
