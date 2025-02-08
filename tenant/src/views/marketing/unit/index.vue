<template>
    <div class="">
        <el-form
            ref="formRef"
            :rules="rules"
            class="ls-form"
            :model="formData"
            label-width="120px"
            scroll-to-error
        >
            <el-card shadow="never" class="!border-none">
                <div class="text-xl font-medium mb-[20px]">算力单位配置</div>

                <el-form-item label="算力单位" prop="unit">
                    <div>
                        <el-input class="w-[200px]" v-model="formData.unit" placeholder="请输入">
                        </el-input>
                        <div class="form-tips">
                            自定义用户端显示的算力单位名称，当前默认名称为：算力，为空则显示默认名称。
                        </div>
                    </div>
                </el-form-item>
            </el-card>
        </el-form>
        <footer-btns v-perms="['marketing.marketingSetting/setMarketingCinfig']">
            <el-button type="primary" @click="handleSubmit">保存</el-button>
        </footer-btns>
    </div>
</template>
<script lang="ts" setup name="giftConfig">
import type { FormInstance } from 'element-plus'

import { getUnitConfig, setUnitConfig } from '@/api/marketing/unit'

const formRef = ref<FormInstance>()

const formData = reactive({
    unit: 0
})
const rules = {
    unit: [
        {
            required: true,
            message: '请输入单位名称',
            trigger: ['blur']
        }
    ]
}
const getData = async () => {
    const data = await getUnitConfig()
    for (const key in formData) {
        //@ts-ignore
        formData[key] = data[key]
    }
}

const handleSubmit = async () => {
    await formRef.value?.validate()
    await setUnitConfig(formData)
    getData()
}

getData()
</script>
