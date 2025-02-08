<script setup lang="ts">
import { apiPostBindMobile, apiPostSendCode } from '@/api/user';
import { CodeTypeEnum } from '@/enums/variableEnum';
import { useUserStore } from '@/stores/user';
import { ref } from 'vue';

const userStore = useUserStore();

// 倒计时开关
const countdownSwitch = ref(false);

const formData = ref({
    mobile: '',
    code: '',
    type: 'bind' as 'bind' | 'change',
});

const formRef = ref();
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

const handleConfirm = async () => {
    // 表单校验
    await formRef.value.validate();

    await apiPostBindMobile({
        ...formData.value,
    });

    await userStore.getUser();
    uni.navigateBack();
};

// 获取验证码
const handleCode = async () => {
    if (!formData.value.mobile) return useToast('请输入手机号');

    countdownSwitch.value = true;

    await apiPostSendCode({
        mobile: formData.value.mobile,
        scene:
            formData.value.type === 'bind' ? CodeTypeEnum.BIND_MOBILE : CodeTypeEnum.CHANGE_MOBILE,
    });

    console.log('发送获取验证码请求');
};

onMounted(() => {
    formData.value.mobile = userStore.userInfo?.mobile || '';
    formData.value.type = userStore.userInfo?.mobile ? 'change' : 'bind';
    uni.setNavigationBarTitle({
        title: userStore.userInfo?.mobile ? '更换手机号' : '绑定手机号',
    });
});
</script>

<template>
    <view px-3 pt-7>
        <uni-forms ref="formRef" :model-value="formData" :rules="rules">
            <uni-forms-item label="" name="mobile">
                <uni-easyinput
                    v-model="formData.mobile"
                    placeholder="请输入手机号"
                    prefix-icon="person"
                />
            </uni-forms-item>

            <uni-forms-item label="" name="code">
                <uni-easyinput
                    v-model="formData.code"
                    placeholder="请输入验证码"
                    prefix-icon="email"
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

        <button type="primary" mt-14 @click="handleConfirm">确定</button>
    </view>
</template>

<style lang="scss" scoped></style>
