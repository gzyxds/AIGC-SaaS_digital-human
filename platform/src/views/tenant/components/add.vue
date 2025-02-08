<template>
    <el-drawer
        v-model="drawer"
        title="新增租户"
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
            <el-form-item label="租户头像：" prop="avatar">
                <material-picker v-model="formData.avatar" :limit="1" />
            </el-form-item>
            <el-form-item label="租户名称：" prop="name">
                <el-input
                    v-model="formData.name"
                    placeholder="请输入租户名称"
                    style="width: 280px"
                />
            </el-form-item>
            <el-form-item label="租户状态：" prop="disable">
                <el-radio-group v-model="formData.disable">
                    <el-radio :value="0">开启</el-radio>
                    <el-radio :value="1">关闭</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="联系方式：" prop="tel">
                <el-input
                    v-model="formData.tel"
                    :maxlength="30"
                    placeholder="请输入联系方式"
                    style="max-width: 280px"
                />
            </el-form-item>
            <!-- <el-form-item label="分表策略：" prop="tactics">
                <el-radio-group v-model="formData.tactics">
                    <el-radio :value="0">公表模式</el-radio>
                    <el-radio :value="1">分表模式</el-radio>
                </el-radio-group>
            </el-form-item> -->
            <el-form-item label="自定义主机名：" prop="host_name">
                <div class="w-full">
                    <el-input
                        v-model="formData.host_name"
                        placeholder="请输入主机名"
                        style="max-width: 280px"
                        :formatter="formatHostName"
                        :parser="parseHostName"
                    >
                        <template #append>.{{ getPrimaryDomain() }}</template>
                    </el-input>
                    <p class="text-info text-sm">
                        设置默认主机名，例：tenant001.{{ getPrimaryDomain() }}，不填则由系统随机生成
                    </p>
                </div>
            </el-form-item>
            <el-form-item label="租户自定义域名：" prop="domain_alias">
                <div class="w-full">
                    <el-input
                        v-model="formData.domain_alias"
                        placeholder="请输入租户自定义域名"
                        style="max-width: 280px"
                    />
                    <p class="text-info text-sm">
                        设置租户自定义域名，例如：https://www.example.com
                    </p>
                </div>
            </el-form-item>
            <el-form-item label="启用自定义域名：" prop="domain_alias_enable">
                <div>
                    <el-radio-group v-model="formData.domain_alias_enable">
                        <el-radio :value="0">启用</el-radio>
                        <el-radio :value="1">禁用</el-radio>
                    </el-radio-group>
                    <p class="text-info text-sm">
                        需将别名解析到默认域名并将别名配置到租户站点后生效
                    </p>
                </div>
            </el-form-item>
            <el-form-item label="租户备注：" prop="notes">
                <el-input
                    v-model="formData.notes"
                    placeholder="请输入租户备注"
                    style="max-width: 280px"
                    type="textarea"
                    :maxlength="100"
                />
            </el-form-item>
            <div class="form_header">
                账号信息
                <span class="text-xs font-normal text-info opacity-70">
                    （不填则生成默认账号，账号默认为sn编码，密码默认a123456）
                </span>
            </div>
            <el-form-item label="管理员账号：" prop="account">
                <el-input
                    v-model="formData.account"
                    placeholder="请输入管理员账号"
                    style="max-width: 280px"
                />
            </el-form-item>
            <el-form-item label="密码：" prop="password">
                <el-input
                    v-model="formData.password"
                    type="password"
                    placeholder="请输入密码"
                    style="max-width: 280px"
                />
            </el-form-item>
            <el-form-item label="确认密码：" prop="password_confirm">
                <el-input
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
    name: string
    sn: string
    id: string
    disable: number
    tel: string
    tactics: number
    domain_alias: string
    domain_alias_enable: number
    notes: string
    account: string
    password: string
    password_confirm: string
    host_name: string
}

const drawer = ref<boolean>(false)
const formRef = shallowRef<FormInstance>()
const submited = ref<boolean>(false)
const initForm: DetailType = {
    avatar: '',
    create_time: '',
    name: '',
    sn: '',
    id: '',
    disable: 0,
    tel: '',
    tactics: 0,
    domain_alias: '',
    domain_alias_enable: 1,
    notes: '',
    account: '',
    password: '',
    password_confirm: '',
    host_name: ''
}
const formData = ref<DetailType>(_.cloneDeep(initForm))

const rules: FormRules = {
    name: [
        {
            required: true,
            message: '请输入租户名称',
            trigger: ['blur']
        }
    ],
    disable: [
        {
            required: true,
            message: '请选择租户状态',
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

/**
 * 获取当前页面的一级域名
 * @returns {string} 一级域名
 */
function getPrimaryDomain(): string {
    const hostname = window.location.hostname // 当前页面的完整主机名
    const parts = hostname.split('.') // 根据点分隔主机名

    // 如果主机名是 IP 地址或本地环境（如 localhost）
    if (parts.length <= 2 || /^\d{1,3}(\.\d{1,3}){3}$/.test(hostname) || hostname === 'localhost') {
        return hostname // 返回完整主机名
    }

    // 获取一级域名，最后两部分
    return parts.slice(-2).join('.')
}

const afterClose = () => {
    formRef.value?.resetFields()
}

const submitAdd = async () => {
    await formRef.value?.validate()
    if (formData.value.password !== formData.value.password_confirm) {
        return ElMessage.error('两次密码输入不一致')
    }
    await userAdd(formData.value)
    submited.value = true
    drawer.value = false
    emits('refresh')
}

// 格式化输入为合法的主机名格式
const formatHostName = (value: string): string => {
    if (!value) return ''
    return value
        .split('.')
        .map(
            (segment) => segment.replace(/[^a-zA-Z0-9-]/g, '').slice(0, 32) // 去除非法字符并限制长度
        )
        .filter((segment) => segment && !/^-|-$/.test(segment)) // 去除开头或结尾是 "-" 的部分
        .join('.') // 用 "." 连接有效段
}

// 解析输入并清理为基本主机名
const parseHostName = (value: string): string => {
    if (!value) return ''
    return value
        .replace(/\s+/g, '') // 去除空格
        .replace(/[^a-zA-Z0-9-.]/g, '') // 仅保留合法字符
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
:deep(.el-form-item__content .el-input-group) {
    .el-input-group__append,
    .el-input-group__prepend {
        @apply px-3;
    }
}
</style>
