<script setup lang="ts">
import { apiPostSetUserInfoSingle } from '@/api/user';
import { useUserStore } from '@/stores/user';
import { ref } from 'vue';

const userStore = useUserStore();

const formData = ref({
    name: '',
});

const formRef = ref();
const rules = {
    name: {
        rules: [
            {
                required: true,
                errorMessage: '请输入昵称',
            },
        ],
    },
};

const handleConfirm = async () => {
    // 表单校验
    await formRef.value.validate();

    await apiPostSetUserInfoSingle({
        field: 'nickname',
        value: formData.value.name,
    });

    await userStore.getUser();
    uni.navigateBack();
};

onMounted(() => {
    formData.value.name = userStore.userInfo?.nickname || '';
});
</script>

<template>
    <view px-3 pt-7>
        <uni-forms ref="formRef" :model-value="formData" :rules="rules">
            <uni-forms-item label="" name="name">
                <uni-easyinput v-model="formData.name" placeholder="请输入昵称" />
            </uni-forms-item>
        </uni-forms>

        <button type="primary" mt-14 @click="handleConfirm">确定</button>
    </view>
</template>

<style lang="scss" scoped></style>
