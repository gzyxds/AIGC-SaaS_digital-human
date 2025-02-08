<template>
    <UForm :schema="schema" :state="formData" class="space-y-4" @submit="onSubmit">
        <UFormGroup :label="['账号/手机号', '账号'][formType]" name="account" required>
            <UInput
                id="account"
                v-model="formData.account"
                autocomplete="off"
                :placeholder="['请填写账号/手机号码', '请填写账号'][formType]"
                size="lg"
            >
            </UInput>
        </UFormGroup>

        <UFormGroup label="密码" name="password" required>
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
            <template v-if="formType === 1" #help>
                <div class="flex items-center gap-1 text-xs">
                    <UIcon name="tabler:alert-circle" size="14" />
                    密码必须为6-25位数字+字母或符号组合
                </div>
            </template>
        </UFormGroup>

        <UFormGroup v-if="formType === 1" label="确认密码" name="password_confirm" required>
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
            <template #help>
                <div class="flex items-center gap-1 text-xs">
                    <UIcon name="tabler:alert-circle" size="14" />
                    密码必须为6-25位数字+字母或符号组合
                </div>
            </template>
        </UFormGroup>

        <div class="flex items-center justify-between">
            <span class="cursor-pointer text-sm text-primary" @click="emit('forget')">
                忘记密码？
            </span>
        </div>

        <div class="flex gap-4">
            <UButton
                variant="outline"
                class="flex-1 px-8 center"
                size="lg"
                :disabled="btnStatus"
                @click="changeformType(formType === 0 ? 1 : 0)"
            >
                {{ ['注册账号', '返回登录'][formType] }}
            </UButton>
            <UButton
                type="submit"
                class="flex-1 px-8 center"
                size="lg"
                :loading="btnStatus"
                :disabled="!isConfirmAgreement"
            >
                {{ ['立即登录', '立即注册', '修改并登录'][formType] }}
            </UButton>
        </div>
    </UForm>
</template>

<script lang="ts" setup>
import { object, ref as yupRef, string } from 'yup';

import { apiPostAccountLogin, apiPostAccountRegister, type LoginResponse } from '~/api/user';

withDefaults(
    defineProps<{
        isConfirmAgreement?: boolean;
    }>(),
    {
        isConfirmAgreement: false,
    }
);

const emit = defineEmits<{
    forget: [];
    login: [value: LoginResponse];
}>();

interface FormData {
    code: string;
    account: string;
    password: string;
    scene: 1 | 2 | 3;
    password_confirm: string;
}

/** 0=登录 1=注册 */
const formType = ref<0 | 1>(0);
const visiblePwd = ref<boolean>(false);
const visibleCmfPwd = ref<boolean>(false);
const btnStatus = ref<boolean>(false);
const schema = ref(
    object({
        account: string().min(8, '手机号不能少于11位').required('请输入手机号码'),
        password: string().min(6, '密码不能少于6位').required('密码不能为空'),
    })
);

const userStore = useUserStore();
const appStore = useAppStore();
const controlsStore = useControlsStore();

const formData = reactive<FormData>({
    account: '',
    code: '',
    password: '',
    password_confirm: '',
    scene: 3,
});

const changeformType = (type: 0 | 1) => {
    formType.value = type;
    if (formType.value === 0) {
        schema.value = object({
            account: string().min(8, '手机号不能少于11位').required('请输入手机号码'),
            password: string().min(6, '密码不能少于6位').required('密码不能为空'),
        });
    } else {
        schema.value = object({
            account: string()
                .min(8, '账号不能少于8位')
                .max(20, '账号不能大于20位')
                .required('请输入账号/手机号'),
            password: string().min(6, '密码不能少于6位').required('密码不能为空'),
            password_confirm: string()
                .min(6, '密码不能少于6位')
                .required('请再次输入密码')
                .oneOf([yupRef('password')], '两次输入的密码不一致'),
        });
    }
};

async function onSubmit() {
    btnStatus.value = true;
    try {
        if (formType.value === 0) {
            const res = await apiPostAccountLogin(formData);
            btnStatus.value = false;
            if (isEnable(appStore.siteConfig?.login.coerce_mobile) && !res.mobile) {
                emit('login', res);
            } else {
                userStore.login(res.token);
                controlsStore.setLoginModal(false);
            }
        } else {
            const res = await apiPostAccountRegister({
                account: formData.account,
                channel: 3,
                code: formData.code,
                password: formData.password,
                password_confirm: formData.password_confirm,
                scene: 3,
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
        }
    } catch (error) {
        btnStatus.value = false;
    }
}
</script>

<style lang="scss" scoped></style>
