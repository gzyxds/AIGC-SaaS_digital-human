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
                <div class="text-xl font-medium mb-[20px]">充值引导</div>

                <el-form-item label="链接" prop="guide">
                    <div>
                        <el-input class="w-[400px]" v-model="formData.guide" placeholder="请输入">
                        </el-input>
                        <!-- <div class="form-tips">
                            新用户注册，免费赠送算力数量；填写0或者为空则表示不赠送
                        </div> -->
                    </div>
                </el-form-item>
                <el-form-item label="文案" prop="text">
                    <div>
                        <el-input class="w-[400px]" v-model="formData.text" placeholder="请输入">
                        </el-input>
                        <!-- <div class="form-tips">
                            新用户注册，免费赠送算力数量；填写0或者为空则表示不赠送
                        </div> -->
                    </div>
                </el-form-item>
            </el-card>
        </el-form>
        <footer-btns v-perms="['setting.web.guideSetting/setGuide']">
            <el-button type="primary" @click="handleSubmit">保存</el-button>
        </footer-btns>
    </div>
</template>
<script lang="ts" setup name="giftConfig">
import type { FormInstance } from 'element-plus'
import { ref, reactive } from 'vue'
import { getGuide, setGuide } from '@/api/marketing/guide'

const formRef = ref<FormInstance>()

const formData = reactive({
    guide: '',
    text: ''
})
const rules = {
    guide: [
        {
            required: true,
            message: '请输入',
            trigger: ['blur']
        }
    ]
}
const getData = async () => {
    const data = await getGuide()
    for (const key in formData) {
        //@ts-ignore
        formData[key] = data[key]
    }
}

const handleSubmit = async () => {
    await formRef.value?.validate()
    await setGuide(formData)
    getData()
}

getData()
</script>
