<template>
    <proModal v-model="isOpen" :title="detail?.mobile ? '更换手机号' : '绑定手机号'">
        <UForm :state="formData" :schema="bindFormSchema" class="my-4 space-y-4" @submit="onSubmit">
            <UFormGroup label="新的手机号" name="mobile" size="lg" required>
                <UInput
                    ref="mobileRef"
                    v-model="formData.mobile"
                    placeholder="请填写手机号"
                    color="gray"
                    class="flex-1"
                />
            </UFormGroup>

            <UFormGroup label="验证码" name="code" required>
                <UInput
                    id="code"
                    v-model="formData.code"
                    size="lg"
                    color="gray"
                    placeholder="请输入验证码"
                    :ui="{
                        trailing: {
                            padding: {
                                '2xs': 'pe-28',
                                xs: 'pe-28',
                                sm: 'pe-28',
                                md: 'pe-28',
                                lg: 'pe-28',
                                xl: 'pe-28',
                            },
                        },
                        icon: {
                            trailing: { pointer: 'pointer-events-auto', wrapper: '!px-[2px]' },
                        },
                    }"
                >
                    <template #trailing>
                        <UDivider class="h-1/2" orientation="vertical" />
                        <UButton
                            class="w-[90px]"
                            variant="link"
                            :ui="{
                                variant: { link: 'hover:no-underline' },
                                inline: 'w-full justify-center',
                            }"
                            @click="sendCode"
                        >
                            {{ codeBtnState.text }}
                        </UButton>
                    </template>
                </UInput>
            </UFormGroup>

            <div class="flex justify-end gap-2">
                <UButton
                    type="submit"
                    class="px-8"
                    size="lg"
                    :loading="btnStatus"
                    :disabled="!formData.code || !formData.mobile"
                >
                    {{ detail?.mobile ? '确认换绑' : '确认绑定' }}
                </UButton>
            </div>
        </UForm>
    </proModal>
</template>

<script lang="ts" setup>
import { object, string } from 'yup';

import { apiPostBindMobile, apiPostSendCode } from '~/api/user';

const emit = defineEmits<{
    refresh: [];
}>();

const detail = ref<UserInfo | null>(null);

const userStore = useUserStore();

const isOpen = ref<boolean>(false);
const btnStatus = ref<boolean>(false);
const formData = reactive<{ mobile: string; code: string; type?: 'bind' | '' }>({
    mobile: '',
    code: '',
    type: '',
});

const bindFormSchema = object({
    mobile: string().min(11, '手机号不能低于11位').required('请输入手机号码'),
    code: string().min(4, '验证码不能少于4位').max(4, '验证码不能多于4位').required('请输入验证码'),
});

const codeBtnState = ref<{
    isCounting: boolean;
    text: string;
    disabled: boolean;
}>({
    isCounting: false,
    text: '获取验证码',
    disabled: false,
});

const open = (data?: UserInfo) => {
    if (data) {
        detail.value = data;
        resetForm(formData, { mobile: '', code: '', type: '' });
        isOpen.value = true;
    }
};

const sendCode = async () => {
    if (codeBtnState.value.isCounting === true) return;
    codeBtnState.value.text = '正在发送中';
    codeBtnState.value.disabled = true;
    try {
        await apiPostSendCode({
            mobile: formData.mobile as string,
            scene: 'BGSJHM',
        });
        useMessage().success('验证码已发送，注意查收');
        codeBtnState.value.isCounting = true;
        codeBtnState.value.disabled = false;
        let count = 60;
        codeBtnState.value.text = count + 's';
        const interval = setInterval(() => {
            count--;
            codeBtnState.value.text = count + 's';
            if (count === 0) {
                clearInterval(interval);
                codeBtnState.value.isCounting = false;
                codeBtnState.value.text = '重新发送';
            }
        }, 1000);
    } catch (error) {
        codeBtnState.value.isCounting = false;
        codeBtnState.value.text = '重新发送';
    }
};

async function onSubmit() {
    btnStatus.value = true;
    try {
        if (!detail.value?.mobile) {
            formData.type = 'bind';
        }
        await apiPostBindMobile(formData);
        userStore.getUser();
        isOpen.value = false;
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
