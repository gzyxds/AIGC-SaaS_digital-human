<script setup lang="ts">
import { apiPostChangePassword, apiPostSetPassword } from '@/api/user';
import { useUserStore } from '@/stores/user';
import { ref } from 'vue';

const userStore = useUserStore();

const formData = ref({
    password: '',
    password_confirm: '',
    old_password: '',
});

const formRef = ref();

const rules = {
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
                errorMessage: '请确认新密码',
            },
        ],
    },
    old_password: {
        rules: [
            {
                required: true,
                errorMessage: '请输入原密码',
            },
        ],
    },
};

const handleConfirm = async () => {
    // 表单校验
    await formRef.value.validate();

    if (formData.value.password !== formData.value.password_confirm) {
        uni.showToast({
            title: '两次输入的密码不一致',
            icon: 'error',
        });
        return;
    }

    if (userStore.userInfo?.has_password) {
        await apiPostChangePassword(formData.value);
    } else {
        await apiPostSetPassword(formData.value);
    }

    // userStore.getUser();
    userStore.logout();
    setTimeout(() => {
        uni.switchTab({ url: '/pages/user/index' });
    }, 500);
};

onMounted(() => {
    uni.setNavigationBarTitle({
        title: userStore.userInfo?.has_password ? '修改密码' : '设置密码',
    });
});
</script>

<template>
    <view px-3 pt-7>
        <uni-forms ref="formRef" :model-value="formData" :rules="rules">
            <uni-forms-item label="" name="password">
                <uni-easyinput
                    v-model="formData.password"
                    type="password"
                    placeholder="请输入新密码"
                    prefix-icon="locked"
                />
            </uni-forms-item>

            <uni-forms-item label="" name="confirmPassword">
                <uni-easyinput
                    v-model="formData.password_confirm"
                    type="password"
                    placeholder="请确认新密码"
                    prefix-icon="locked"
                />
            </uni-forms-item>

            <uni-forms-item v-if="userStore.userInfo?.has_password" label="" name="oldPassword">
                <uni-easyinput
                    v-model="formData.old_password"
                    type="password"
                    placeholder="请输入原密码"
                    prefix-icon="locked"
                />
            </uni-forms-item>
        </uni-forms>

        <button type="primary" mt-14 @click="handleConfirm">确定</button>
    </view>
</template>

<style lang="scss" scoped></style>
