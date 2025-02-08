<template>
    <el-card class="!border-none flex-1" shadow="never">
        <el-tabs v-model="activeName" class="demo-tabs">
            <el-tab-pane label="服务协议" :name="1">
                <div class="">
                    <el-card class="!border-none flex-1 xl:mr-4 mb-4" shadow="never">
                        <!-- <template #header>
                            <span class="font-medium">服务协议</span>
                        </template> -->
                        <el-form :model="formData" label-width="80px">
                            <el-form-item label="协议名称">
                                <el-input v-model="formData.service_title" />
                            </el-form-item>
                        </el-form>

                        <editor
                            class="mb-10"
                            v-model="formData.service_content"
                            height="500"
                        ></editor>
                    </el-card>
                </div>
            </el-tab-pane>
            <el-tab-pane label="隐私协议" :name="2">
                <div class="">
                    <el-card class="!border-none flex-1 mb-4" shadow="never">
                        <!-- <template #header>
                            <span class="font-medium">隐私协议</span>
                        </template> -->
                        <el-form :model="formData" label-width="80px">
                            <el-form-item label="协议名称">
                                <el-input v-model="formData.privacy_title" />
                            </el-form-item>
                        </el-form>

                        <editor
                            class="mb-10"
                            v-model="formData.privacy_content"
                            height="500"
                        ></editor>
                    </el-card>
                </div>
            </el-tab-pane>
            <el-tab-pane label="使用协议" :name="3">
                <div class="">
                    <el-card class="!border-none flex-1 xl:mr-4 mb-4" shadow="never">
                        <!-- <template #header>
                            <span class="font-medium">使用协议</span>
                        </template> -->
                        <el-form :model="formData" label-width="80px">
                            <el-form-item label="协议名称">
                                <el-input v-model="formData.use_title" />
                            </el-form-item>
                        </el-form>

                        <editor class="mb-10" v-model="formData.use_content" height="500"></editor>
                    </el-card>
                </div>
            </el-tab-pane>
            <el-tab-pane label="充值协议" :name="4">
                <div class="">
                    <el-card class="!border-none flex-1 mb-4" shadow="never">
                        <!-- <template #header>
                            <span class="font-medium">充值协议</span>
                        </template> -->
                        <el-form :model="formData" label-width="80px">
                            <el-form-item label="协议名称">
                                <el-input v-model="formData.recharge_title" />
                            </el-form-item>
                        </el-form>

                        <editor
                            class="mb-10"
                            v-model="formData.recharge_content"
                            height="500"
                        ></editor>
                    </el-card>
                </div>
            </el-tab-pane>
            <el-tab-pane label="算力说明" :name="5">
                <div class="">
                    <el-card class="!border-none flex-1 xl:mr-4 mb-4" shadow="never">
                        <!-- <template #header>
                            <span class="font-medium">算力说明</span>
                        </template> -->
                        <el-form :model="formData" label-width="80px">
                            <el-form-item label="协议名称">
                                <el-input v-model="formData.currency_title" />
                            </el-form-item>
                        </el-form>

                        <editor
                            class="mb-10"
                            v-model="formData.currency_content"
                            height="500"
                        ></editor>
                    </el-card>
                </div>
            </el-tab-pane>
            <el-tab-pane label="免责声明" :name="6">
                <div class="">
                    <el-card class="!border-none flex-1 mb-4" shadow="never">
                        <!-- <template #header>
                            <span class="font-medium">免责声明</span>
                        </template> -->
                        <el-form :model="formData" label-width="80px">
                            <el-form-item label="协议名称">
                                <el-input v-model="formData.disclaimer_title" />
                            </el-form-item>
                        </el-form>

                        <editor
                            class="mb-10"
                            v-model="formData.disclaimer_content"
                            height="500"
                        ></editor>
                    </el-card>
                </div>
            </el-tab-pane>
        </el-tabs>
    </el-card>

    <footer-btns v-perms="['setting.web.web_setting/setAgreement']">
        <el-button type="primary" @click="handleProtocolEdit">保存</el-button>
    </footer-btns>
</template>

<script setup lang="ts" naem="webProtocol">
import { getProtocol, setProtocol } from '@/api/setting/website'
const activeName = ref(1)
interface formDataObj {
    service_title: string
    service_content: string
    privacy_title: string
    privacy_content: string
    use_title: string
    use_content: string
    recharge_title: string
    recharge_content: string
    currency_title: string
    currency_content: string
    disclaimer_title: string
    disclaimer_content: string
}
const formData = ref<formDataObj>({
    service_title: '',
    service_content: '',
    privacy_title: '',
    privacy_content: '',
    use_title: '',
    use_content: '',
    recharge_title: '',
    recharge_content: '',
    currency_title: '',
    currency_content: '',
    disclaimer_title: '',
    disclaimer_content: ''
})
const protocolGet = async () => {
    formData.value = await getProtocol()
}

const handleProtocolEdit = async (): Promise<void> => {
    await setProtocol({ ...formData.value })
    protocolGet()
}
protocolGet()
</script>
