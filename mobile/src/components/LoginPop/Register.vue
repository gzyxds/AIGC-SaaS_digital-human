<script setup lang="ts">
import { apiPostAccountRegister, apiPostSendCode } from '@/api/user';
import { CodeTypeEnum } from '@/enums/variableEnum';

const props = defineProps({
    type: {
        type: String,
        default: RegisterWayEnum.ACCOUNT_REGISTER,
    },
    isAgreement: {
        type: Boolean,
    },
    agreementPopupRef: {
        type: Object as any,
    },
});

const emits = defineEmits(['register']);

// 注册方式
enum RegisterWayEnum {
    /* 账号注册 */
    ACCOUNT_REGISTER = '6',
    /* 手机号注册 */
    MOBILE_REGISTER = '7',
}

// 验证码倒计时开关
const countdownSwitch = ref(false);

// 表单Ref
const formRef = ref();

// 表单数据
const formData = ref({
    account: '',
    password: '',
    password_confirm: '',
    code: '',
});

// 表单校验规则
const rules = {
    account: {
        rules: [
            {
                required: true,
                errorMessage: '请输入账号/手机号',
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
    password: {
        rules: [
            {
                required: true,
                errorMessage: '请输入密码',
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
};

// 获取验证码
const handleCode = () => {
    if (!formData.value.account) {
        uni.showToast({
            title: '请输入手机号',
            icon: 'none',
        });
        return;
    }
    countdownSwitch.value = true;
    apiPostSendCode({
        mobile: formData.value.account,
        scene: CodeTypeEnum.MOBILE_REGISTER,
    });
    console.log('发送获取验证码请求');
};

// 注册
const handleConfirm = async () => {
    // 是否同意协议
    if (!props.isAgreement) {
        props.agreementPopupRef.open();
        return;
    }

    const res = await formRef.value.validate();
    console.log('表单校验通过', res);

    if (formData.value.password !== formData.value.password_confirm) {
        useToast('两次输入的密码不一致');
        return;
    }

    const { code }: any = await uni.login({
        provider: 'weixin',
    });
    // scene账号密码-3，手机验证码-2
    await apiPostAccountRegister({
        ...formData.value,
        code,
        scene: props.type === RegisterWayEnum.MOBILE_REGISTER ? 2 : 3,
    });
    emits('register');
};
</script>

<template>
    <view w-full>
        <uni-forms ref="formRef" :model-value="formData" :rules="rules">
            <uni-forms-item label="" name="account">
                <uni-easyinput
                    v-model="formData.account"
                    prefix-icon="person"
                    placeholder="请输入账号"
                />
            </uni-forms-item>

            <!-- 验证码-手机号码注册 -->
            <uni-forms-item v-if="type === RegisterWayEnum.MOBILE_REGISTER" label="" name="code">
                <uni-easyinput
                    v-model="formData.code"
                    prefix-icon="person"
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

            <!-- 密码 -->
            <uni-forms-item label="" name="password">
                <uni-easyinput
                    v-model="formData.password"
                    type="password"
                    prefix-icon="locked"
                    placeholder="请输入密码"
                />
            </uni-forms-item>

            <!-- 确认密码 -->
            <uni-forms-item label="" name="password_confirm">
                <uni-easyinput
                    v-model="formData.password_confirm"
                    type="password"
                    prefix-icon="locked"
                    placeholder="请再次输入密码"
                />
            </uni-forms-item>
        </uni-forms>

        <view mt-4 w-full between gap-4>
            <button type="default" @click="$emit('register')">返回登录</button>
            <button type="primary" @click="handleConfirm">立即注册</button>
        </view>
    </view>
</template>

<style lang="scss" scoped></style>
