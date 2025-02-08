<template>
    <div class="flex flex-col gap-4 pt-4 center">
        <div v-if="loginConfig" class="relative overflow-hidden rounded-lg border border-border/50">
            <img class="size-48" :src="loginConfig?.url" alt="微信登录二维码" />
            <div
                v-if="!expireStatus"
                class="absolute inset-0 bg-black/20 text-white backdrop-blur center"
            >
                <UButton icon="tabler:reload" @click="getLoginQrcode">刷新</UButton>
            </div>
            <div
                v-if="!isConfirmAgreement"
                class="absolute inset-0 bg-black/20 text-sm text-white backdrop-blur center"
            >
                请先阅读并勾选协议
            </div>
            <div
                v-if="loginStatus === QrLoginStatusEnum.SCANNED"
                class="absolute inset-0 bg-black/20 text-sm text-white backdrop-blur center"
            >
                已扫码
            </div>
            <div
                v-if="loginStatus === QrLoginStatusEnum.SUCCESS"
                class="absolute inset-0 bg-black/20 text-sm text-white backdrop-blur center"
            >
                登录成功
            </div>
        </div>
        <USkeleton v-else class="size-48 rounded-lg" />
        <p>{{ { 1: '微信扫码登录/注册', 2: '已扫码', 4: '登录成功' }[loginStatus] }}</p>
        <p class="text-sm text-foreground/60">首次扫码关注公众号后将自动注册新账号</p>
    </div>
</template>

<script lang="ts" setup>
import NP from 'number-precision';

import { apiGetLoginQrcode, apiPostLoginTicket, type LoginResponse } from '~/api/user';
import { QrLoginStatusEnum } from '~/enums/variableEnum';

withDefaults(
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

const userStore = useUserStore();
const appStore = useAppStore();

const loginConfig = ref<LoginQrcode>();
const expireStatus = ref<boolean>(true);
const loginStatus = ref<QrLoginStatusEnum>(QrLoginStatusEnum.NOSCAN);

const expireDownTime = ref<NodeJS.Timeout | null>(null);

const getLoginQrcode = async () => {
    loginConfig.value = await apiGetLoginQrcode();
    expireStatus.value = true;
    stopDownTime(() => startDownTime());
    clear(() => start());
};

const startDownTime = () => {
    expireDownTime.value = setTimeout(
        () => {
            expireStatus.value = false;
        },
        NP.times(loginConfig.value?.expire_seconds || 120, 1000)
    );
};

const stopDownTime = (callback?: () => void) => {
    if (expireDownTime.value !== null) {
        clearTimeout(expireDownTime.value as NodeJS.Timeout);
    }
    callback?.();
};

const { start, clear } = usePollingTask(async (stop) => {
    if (loginConfig.value?.key) {
        const res = await apiPostLoginTicket({ key: loginConfig.value?.key });
        loginStatus.value = res.status;
        if (res.status === QrLoginStatusEnum.SUCCESS) {
            stop();
            if (isEnable(appStore.siteConfig?.login.coerce_mobile) && !res.user.mobile) {
                emit('login', res.user);
            } else {
                setTimeout(() => {
                    userStore.login(res.user.token);
                    useControlsStore().setLoginModal(false);
                }, 1000);
            }
        }
    }
});

onMounted(async () => {
    loginConfig.value = undefined;
    await getLoginQrcode();
    startDownTime();
});

onBeforeUnmount(() => {
    stopDownTime();
    clear();
});
</script>

<style lang="scss" scoped></style>
