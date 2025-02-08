<script setup lang="ts">
import { apiPostBindMobile, apiPostSendCode } from '@/api/user';
import { CodeTypeEnum } from '@/enums/variableEnum';

const emits = defineEmits(['bind']);

// 倒计时开关
const countdownSwitch = ref(false);

// 表单Ref
const formRef = ref();

// 表单数据
const formData = ref({
    mobile: '',
    code: '',
    type: 'bind' as 'bind' | 'change',
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
        scene: CodeTypeEnum.BIND_MOBILE,
    });
    console.log('发送获取验证码请求');
};

// 忘记密码
const handleConfirm = async () => {
    const res = await formRef.value.validate();
    console.log('表单校验通过', res);

    await apiPostBindMobile(formData.value);
    emits('bind');
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
        </uni-forms>

        <view mt-4 between gap-4>
            <button type="primary" @click="handleConfirm">确定</button>
        </view>
    </view>
</template>

<style lang="scss" scoped></style>
