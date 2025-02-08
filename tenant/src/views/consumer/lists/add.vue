<template>
    <el-drawer
        v-model="drawer"
        title="新增用户"
        direction="rtl"
        size="50%"
        :destroy-on-close="true"
        :before-close="beforeClose"
        @close="afterClose"
    >
        <el-form
            ref="formRef"
            class="ls-form"
            label-position="right"
            :model="formData"
            :rules="rules"
            label-width="130px"
        >
            <div class="form_header">基础信息</div>
            <el-form-item label="用户头像：" prop="avatar">
                <material-picker v-model="formData.avatar" :limit="1" />
            </el-form-item>
            <el-form-item label="用户昵称：" prop="nickname">
                <div>
                    <el-input
                        v-model="formData.nickname"
                        placeholder="请输入用户昵称"
                        style="width: 280px"
                    />
                    <div class="form-tips">Tips：不填则由系统默认生成</div>
                </div>
            </el-form-item>
            <el-form-item label="用户状态：" prop="is_disable">
                <el-radio-group v-model="formData.is_disable">
                    <el-radio :value="0">开启</el-radio>
                    <el-radio :value="1">关闭</el-radio>
                </el-radio-group>
            </el-form-item>
            <div class="form_header">账号信息</div>
            <el-form-item label="账号：" prop="account">
                <el-input
                    autocomplete="off"
                    v-model="formData.account"
                    placeholder="请输入账号"
                    style="max-width: 280px"
                />
            </el-form-item>
            <el-form-item label="绑定手机号：" prop="mobile">
                <el-input
                    name="mobile"
                    autocomplete="off"
                    v-model="formData.mobile"
                    :maxlength="30"
                    placeholder="请输入手机号"
                    style="max-width: 280px"
                />
            </el-form-item>
            <el-form-item label="密码：" prop="password">
                <el-input
                    name="password"
                    autocomplete="off"
                    v-model="formData.password"
                    type="password"
                    placeholder="请输入密码"
                    style="max-width: 280px"
                />
            </el-form-item>
            <el-form-item label="确认密码：" prop="password_confirm">
                <el-input
                    name="password_confirm"
                    autocomplete="off"
                    v-model="formData.password_confirm"
                    type="password"
                    placeholder="请再次输入密码"
                    style="max-width: 280px"
                />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" :loading="isLock" @click="lockSubmit">提交</el-button>
            </el-form-item>
        </el-form>
    </el-drawer>
</template>

<script lang="ts" setup>
import { ElMessage, type FormInstance, type FormRules } from 'element-plus'
import * as _ from 'lodash-es'

import { userAdd } from '@/api/consumer'
import { useLockFn } from '@/hooks/useLockFn'

interface DetailType {
    avatar: string
    create_time: string
    nickname: string
    is_disable: number
    mobile: string | number
    account: string
    password: string
    password_confirm: string
}

const drawer = ref<boolean>(false)
const formRef = shallowRef<FormInstance>()
const submited = ref<boolean>(false)
const initForm: DetailType = {
    avatar: '',
    create_time: '',
    nickname: '',
    is_disable: 0,
    mobile: '',
    account: '',
    password: '',
    password_confirm: ''
}
const formData = ref<DetailType>(_.cloneDeep(initForm))

const rules: FormRules = {
    account: [
        {
            required: true,
            message: '请填写用户账号',
            trigger: ['blur']
        },
        {
            min: 8,
            max: 11,
            message: '用户账号长度必须在8-20位之间',
            trigger: ['blur']
        }
    ],
    password: [
        {
            validator: (rule, value, callback) => {
                if (!value && formData.value.password_confirm) {
                    callback(new Error('请输入密码'))
                } else if (value !== formData.value.password) {
                    callback(new Error('两次输入的密码不一致'))
                } else {
                    callback()
                }
            },
            trigger: ['blur']
        }
    ],
    password_confirm: [
        {
            validator: (rule, value, callback) => {
                if (!value && formData.value.password) {
                    callback(new Error('请再次输入密码'))
                } else if (value !== formData.value.password) {
                    callback(new Error('两次输入的密码不一致'))
                } else {
                    callback()
                }
            },
            trigger: ['blur']
        }
    ]
}

const emits = defineEmits(['refresh'])

const openHandle = () => {
    submited.value = false
    drawer.value = true
}

const beforeClose = (done: () => void) => {
    if (submited.value) done()
    if (_.isEqual(formData.value, initForm)) {
        done()
    } else {
        ElMessageBox.confirm('表单未提交，确认退出编辑吗？')
            .then(() => {
                done()
            })
            .catch(() => {})
    }
}

const afterClose = () => {
    formRef.value?.resetFields()
}

const submitAdd = async () => {
    await formRef.value?.validate()
    if (formData.value.password !== formData.value.password_confirm) {
        return ElMessage.error('两次密填写不一致')
    }
    await userAdd(formData.value)
    submited.value = true
    drawer.value = false
    emits('refresh')
}

const { isLock, lockFn: lockSubmit } = useLockFn(submitAdd)

defineExpose({
    openHandle
})
</script>

<style lang="scss" scoped>
.form_header {
    @apply text-lg font-bold mb-4 p-2 rounded;
    background-color: rgba(204, 204, 204, 0.2);
}
</style>
