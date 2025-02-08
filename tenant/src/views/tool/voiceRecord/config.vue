<template>
    <div class="website-information">
        <el-form
            ref="formRef"
            :rules="rules"
            class="ls-form"
            :model="formData"
            label-width="120px"
            scroll-to-error
        >
            <el-card shadow="never" class="!border-none">
                <div class="text-xl font-medium mb-[20px]">计费配置</div>
                <!-- <el-form-item label="接口类型" prop="video_mode">
                    <div>
                        <el-radio-group v-model="formData.video_mode">
                            <el-radio :value="1">克隆接口</el-radio>
                        </el-radio-group>
                        <div class="form-tips">
                            使用声音合成需先开通声音合成接口，且需在「AI设置-》内容审核」开启「在线审核」。前往开通
                        </div>
                    </div>
                </el-form-item> -->
                <el-form-item label="计费公式" prop="voice_words">
                    <div class="flex flex-col">
                        <div class="flex items-center">
                            <el-input
                                class="w-[200px]"
                                type="number"
                                v-model.trim="formData.voice_power"
                                placeholder="算力消耗值"
                            >
                                <template #append>算力</template>
                            </el-input>
                            <span class="mx-5">/</span>
                            <el-input
                                class="w-[150px]"
                                type="number"
                                disabled
                                v-model.trim="formData.voice_words"
                                placeholder="单位（字数）"
                            >
                                <template #append>字</template>
                            </el-input>
                        </div>
                        <div class="form-tips">根据文本内容合成声音时，算力消耗的计费规则。</div>
                    </div>
                </el-form-item>
                <!-- <el-form-item label="文案配置" prop="voice_copy">
                    <el-input
                        class="w-[400px]"
                        v-model.trim="formData.voice_copy"
                        placeholder="请输入文案"
                        type="textarea"
                        :rows="3"
                    ></el-input>
                </el-form-item> -->
            </el-card>
        </el-form>
        <footer-btns v-perms="['setting.power.powerConfig/setVoiceConfig']">
            <el-button type="primary" @click="handleSubmit">保存</el-button>
        </footer-btns>
    </div>
</template>

<script lang="ts" setup name="avaterConfig">
import type { FormInstance } from 'element-plus'

import { getVoiceConfig, setVoiceConfig } from '@/api/tool'

const formRef = ref<FormInstance>()

// 表单数据
const formData = reactive({
    voice_power: '',
    voice_words: '',
    video_mode: 1,
    voice_copy: ''
})

// 表单验证
const rules = {
    voice_power: [
        {
            required: true,
            message: '请输入',
            trigger: ['blur']
        }
    ],
    voice_words: [
        {
            required: true,
            message: '请输入',
            trigger: ['blur']
        }
    ]
}

const getData = async () => {
    const data = await getVoiceConfig()
    for (const key in formData) {
        //@ts-ignore
        formData[key] = data[key]
    }
}

const handleSubmit = async () => {
    await formRef.value?.validate()
    await setVoiceConfig(formData)
    getData()
}

onMounted(() => {
    getData()
})
</script>

<style lang="scss" scoped></style>
