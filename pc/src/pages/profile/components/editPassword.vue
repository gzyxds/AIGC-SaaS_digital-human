<template>
    <proModal v-model="isOpen" title="修改密码">
        <UForm :state="formData" :schema="bindFormSchema" class="my-4 space-y-4" @submit="onSubmit">
            <UFormGroup label="原密码" name="old_password" size="lg" required>
                <UInput
                    v-model="formData.old_password"
                    placeholder="请填写原密码"
                    color="gray"
                    :type="visibleOldPwd ? 'text' : 'password'"
                    class="flex-1"
                    :ui="{
                        icon: {
                            trailing: {
                                pointer: 'pointer-events-auto',
                            },
                        },
                    }"
                >
                    <template v-if="formData.old_password" #trailing>
                        <UIcon
                            :name="visibleOldPwd ? 'tabler:eye-off' : 'tabler:eye'"
                            class="cursor-pointer text-foreground/70"
                            :size="20"
                            @click="visibleOldPwd = !visibleOldPwd"
                        />
                    </template>
                </UInput>
            </UFormGroup>

            <UFormGroup label="新密码" name="password" size="lg" required>
                <UInput
                    v-model="formData.password"
                    placeholder="请填写新密码"
                    color="gray"
                    :type="visiblePwd ? 'text' : 'password'"
                    class="flex-1"
                    :ui="{
                        icon: {
                            trailing: {
                                pointer: 'pointer-events-auto',
                            },
                        },
                    }"
                >
                    <template v-if="formData.password" #trailing>
                        <UIcon
                            :name="visiblePwd ? 'tabler:eye-off' : 'tabler:eye'"
                            class="cursor-pointer text-foreground/70"
                            :size="20"
                            @click="visiblePwd = !visiblePwd"
                        />
                    </template>
                </UInput>
            </UFormGroup>

            <UFormGroup label="确认新密码" name="password_confirm" size="lg" required>
                <UInput
                    v-model="formData.password_confirm"
                    placeholder="请再次填写新密码"
                    color="gray"
                    :type="visibleCmfPwd ? 'text' : 'password'"
                    class="flex-1"
                    :ui="{
                        icon: {
                            trailing: {
                                pointer: 'pointer-events-auto',
                            },
                        },
                    }"
                >
                    <template v-if="formData.password_confirm" #trailing>
                        <UIcon
                            :name="visibleCmfPwd ? 'tabler:eye-off' : 'tabler:eye'"
                            class="cursor-pointer text-foreground/70"
                            :size="20"
                            @click="visibleCmfPwd = !visibleCmfPwd"
                        />
                    </template>
                </UInput>
            </UFormGroup>

            <div class="flex justify-end gap-2">
                <UButton
                    type="submit"
                    class="px-8"
                    size="lg"
                    :loading="btnStatus"
                    :disabled="
                        !formData.old_password || !formData.password || !formData.password_confirm
                    "
                >
                    {{ detail?.has_password ? '确认修改' : '立即设置' }}
                </UButton>
            </div>
        </UForm>
    </proModal>
</template>

<script lang="ts" setup>
import { object, ref as yupRef, string } from 'yup';

import { apiPostChangePassword } from '~/api/user';

const emit = defineEmits<{
    refresh: [];
}>();

const detail = ref<UserInfo | null>(null);

const userStore = useUserStore();
const visibleOldPwd = ref<boolean>(false);
const visiblePwd = ref<boolean>(false);
const visibleCmfPwd = ref<boolean>(false);

const isOpen = ref<boolean>(false);
const btnStatus = ref<boolean>(false);
const formData = reactive<{ old_password: string; password: string; password_confirm: string }>({
    old_password: '',
    password: '',
    password_confirm: '',
});

const bindFormSchema = object({
    old_password: string().min(6, '密码不能少于6位').required('原密码不能为空'),
    password: string().min(6, '新密码不能少于6位').required('密码不能为空'),
    password_confirm: string()
        .min(6, '新密码不能少于6位')
        .required('请再次输入密码')
        .oneOf([yupRef('password')], '两次输入的新密码不一致'),
});

const open = (data?: UserInfo) => {
    if (data) {
        resetForm(formData, { old_password: '', password: '', password_confirm: '' });
        visibleOldPwd.value = false;
        visiblePwd.value = false;
        visibleCmfPwd.value = false;
        detail.value = data;
        isOpen.value = true;
    }
};

async function onSubmit() {
    btnStatus.value = true;
    try {
        await apiPostChangePassword(formData);
        userStore.getUser();
        isOpen.value = false;
        btnStatus.value = false;
        emit('refresh');
    } catch (error) {
        btnStatus.value = false;
    }
}

defineExpose({
    open,
});
</script>

<style lang="scss" scoped></style>
