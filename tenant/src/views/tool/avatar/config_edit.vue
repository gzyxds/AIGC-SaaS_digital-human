<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            title="通道设置"
            :async="true"
            width="550px"
            @confirm="handleSubmit"
            @close="handleClose"
        >
            <el-form ref="formRef" :model="formData" label-width="84px" :rules="formRules">
                <el-form-item label="自定义名称" prop="video_mode_title">
                    <el-input
                        v-model="formData.video_mode_title"
                        placeholder="请输入自定义名称"
                        clearable
                    />
                    <div class="form-tips">用户选择时显示的通道名称，为空则显示合同通道名称</div>
                </el-form-item>
                <el-form-item label="消耗算力" prop="video_power">
                    <div class="flex">
                        <el-input
                            class="w-[150px]"
                            type="number"
                            v-model.trim="formData.video_power"
                            placeholder="算力消耗值"
                        >
                            <template #append>算力</template>
                        </el-input>
                        <span class="mx-5">/</span>
                        <el-input
                            class="w-[150px]"
                            type="number"
                            disabled
                            v-model.trim="formData.video_time"
                            placeholder="单位（分钟）"
                        >
                            <template #append>分钟</template>
                        </el-input>
                    </div>
                    <div class="form-tips">合成数字人视频时，算力消耗的计费规则。</div>
                </el-form-item>
                <el-form-item label="通道说明" prop="video_mode_title">
                    <span>
                        {{
                            {
                                1: '创客API极速模式，合成速度快',
                                2: '创客API高清模式，合成效果好',
                                3: '创客API高清模式，合成效果好'
                            }[formData.mode as number]
                        }}
                    </span>
                </el-form-item>
                <el-form-item label="状态" prop="video_mode_status">
                    <el-switch
                        v-model="formData.video_mode_status"
                        :active-value="1"
                        :inactive-value="0"
                    />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup name="articleColumnEdit">
import type { FormInstance } from 'element-plus'

import { getAvatarConfig, setAvatarConfig } from '@/api/tool'
import Popup from '@/components/popup/index.vue'

const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()

const formData = reactive({
    mode: 1,
    video_mode_status: 1,
    video_mode_title: '创客API-极速模式',
    video_power: 0,
    video_time: 1
})

const formRules = {
    video_power: [
        {
            required: true,
            message: '请输入算力消耗值',
            trigger: ['blur']
        }
    ]
}

const handleSubmit = async () => {
    await formRef.value?.validate()
    await setAvatarConfig(formData)
    popupRef.value?.close()
    emit('success')
}

const open = () => {
    popupRef.value?.open()
}

const setFormData = (data: Record<any, any>) => {
    for (const key in formData) {
        if (data[key] != null && data[key] != undefined) {
            //@ts-ignore
            formData[key] = data[key]
        }
    }
}

const getDetail = async (row: Record<string, any>) => {
    const data = await getAvatarConfig({
        mode: row.mode
    })
    formData.mode = row.mode
    setFormData(data)
}

const handleClose = () => {
    emit('close')
}

defineExpose({
    open,
    setFormData,
    getDetail
})
</script>
