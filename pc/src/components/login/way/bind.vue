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

        <div class="flex gap-4">
            <UButton type="submit" block class="flex-1 px-8 center" size="lg" :loading="btnStatus">
                立即绑定
            </UButton>
        </div>
    </UForm>
</template>

<script lang="ts" setup>
import { object, string } from 'yup';

import { apiPostBindMobile, apiPostSendCode } from '~/api/user';

interface FormData {
    code: string;
    mobile: string;
    type: 'bind';
}

const emit = defineEmits<{
    bind: [];
}>();

const areaCodes: { label: string; value: string }[] = [{ value: '86', label: '中国大陆' }];

const btnStatus = ref<boolean>(false);
const selected = ref<string>(areaCodes[0].value);
const schema = ref(
    object({
        mobile: string().min(8, '手机号不能少于11位').required('请输入手机号码'),
        code: string().min(4, '验证码不能少于4位').required('请输入验证码'),
    })
);

const userStore = useUserStore();
const controlsStore = useControlsStore();

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
    type: 'bind',
});

async function onSubmit() {
    btnStatus.value = true;
    try {
        await apiPostBindMobile(formData);
        btnStatus.value = false;
        if (userStore.token) {
            userStore.login(userStore.token);
            emit('bind');
        } else {
            useMessage('绑定成功');
            controlsStore.setLoginModal(false);
        }
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
            scene: 'BDSJHM',
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
