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
                            使用声音克隆需开通克隆接口。
                            <a
                                href="https://www.hihookeji.com"
                                target="_blank"
                                rel=""
                                class="text-primary"
                            >
                                前往开通</a
                            >
                        </div>
                    </div>
                </el-form-item> -->
                <el-form-item label="计费公式" prop="clone_power">
                    <div class="flex flex-col">
                        <div class="flex items-center">
                            <el-input
                                class="w-[280px]"
                                type="number"
                                v-model.trim="formData.clone_power"
                                placeholder="请输入"
                            >
                                <template #append>算力/次</template>
                            </el-input>
                            <!-- <span class="mx-5">/</span>
                            <el-input
                                class="w-[200px]"
                                type="number"
                                v-model.trim="formData.video_power"
                                placeholder="算力消耗值"
                            >
                                <template #append>算力</template>
                            </el-input> -->
                        </div>
                        <div class="form-tips">克隆声音时，算力消耗的计费规则。</div>
                    </div>
                </el-form-item>
                <el-form-item label="文案配置" prop="voice_copy">
                    <el-input
                        disabled
                        class="w-[400px]"
                        v-model.trim="formData.voice_copy"
                        placeholder="请输入文案"
                        type="textarea"
                        :rows="3"
                    ></el-input>
                </el-form-item>
            </el-card>
        </el-form>
        <footer-btns v-perms="['setting.power.powerConfig/setVoiceCloneConfig']">
            <el-button type="primary" @click="handleSubmit">保存</el-button>
        </footer-btns>
    </div>
</template>

<script lang="ts" setup name="avaterConfig">
import type { FormInstance } from 'element-plus'

import { getVoiceCloneConfig, setVoiceCloneConfig } from '@/api/tool'

const formRef = ref<FormInstance>()

// 表单数据
const formData = reactive({
    clone_power: '',
    voice_copy: ''
})

// 表单验证
const rules = {
    clone_power: [
        {
            required: true,
            message: '请填写计费公式',
            trigger: ['blur']
        }
    ],
    voice_copy: [
        {
            required: true,
            message: '请输入声音录制文案',
            trigger: ['blur']
        }
    ]
}

const getData = async () => {
    const data = await getVoiceCloneConfig()
    for (const key in formData) {
        //@ts-ignore
        formData[key] = data[key]
    }
}

const handleSubmit = async () => {
    await formRef.value?.validate()
    await setVoiceCloneConfig(formData)
    getData()
}

getData()
</script>

<style lang="scss" scoped></style>
