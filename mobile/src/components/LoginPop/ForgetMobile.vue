<script setup lang="ts">
import { apiPostResetPassword, apiPostSendCode } from '@/api/user';
import { CodeTypeEnum } from '@/enums/variableEnum';

const emits = defineEmits(['forget']);

// 倒计时开关
const countdownSwitch = ref(false);

// 表单Ref
const formRef = ref();

// 表单数据
const formData = ref({
    mobile: '',
    password: '',
    password_confirm: '',
    code: '',
});

// 表单校验规则
const rules = {
    mobile: {
        rules: [
            {
                required: true,
                errorMessage: '请输入手机号',
            },
        ],
    },
    password: {
        rules: [
            {
                required: true,
                errorMessage: '请输入新密码',
            },
        ],
    },
    password_confirm: {
        rules: [
            {
                required: true,
                errorMessage: '请再次输入新密码',
            },
        ],
    },
    code: {
        rules: [
            {
                required: true,
                errorMessage: '请输入验证码',
            },
        ],
    },
};

// 获取验证码
const handleCode = () => {
    if (!formData.value.mobile) {
        useToast('请输入手机号');
        return;
    }
    countdownSwitch.value = true;
    apiPostSendCode({
        mobile: formData.value.mobile,
        scene: CodeTypeEnum.FIND_PASSWORD,
    });
    console.log('发送获取验证码请求');
};

// 忘记密码
const handleConfirm = async () => {
    const res = await formRef.value.validate();
    console.log('表单校验通过', res);

    if (formData.value.password !== formData.value.password_confirm) {
        useToast('两次输入的密码不一致');
        return;
    }

    await apiPostResetPassword(formData.value);
    emits('forget');
};
</script>

<template>
    <view w-full>
        <uni-forms ref="formRef" :model-value="formData" :rules="rules">
            <uni-forms-item label="" name="mobile">
                <uni-easyinput
                    v-model="formData.mobile"
                    prefix-icon="person"
                    placeholder="请输入手机号"
                />
            </uni-forms-item>

            <!-- 验证码 -->
            <uni-forms-item label="" name="code">
                <uni-easyinput
                    v-model="formData.code"
                    prefix-icon="locked"
                    placeholder="请输入验证码"
                >
                    <template #right>
                        <VerificationCode
                            v-model:countdown-switch="countdownSwitch"
                            :second="59"
                            @handle-code="handleCode"
                        />
                    </template>
                </uni-easyinput>
            </uni-forms-item>

            <!-- 新密码 -->
            <uni-forms-item label="" name="password">
                <uni-easyinput
                    v-model="formData.password"
                    type="password"
                    prefix-icon="locked"
                    placeholder="请输入新密码"
                />
            </uni-forms-item>

            <!-- 确认新密码 -->
            <uni-forms-item label="" name="confirm_password">
                <uni-easyinput
                    v-model="formData.password_confirm"
                    type="password"
                    prefix-icon="locked"
                    placeholder="请再次输入新密码"
                />
            </uni-forms-item>
        </uni-forms>

        <view mt-4 between gap-4>
            <button type="default" @click="$emit('forget')">返回登录</button>
            <button type="primary" @click="handleConfirm">确定修改</button>
        </view>
    </view>
</template>

<style lang="scss" scoped></style>
