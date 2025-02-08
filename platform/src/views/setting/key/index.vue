<template>
    <div class="storage">
        <el-card class="!border-none" shadow="never">
            <el-alert type="warning" title="温馨提示：" :closable="false" show-icon>
                <template #default>
                    <div>
                        1.请前往<el-link
                            type="warning"
                            href="https://www.hihookeji.com/"
                            icon="Link"
                            style="font-weight: 900; margin-bottom: 1.7%"
                            >创客平台</el-link
                        >配置对应用户专属密钥
                    </div>
                </template>
            </el-alert>
        </el-card>
        <el-form ref="formRef" :rules="rules" :model="formData" label-width="120px" scroll-to-error>
            <el-card class="!border-none mt-4" shadow="never">
                <div class="text-xl font-medium mb-[20px]">创客密钥设置</div>
                <el-form-item label="appkey" prop="key">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.key"
                            placeholder="请输入appkey"
                            maxlength="30"
                            show-word-limit
                        />
                    </div>
                </el-form-item>
                <el-form-item label="开启加签" prop="sign" required>
                    <el-radio-group v-model="formData.sign">
                        <el-radio :value="0">关闭</el-radio>
                        <el-radio :value="1">开启</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="私钥sk" prop="sk">
                    <div class="w-80">
                        <el-input
                            v-model.trim="formData.sk"
                            placeholder="请填写私钥sk"
                            maxlength="30"
                            show-word-limit
                        />
                    </div>
                </el-form-item>
                <div style="display: flex; justify-content: center; margin-top: 20px">
                    <el-button type="primary" @click="handleSubmit">保存</el-button>
                </div>
                <!-- <el-button
                    style="text-align: center; margin-top: 20px"
                    type="primary"
                    @click="handleSubmit"
                    >保存</el-button
                > -->
            </el-card>
        </el-form>
    </div>
</template>
<script lang="ts" setup name="storage">
import { getKeyConfig } from '@/api/setting/key'
import { setKeyConfig } from '@/api/setting/key'

const formRef = ref<FormInstance>()

// 表单数据
const formData = reactive({
    key: '', // 配置的接口请求调用appkey
    sign: '', // 是否开启加签校验
    sk: '' // 配置的接口请求调用app私钥sk
})

// 表单验证
const rules = {
    key: [
        {
            required: true,
            message: '请输入appkey',
            trigger: ['blur']
        }
    ],
    sign: [
        {
            required: true,
            message: '请选择网站图标',
            trigger: ['change']
        }
    ]
}

// 获取存储引擎列表数据
const getConfig = async () => {
    try {
        const formData = await getKeyConfig()
        for (const key in formData) {
            formData[key] = data[key]
        }
    } catch (error) {
        console.log('获取内容失败:'.error)
    }
}

const handleSubmit = async () => {
    await formRef.value?.validate()
    await setKeyConfig(formData)
    // appStore.getConfig()
    getConfig()
}

getConfig()
</script>
