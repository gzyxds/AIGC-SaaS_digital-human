<template>
    <div class="flex flex-1 md:overflow-y-hidden md:rounded-xl md:bg-background">
        <div
            class="hidden h-full w-44 flex-col gap-4 border-border/40 bg-background p-6 transition-[width] md:flex md:border-r lg:w-52"
        >
            <UButton
                id="__driver_add__"
                block
                :ui="{ rounded: 'rounded-full' }"
                icon="tabler:plus"
                @click="toClone"
            >
                创建音色
            </UButton>
            <NuxtLink
                v-for="item in menuList"
                :key="item.path"
                class="flex cursor-pointer items-center gap-2 rounded-lg px-4 py-2 text-sm hover:bg-primary/5"
                :class="{
                    'bg-primary/10 text-primary':
                        item.path === $route.path || `${item.path}/` === $route.path,
                }"
                @click="
                    () => {
                        if (item.path) {
                            navigateTo(item.path);
                        } else {
                            item.click?.();
                        }
                    }
                "
            >
                <UIcon v-if="item.icon" :name="item.icon" />
                <span>{{ item.title }}</span>
            </NuxtLink>
        </div>
        <div class="flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <NuxtPage />
        </div>
        <ProModal
            ref="modalRef"
            v-model="showQrCode"
            content-id="__driver_modal__"
            title="创建音色"
        >
            <div class="flex-col center">
                <VueQrcode
                    class="size-52 rounded-lg"
                    :value="`${audioGeneratorLink}&access_token=${userStore.token}`"
                    :color="{ dark: '#000000', light: '#ffffff' }"
                    type="image/png"
                />

                <p class="flex flex-col items-center gap-2">
                    <span class="mt-2 text-xs">请使用手机扫码进行录制</span>
                    <span>
                        单次消耗
                        <span class="text-lg font-bold text-primary">
                            {{ powerConfig?.clone_power }}
                        </span>
                        {{ useAppStore().siteConfig?.unit.power }}
                    </span>
                </p>
                <p class="mt-4 flex items-center gap-4">
                    <ClientOnly>
                        <UButton
                            size="xs"
                            icon="tabler:copy"
                            @click="
                                () =>
                                    useCopy(`${audioGeneratorLink}&access_token=${userStore.token}`)
                            "
                        >
                            复制链接
                        </UButton>
                    </ClientOnly>
                    <UButton
                        size="xs"
                        icon="tabler:external-link"
                        to="/audio/clone/create"
                        target="_blank"
                    >
                        跳转前往
                    </UButton>
                </p>
                <UButton
                    id="__driver_confirm__"
                    class="mt-4"
                    :ui="{ rounded: 'rounded-full' }"
                    color="gray"
                    icon="tabler:circle-check"
                    @click="
                        () => {
                            modalRef?.close();
                            needRefresh = true;
                        }
                    "
                >
                    我已完成录制，点击关闭
                </UButton>
                <p class="mt-4 text-xs text-foreground/50">
                    <span>「扫码」代表同意</span>
                    <NuxtLink
                        target="_blank"
                        class="mx-[1px] font-medium text-primary"
                        to="/agreement?type=agreement&item=use"
                    >
                        《使用协议》
                    </NuxtLink>
                </p>
            </div>
        </ProModal>
    </div>
</template>

<script lang="ts" setup>
import VueQrcode from 'vue-qrcode';

import { apiGetVoiceClonePowerConfig } from '~/api/power';
import ProModal from '~/components/pro-modal.vue';

const route = useRoute();
const userStore = useUserStore();
const showQrCode = ref<boolean>(false);
const needRefresh = ref<boolean>(false);

const modalRef = getComponentExpose(ProModal);

provide('CloneLayout', {
    needRefresh,
    updateNeedRefresh: () => {
        needRefresh.value = false;
    },
});

const audioGeneratorLink = ref<string>('');

const menuList: MenuItem[] = [
    { icon: '', path: '/audio/clone', title: '我的音色' },
    { icon: '', path: '/audio/clone/common', title: '系统音色' },
];

const powerConfig = ref<VoiceClonePowerConfig>();

const { start, stop, state, next, prev } = useDriver(
    [
        {
            element: '#__driver_add__',
            popover: {
                title: '点击按钮',
                description: '点击【创建音色】按钮生成录制二维码',
                onNextClick: async () => {
                    showQrCode.value = true;
                    await nextTick();
                    next();
                },
            },
        },
        {
            element: '#__driver_modal__',
            popover: {
                title: '扫码录制',
                description:
                    '使用手机扫描二维码，根据要求进行声音录制，录制完成后点击完成按钮会自动刷新列表',
            },
        },
        {
            element: '#__driver_confirm__',
            popover: {
                title: '完成录制',
                description: '如果已经完成录制，点击按钮即可关闭弹窗并同时刷新列表',
                onCloseClick: () => {
                    showQrCode.value = false;
                    stop();
                },
                onNextClick: () => {
                    showQrCode.value = false;
                    stop();
                },
            },
        },
    ],
    {
        cache: {
            key: '__voice_clone__',
            enable: true,
        },
    }
);

const toClone = async () => {
    powerConfig.value = await apiGetVoiceClonePowerConfig();
    if (isMobile()) {
        navigateTo(`${audioGeneratorLink.value}&access_token=${userStore.token}`);
    } else {
        showQrCode.value = true;
    }
};

onMounted(async () => {
    powerConfig.value = await apiGetVoiceClonePowerConfig();
    audioGeneratorLink.value = isDev()
        ? // 开发环境时替换为你真实的网络地址
          `http://192.168.9.117:3333/audio/clone/create?origin=${route.fullPath}`
        : `${window.location.origin}/audio/clone/create?origin=${route.fullPath}`;
    const { isMobile } = useDevice();
    if (!isMobile) {
        start();
    }
});
</script>

<style lang="scss" scoped></style>
