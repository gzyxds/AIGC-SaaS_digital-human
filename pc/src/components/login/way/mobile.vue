<template>
    <UForm :schema="schema" :state="formData" class="my-4 space-y-4" @submit="onSubmit">
        <UFormGroup label="手机号码" name="account" required>
            <UInput
                id="account"
                v-model="formData.account"
                placeholder="请输入手机号码"
                size="lg"
                autocomplete="off"
                :disabled="step !== 0"
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
                            autocomplete="off"
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

        <UFormGroup v-if="step === 1" label="验证码" name="code" required>
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

        <UFormGroup v-if="step === 1 && !isRegistered" label="密码" name="password">
            <UInput
                id="password"
                v-model="formData.password"
                :type="visiblePwd ? 'text' : 'password'"
                autocomplete="off"
                placeholder="请输入密码"
                size="lg"
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
            <template #help>
                <div class="flex items-center gap-1 text-xs">
                    <UIcon name="tabler:alert-circle" size="14" />
                    密码必须为6-25位数字+字母或符号组合
                </div>
            </template>
        </UFormGroup>

        <UFormGroup v-if="step === 1 && !isRegistered" label="确认密码" name="password_confirm">
            <UInput
                id="password_confirm"
                v-model="formData.password_confirm"
                :type="visibleCmfPwd ? 'text' : 'password'"
                autocomplete="off"
                placeholder="请再次输入密码"
                size="lg"
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

        <div class="flex gap-4">
            <UButton
                v-if="step === 1"
                color="white"
                class="flex-1 px-8 center"
                size="lg"
                @click="prevStep"
            >
                上一步
            </UButton>
            <UButton
                type="submit"
                class="flex-1 px-8 center"
                size="lg"
                :block="step === 0"
                :loading="btnStatus"
                :disabled="!isConfirmAgreement"
            >
                {{ ['下一步', isRegistered ? '立即登录' : '立即注册'][step] }}
            </UButton>
        </div>
    </UForm>
</template>

<script lang="ts" setup>
import { type InferType, object, ref as yupRef, string } from 'yup';

import type { FormSubmitEvent } from '#ui/types/form';
import {
    apiPostAccountLogin,
    apiPostAccountRegister,
    apiPostCheckRegister,
    apiPostSendCode,
    type LoginResponse,
} from '~/api/user';
import { LoginWayEnum } from '~/enums/variableEnum';

type Schema = InferType<typeof schema.value>;

interface FormData {
    code: string;
    account: string;
    password: string;
    scene: 1 | 2;
    password_confirm: string;
}

const props = withDefaults(
    defineProps<{
        isConfirmAgreement?: boolean;
    }>(),
    {
        isConfirmAgreement: false,
    }
);

const emit = defineEmits<{
    login: [value: LoginResponse];
}>();

/** 0=手机号验证 1=注册 2=登录｜注册 */
const step = ref<0 | 1 | 2>(0);
const areaCodes: { label: string; value: string }[] = [
    { value: '86', label: '中国大陆' },
    // { value: '886', label: '中国台湾' },
    // { value: '852', label: '中国香港' },
    // { value: '853', label: '中国澳门' },
];

const appStore = useAppStore();
const controlsStore = useControlsStore();
const userStore = useUserStore();

const visiblePwd = ref<boolean>(false);
const visibleCmfPwd = ref<boolean>(false);
const isRegistered = ref<boolean>(false);
const btnStatus = ref<boolean>(false);
const selected = ref<string>(areaCodes[0].value);
const schema = ref(
    object({
        account: string().min(8, '手机号不能少于11位').required('请输入手机号码'),
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
    account: '',
    code: '',
    password: '',
    password_confirm: '',
    scene: 2,
});

async function onSubmit(event: FormSubmitEvent<Schema>) {
    btnStatus.value = true;
    if (!props.isConfirmAgreement) {
        btnStatus.value = false;
        return useToast().add({
            title: '提示',
            description: '请先阅读并同意《用户协议》和《隐私政策》',
            color: 'red',
        });
    }
    if (step.value === 0) {
        try {
            await apiPostCheckRegister({ mobile: formData.account });

            if (!checkWayStatus(LoginWayEnum.MOBILE)) {
                return useMessage().warn('管理员已关闭手机号注册');
            }
            isRegistered.value = true;
            step.value = 1;
            schema.value = object({
                account: string().min(8, '手机号不能少于11位').required('请输入手机号码'),
                code: string().min(4, '验证码不能少于4位').required('请输入验证码'),
            });
            btnStatus.value = false;
        } catch (e) {
            isRegistered.value = false;
            step.value = 1;
            schema.value = object({
                account: string().min(8, '手机号不能少于11位').required('请输入手机号码'),
                code: string().min(4, '验证码不能少于4位').required('请输入验证码'),
                password: string().min(6, '密码不能少于6位').required('密码不能为空'),
                password_confirm: string()
                    .min(6, '密码不能少于6位')
                    .required('请再次输入密码')
                    .oneOf([yupRef('password')], '两次输入的密码不一致'),
            });
            btnStatus.value = false;
        }
    } else if (step.value === 1) {
        if (isRegistered.value) {
            try {
                const res = await apiPostAccountLogin(formData);
                btnStatus.value = false;
                if (isEnable(appStore.siteConfig?.login.coerce_mobile) && !res.mobile) {
                    emit('login', res);
                } else {
                    userStore.login(res.token);
                    controlsStore.setLoginModal(false);
                }
            } catch (error) {
                btnStatus.value = false;
            }
        } else {
            try {
                const res = await apiPostAccountRegister({
                    account: formData.account,
                    channel: 3,
                    code: formData.code,
                    password: formData.password,
                    password_confirm: formData.password_confirm,
                    scene: 2,
                });
                btnStatus.value = false;
                if (isEnable(appStore.siteConfig?.login.coerce_mobile) && !res.mobile) {
                    emit('login', res);
                } else {
                    useMessage().success('注册成功，即将自动登录...', {
                        timeout: 1000,
                        callback: () => {
                            userStore.login(res.token);
                        },
                        hook: () => {
                            controlsStore.setLoginModal(false);
                        },
                    });
                }
            } catch (error) {
                btnStatus.value = false;
            }
        }
    }
}

const sendCode = async () => {
    if (codeBtnState.value.isCounting === true) return;
    codeBtnState.value.text = '正在发送中';
    codeBtnState.value.disabled = true;
    try {
        await apiPostSendCode({
            mobile: formData.account,
            scene: isRegistered.value ? 'YZMDL' : 'YZMZC',
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

const prevStep = () => {
    step.value = 0;
    schema.value = object({
        account: string().min(8, '手机号不能少于11位').required('请输入手机号码'),
    });
};

const checkWayStatus = (way: LoginWayEnum) => {
    return (
        appStore.siteConfig?.login.login_way.includes(way) ||
        appStore.siteConfig?.login.login_way.includes(String(way))
    );
};
</script>

<style lang="scss" scoped></style>
