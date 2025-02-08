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
                <div class="text-xl font-medium mb-[20px]">注册赠送</div>
                <el-form-item label="功能状态" prop="gift_switch">
                    <div>
                        <el-switch
                            v-model="formData.gift_switch"
                            :active-value="1"
                            :inactive-value="0"
                        >
                        </el-switch>
                        <div class="form-tips">关闭后，新用户注册将不赠送免费次数</div>
                    </div>
                </el-form-item>
                <el-form-item label="赠送算力" prop="gift_amount">
                    <div>
                        <el-input
                            class="w-[200px]"
                            type="number"
                            v-model.trim="formData.gift_amount"
                            placeholder="请输入"
                        >
                            <template #append>算力</template>
                        </el-input>
                        <div class="form-tips">
                            新用户注册，免费赠送算力数量；填写0或者为空则表示不赠送
                        </div>
                    </div>
                </el-form-item>
            </el-card>
        </el-form>
        <footer-btns v-perms="['marketing.marketingSetting/setGiftConfig']">
            <el-button type="primary" @click="handleSubmit">保存</el-button>
        </footer-btns>
    </div>
</template>
<script lang="ts" setup name="giftConfig">
import type { FormInstance } from 'element-plus'

import { getGiftConfig, setGiftConfig } from '@/api/marketing/gift'

const formRef = ref<FormInstance>()

const formData = reactive({
    gift_switch: 0,
    gift_amount: 0
})
const rules = {}
const getData = async () => {
    const data = await getGiftConfig()
    for (const key in formData) {
        //@ts-ignore
        formData[key] = data[key]
    }
}

const handleSubmit = async () => {
    await formRef.value?.validate()
    await setGiftConfig(formData)
    getData()
}

getData()
</script>
