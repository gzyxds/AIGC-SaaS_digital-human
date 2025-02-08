<template>
    <proModal v-model="isOpen" title="绑定微信" @close="closeModal">
        <div class="flex flex-col gap-4 pt-4 center">
            <div
                v-if="loginConfig"
                class="relative overflow-hidden rounded-lg border border-border/50"
            >
                <img class="size-48" :src="loginConfig?.url" alt="微信登录二维码" />
                <div
                    v-if="!expireStatus"
                    class="absolute inset-0 bg-black/20 text-white backdrop-blur center"
                >
                    <UButton icon="tabler:reload" @click="getLoginQrcode">刷新</UButton>
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
                    绑定成功
                </div>
            </div>
            <USkeleton v-else class="size-48 rounded-lg" />
            <p>
                {{ { 1: '使用微信扫码绑定微信', 2: '已扫码', 4: '登录成功' }[loginStatus] }}
            </p>
            <p class="text-sm text-foreground/60">扫码绑定后可以使用微信扫码登录</p>
        </div>
    </proModal>
</template>

<script lang="ts" setup>
import NP from 'number-precision';

import { apiGetLoginQrcode, apiPostLoginTicket, type LoginResponse } from '~/api/user';
import { QrLoginStatusEnum } from '~/enums/variableEnum';

const emit = defineEmits<{
    refresh: [];
}>();

const userStore = useUserStore();

const isOpen = ref<boolean>(false);
const loginConfig = ref<LoginQrcode>();
const expireStatus = ref<boolean>(true);
const loginStatus = ref<QrLoginStatusEnum>(QrLoginStatusEnum.NOSCAN);

const expireDownTime = ref<NodeJS.Timeout | null>(null);

const getLoginQrcode = async () => {
    loginConfig.value = await apiGetLoginQrcode('bind');
    expireStatus.value = true;
    stopDownTime(() => startDownTime());
    clear(() => start());
};

const open = async () => {
    loginStatus.value = QrLoginStatusEnum.NOSCAN;
    expireStatus.value = false;
    isOpen.value = true;
    await getLoginQrcode();
    startDownTime();
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
        const res = await apiPostLoginTicket({ key: loginConfig.value?.key, channel: 'bind' });
        loginStatus.value = res.status;
        if (res.status === QrLoginStatusEnum.SUCCESS) {
            stop();
            isOpen.value = false;
            userStore.getUser();
            emit('refresh');
        }
    }
});

const closeModal = () => {
    clearDownTime();
    loginConfig.value = undefined;
};

const clearDownTime = () => {
    stopDownTime();
    clear();
};

onBeforeUnmount(() => {
    clearDownTime();
});

defineExpose({
    open,
});
</script>

<style lang="scss" scoped></style>
