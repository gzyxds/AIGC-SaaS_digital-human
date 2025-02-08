<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            :title="popupTitle"
            :async="true"
            width="550px"
            @confirm="handleSubmit"
        >
            <el-form
                class="ls-form"
                ref="formRef"
                :rules="rules"
                :model="formData"
                label-width="120px"
            >
                <!-- <el-form-item label="接口类型" prop="channel"> -->
                <!-- v-if="!isChatModel" -->
                <!-- <el-select class="w-[330px]" v-model="formData.channel" style="width: 330px">
                        <el-option
                            v-for="item in aiModelList"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        />
                    </el-select> -->
                <!-- <el-select
                        v-else
                        class="w-[330px]"
                        v-model="formData.model_id"
                        @change="changeModel"
                    >
                        <el-option
                            v-for="item in aiModelList"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        />
                    </el-select> -->
                <!-- </el-form-item> -->

                <el-form-item label="APIKey" prop="key">
                    <div>
                        <el-input
                            class="w-[330px]"
                            v-model="formData.key"
                            placeholder="请输入APIKey"
                            clearable
                        />
                        <!-- <div class="form-tips">完成实名认证</div> -->
                        <div class="form-tips">
                            创客APIKey
                            <a
                                href="https://api.hihookeji.com"
                                target="_blank"
                                rel=""
                                class="text-primary"
                            >
                                申请入口</a
                            >
                        </div>
                    </div>
                </el-form-item>
                <el-form-item label="备注">
                    <el-input
                        class="w-[330px]"
                        type="textarea"
                        :rows="4"
                        v-model="formData.remark"
                        placeholder="请输入备注内容"
                        clearable
                    />
                </el-form-item>
                <el-form-item label="状态">
                    <el-switch v-model="formData.status" :active-value="1" :inactive-value="0" />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>
<script lang="ts" setup>
import type { FormInstance } from 'element-plus'

import { addKey, editKey, getdetail } from '@/api/ai_setting/key_setting'
import Popup from '@/components/popup/index.vue'
import feedback from '@/utils/feedback'

const emit = defineEmits(['success'])
//表单ref
const formRef = shallowRef<FormInstance>()
//弹框ref
const popupRef = shallowRef<InstanceType<typeof Popup>>()
//弹框标题
const popupTitle = ref('')

//表单数据
const formData: any = ref({
    id: '',
    type: '',
    key: '',
    remark: '',
    status: 1
})
// const isChatModel = computed(() => [1, 2].includes(Number(formData.value.type)))
// const changeModel = (id: number) => {
//     const current = aiModelList.value.find((item) => item.id === id)
//     if (current) {
//         formData.value.channel = current.channel
//     }
// }
// const getFieldKey = computed(() => {
//     switch (formData.value.channel) {
//         case 'hunyuan':
//             return {
//                 key: 'SecretKey',
//                 secret: 'SecretId'
//             }
//         case 'minimax':
//             return {
//                 key: '密钥',
//                 secret: 'SecretId'
//             }
//         case 'kdxf': {
//             if ([4].includes(formData.value.type)) {
//                 return {
//                     key: 'SecretKey'
//                 }
//             } else {
//                 return {
//                     key: 'APIKey',
//                     secret: 'APISecret'
//                 }
//             }
//         }
//         case 'chat_ppt':
//             return {
//                 key: 'token'
//             }
//         case 'tiangong':
//             return {
//                 key: 'APPKey',
//                 secret: 'APPSecret'
//             }
//         case 'doubao':
//             return {
//                 key: 'Access Key',
//                 secret: 'Secret Access'
//             }
//         default:
//             return {
//                 key: 'APIKey',
//                 secret: 'APISecret'
//             }
//     }
// })
//表单校验规则
const rules = {
    // channel: [
    //     {
    //         required: true,
    //         message: '请选择接口类型',
    //         trigger: ['blur']
    //     }
    // ],
    key: [
        {
            required: true,
            message: '请输入APIKey',
            trigger: ['blur']
        }
    ]
}

//提交表单
const handleSubmit = async () => {
    try {
        await formRef.value?.validate()
        if (formData.value.id == '') await addKey(formData.value)
        else if (formData.value.id != '') await editKey(formData.value)
        feedback.msgSuccess('操作成功')
        emit('success')
        popupRef.value?.close()
    } catch (error) {
        return error
    }
}

//打开弹框
const open = async (type: number, mode: string, value: any) => {
    //初始化数据
    if (mode == 'add') {
        formData.value = {
            id: '',
            type,
            key: '',
            remark: '',
            status: 1
        }
        popupTitle.value = '新增密钥'
    } else if (mode == 'edit') {
        const data = await getdetail({ id: value.id })
        Object.keys(data).map((item) => {
            formData.value[item] = data[item] ?? ''
        })
        formData.value.type = type
        formData.value.channel = 1
        popupTitle.value = '编辑密钥'
    }
    popupRef.value?.open()
    // getAiModelList(type)
}

// const getAiModelList = async (type: number) => {
//     try {
//         const data = await getmoduleList({
//             type: type
//         })
//         aiModelList.value = data
//     } catch (error) {
//         console.log(error)
//     }
// }

defineExpose({
    open
})
</script>
