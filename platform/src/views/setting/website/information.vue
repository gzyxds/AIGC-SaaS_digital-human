<!-- 网站信息 -->
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
                <div class="text-xl font-medium mb-[20px]">后台设置</div>
                <el-form-item label="平台名称" prop="name">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.name"
                            placeholder="请输入网站名称"
                            maxlength="30"
                            show-word-limit
                        />
                    </div>
                </el-form-item>
                <el-form-item label="平台网站图标" prop="web_favicon" required>
                    <div>
                        <material-picker v-model="formData.web_favicon" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
                <el-form-item label="亮色主题LOGO" prop="web_logo_light" required>
                    <div>
                        <material-picker v-model.trim="formData.web_logo_light" :limit="1" />
                        <div class="form-tips">
                            建议尺寸：100*100像素，支持jpg，jpeg，png格式，将在后台亮色主题下显示
                        </div>
                    </div>
                </el-form-item>
                <el-form-item label="暗色主题LOGO" prop="web_logo_dark" required>
                    <div>
                        <material-picker v-model.trim="formData.web_logo_dark" :limit="1" />
                        <div class="form-tips">
                            建议尺寸：100*100像素，支持jpg，jpeg，png格式，将在后台暗色主题下显示
                        </div>
                    </div>
                </el-form-item>
                <el-form-item label="登录页配图" prop="login_image" required>
                    <div>
                        <material-picker v-model.trim="formData.login_image" :limit="1" />
                        <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
                    </div>
                </el-form-item>
            </el-card>
        </el-form>
        <footer-btns v-perms="['setting.api.api_config/setConfig']">
            <el-button type="primary" @click="handleSubmit">保存</el-button>
        </footer-btns>
    </div>
</template>

<script lang="ts" setup name="webInformation">
import type { FormInstance } from 'element-plus'

import { getWebsite, setWebsite } from '@/api/setting/website'
import useAppStore from '@/stores/modules/app'

const formRef = ref<FormInstance>()

const appStore = useAppStore()
// 表单数据
const formData = reactive({
    name: '', // 网站名称
    web_favicon: '', // 网站图标
    web_logo_light: '', // 网站亮色主题logo
    web_logo_dark: '', // 网站暗色主题logo
    login_image: '' // 登录页广告图
})

// 表单验证
const rules = {
    name: [
        {
            required: true,
            message: '请输入网站名称',
            trigger: ['blur']
        }
    ],
    web_favicon: [
        {
            required: true,
            message: '请选择网站图标',
            trigger: ['change']
        }
    ],
    web_logo: [
        {
            required: true,
            message: '请选择网站logo',
            trigger: ['change']
        }
    ],
    login_image: [
        {
            required: true,
            message: '请选择登录页广告图',
            trigger: ['change']
        }
    ]
}

// 获取备案信息
const getData = async () => {
    const data = await getWebsite()
    for (const key in formData) {
        //@ts-ignore
        formData[key] = data[key]
    }
}

// 设置备案信息
const handleSubmit = async () => {
    await formRef.value?.validate()
    await setWebsite(formData)
    appStore.getConfig()
    getData()
}

getData()
</script>

<style lang="scss" scoped></style>
