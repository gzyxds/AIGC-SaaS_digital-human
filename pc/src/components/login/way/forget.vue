<template>
    <UForm :schema="schema" :state="formData" class="space-y-4" @submit="onSubmit">
        <UFormGroup label="手机号码" name="mobile" required>
            <UInput
                id="mobile"
                v-model="formData.mobile"
                placeholder="请输入手机号码"
                size="lg"
                autocomplete="off"
                :ui="{
                    leading: {
                        padding: {
                            '2xs': 'ps-24',
                            xs: 'ps-24',
                            sm: 'ps-24',
                            md: 'ps-24',
                            lg: 'ps-24',
                            xl: 'ps-24',
                        },
                    },
                    icon: { leading: { pointer: 'pointer-events-auto' } },
                }"
            >
                <template #leading>
                    <div class="flex items-center text-sm">
                        <span class="mr-[1px] pb-[1px]">+</span>
                        <UInputMenu
                            id="areaCode-select"
                            v-model="selected"
                            trailing-icon="heroicons:chevron-up-down-20-solid"
                            :ui-menu="{
                                width: 'w-36',
                                option: {
                                    selectedIcon: { base: 'w-4 h-4 text-primary', padding: 'pe-1' },
                                    selected: 'pe-5',
                                },
                            }"
                            class="w-16"
                            :options="areaCodes"
                            :padded="false"
                            autocomplete="off"
                            variant="none"
                            option-attribute="value"
                        >
                            <template #option="{ option }">
                                <span class="truncate">{{ option.label }} +{{ option.value }}</span>
                            </template>
                        </UInputMenu>
                    </div>
                    <UDivider class="h-1/2" orientation="vertical" />
                </template>
            </UInput>
        </UFormGroup>

        <UFormGroup label="验证码" name="code" required>
            <UInput
                id="code"
                v-model="formData.code"
                size="lg"
                placeholder="请输入验证码"
                autocomplete="off"
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
                    icon: { trailing: { pointer: 'pointer-events-auto', wrapper: '!px-[2px]' } },
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

        <UFormGroup label="新密码" name="password">
            <UInput
                id="password"
                v-model="formData.password"
                type="password"
                autocomplete="off"
                placeholder="请输入新密码"
                size="lg"
            />
            <template #help>
                <div class="flex items-center gap-1 text-xs">
                    <UIcon name="tabler:alert-circle" size="14" />
                    密码必须为6-25位数字+字母或符号组合
                </div>
            </template>
        </UFormGroup>

        <UFormGroup label="确认新密码" name="password_confirm">
            <UInput
                id="password_confirm"
                v-model="formData.password_confirm"
                autocomplete="off"
                type="password"
                placeholder="请再次输入新密码"
                size="lg"
            />
        </UFormGroup>

        <div class="flex gap-4">
            <UButton
                variant="outline"
                class="flex-1 px-8 center"
                size="lg"
                :disabled="btnStatus"
                @click="emit('forget')"
            >
                返回登录
            </UButton>
            <UButton
                type="submit"
                class="flex-1 px-8 center"
                size="lg"
                :loading="btnStatus"
                :disabled="!formData.code"
            >
                确认修改
            </UButton>
        </div>
    </UForm>
</template>

<script lang="ts" setup>
import { type InferType, object, ref as yupRef, string } from 'yup';

import type { FormSubmitEvent } from '#ui/types/form';
import { apiPostResetPassword, apiPostSendCode } from '~/api/user';

type Schema = InferType<typeof schema.value>;

interface FormData {
    mobile: string;
    code: string;
    password: string;
    password_confirm: string;
}

const emit = defineEmits<{
    forget: [];
}>();

const areaCodes: { label: string; value: string }[] = [{ value: '86', label: '中国大陆' }];

const btnStatus = ref<boolean>(false);
const selected = ref<string>(areaCodes[0].value);
const schema = ref(
    object({
        mobile: string().min(8, '手机号不能少于11位').required('请输入手机号码'),
        code: string().min(4, '验证码不能少于4位').required('请输入验证码'),
        password: string().min(6, '新密码不能少于6位').required('新密码不能为空'),
        password_confirm: string()
            .min(6, '新密码不能少于6位')
            .required('请再次输入新密码')
            .oneOf([yupRef('password')], '两次输入的密码不一致'),
    })
);

const codeBtnState = ref<{
    isCounting: boolean;
    text: string;
    disabled: boolean;
}>({
    isCounting: false,
    text: '获取验证码',
    disabled: false,
});

const formData = reactive<FormData>({
    mobile: '',
    code: '',
    password: '',
    password_confirm: '',
});

async function onSubmit(event: FormSubmitEvent<Schema>) {
    btnStatus.value = true;
    try {
        await apiPostResetPassword(formData);
        btnStatus.value = false;
        emit('forget');
    } catch (error) {
        btnStatus.value = false;
    }
}

const sendCode = async () => {
    if (codeBtnState.value.isCounting === true) return;
    codeBtnState.value.text = '正在发送中';
    codeBtnState.value.disabled = true;
    try {
        await apiPostSendCode({
            mobile: formData.mobile,
            scene: 'ZHDLMM',
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
</script>

<style lang="scss" scoped></style>
