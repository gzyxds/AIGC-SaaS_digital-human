<template>
    <UPopover
        v-if="userStore.isLogin"
        class="flex"
        mode="hover"
        :ui="{ rounded: 'rounded-2xl' }"
        :close-delay="300"
    >
        <div class="flex items-center gap-1">
            <div class="flex items-center rounded-full border-2 border-white/40">
                <UAvatar
                    :src="userStore.userInfo?.avatar"
                    alt="Avatar"
                    :size="size"
                    img-class="bg-foreground/30"
                />
            </div>
            <p v-if="showNickname">
                {{ userStore.userInfo?.nickname }}
            </p>
        </div>

        <template #panel>
            <div class="p-6">
                <div class="flex cursor-pointer items-center gap-4" @click="navigateTo('/profile')">
                    <UAvatar
                        :src="userStore.userInfo?.avatar"
                        alt="Avatar"
                        size="md"
                        img-class="bg-foreground/30"
                    />
                    <div class="flex flex-col">
                        <span class="max-w-32 truncate text-sm">
                            {{ userStore.userInfo?.nickname }}
                        </span>
                        <span class="max-w-32 truncate text-xs text-foreground/60">
                            UID：{{ userStore.userInfo?.sn }}
                        </span>
                    </div>
                    <UIcon
                        name="tabler:chevron-right"
                        class="ml-auto text-foreground/60"
                        size="20"
                    />
                </div>

                <ProCard class="rounded bg-primary/10" padding="p-2 my-4" rounded="rounded-md">
                    <div class="flex items-center text-sm">
                        <span>{{ appStore.siteConfig?.unit.power }}：</span>
                        <span class="mr-2 max-w-40 break-words text-primary">
                            {{ userStore.userInfo?.user_money }}
                        </span>
                        <UButton
                            size="2xs"
                            class="ml-auto"
                            @click="() => navigateTo('/profile/recharge')"
                            >充值</UButton
                        >
                    </div>
                </ProCard>

                <div class="grid grid-cols-3 gap-6">
                    <ProMenuLink v-for="item in profileMenu" :key="item.id" :menu="item">
                        <div class="group cursor-pointer flex-col gap-2 center">
                            <div
                                class="h-8 w-8 rounded-full bg-foreground/5 center hover:bg-foreground/10 active:bg-foreground/15"
                            >
                                <UIcon :name="item.icon" />
                            </div>
                            <p class="text-xs">{{ item.title }}</p>
                        </div>
                    </ProMenuLink>
                </div>
                <UDivider class="py-4" />
                <div class="flex justify-between">
                    <div
                        class="flex cursor-pointer items-center gap-1 rounded-md px-2 text-sm text-red-500 transition-colors hover:bg-red-50 dark:hover:hover:bg-red-500/10"
                        @click="userStore.logout()"
                    >
                        <UIcon name="tabler:logout" size="18" />
                        <p>退出登录</p>
                    </div>
                    <ThemeToggle />
                </div>
            </div>
        </template>
    </UPopover>
    <LoginGuard v-else>
        <div class="flex rounded-full border-2 border-white/40">
            <UAvatar icon="tabler:user" alt="未登录" :size="size" />
        </div>
    </LoginGuard>
</template>

<script lang="ts" setup>
import { apiGetCustomerConfig } from '~/api/common';

const userStore = useUserStore();
const appStore = useAppStore();
const controlStore = useControlsStore();

const customerConfig = ref<CustomerConfig>();

withDefaults(
    defineProps<{
        size?: '3xs' | '2xs' | 'xs' | 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl';
        showNickname?: boolean;
    }>(),
    {
        size: 'md',
        showNickname: false,
    }
);

const profileMenu = ref<MenuItem[]>([]);

onMounted(async () => {
    profileMenu.value = [
        {
            id: 1,
            icon: 'tabler:recharging',
            title: '充值记录',
            path: '/profile/recharge-record',
        },
        {
            id: 2,
            icon: 'tabler:file-text',
            title: '账单明细',
            path: '/profile/power-flow',
        },
        {
            id: 3,
            icon: 'tabler:book-2',
            title: '政策协议',
            target: '_blank',
            path: '/agreement',
        },
    ];
    customerConfig.value = await apiGetCustomerConfig();
    if (customerConfig.value.manual_kf.status == 1) {
        profileMenu.value.push({
            id: 4,
            icon: 'tabler:message-chatbot',
            title: '人工客服',
            target: '',
            path: '',
            click: () => {
                controlStore.setCustomerModal(true);
            },
        });
    }
});
</script>
