<template>
    <PageContainer
        :scroll="true"
        :breadcrumb="false"
        padding="p-4 md:p-0"
        :rounded="false"
        :background="false"
    >
        <!-- PC端 -->
        <div class="relative hidden h-full md:block">
            <div
                class="min-h-[200px] bg-cover bg-right bg-no-repeat p-4 md:p-6"
                :style="`background-image: url('${profilePcBg}')`"
            >
                <h1 class="flex items-center gap-2 text-base font-medium text-white">
                    <UIcon name="tabler:sunset-2" size="20" />
                    <span>
                        {{
                            `${getPartOfDay()}好，${userStore.isLogin ? userStore.userInfo?.nickname : ''}！`
                        }}
                    </span>
                </h1>
            </div>

            <div class="px-6">
                <div class="-mt-12 flex items-center gap-4">
                    <div class="rounded-full border-4 border-background bg-background p-0.5 center">
                        <div class="rounded-full border-[3px] border-primary/10 center">
                            <UAvatar
                                :src="userStore.userInfo?.avatar"
                                class="bg-background-soft"
                                icon="tabler:user"
                                size="3xl"
                                :ui="{
                                    icon: { base: 'text-gray-300' },
                                }"
                            />
                        </div>
                    </div>
                    <UButton
                        :ui="{ rounded: 'rounded-full' }"
                        icon="tabler:user-edit"
                        to="/profile/profile-edit"
                    >
                        编辑资料
                    </UButton>
                </div>
                <div class="pt-4">
                    <h2 class="mb-1 text-lg font-medium">
                        {{ userStore.userInfo?.nickname || '未登录' }}
                    </h2>
                    <div class="mb-1 inline-block text-xs">UID：{{ userStore.userInfo?.sn }}</div>
                    <div class="mb-1 text-sm text-foreground/70">
                        {{ dataMasking(userStore.userInfo?.account, 6) }}
                    </div>
                </div>
                <div class="mt-4 flex gap-4">
                    <div
                        v-for="(item, index) in userStore.userInfo?.static"
                        :key="`${item.label}${index}`"
                        class="flex items-center gap-1"
                    >
                        <span class="text-lg font-semibold">{{ item.value }}</span>
                        <span class="text-xs text-foreground/60">{{ item.label }}</span>
                    </div>
                </div>
            </div>

            <UDivider
                class="py-8"
                :ui="{ border: { base: 'flex border-border/40 dark:border-border/40' } }"
            />
        </div>

        <!-- 移动端 -->
        <div class="block h-full md:hidden">
            <div
                class="pointer-events-none fixed -left-[40%] -top-[13%] z-0 block max-h-screen w-[180%] overflow-hidden md:hidden"
            >
                <img :src="profileMobileBg" alt="" />
            </div>
            <div class="relative z-10">
                <h1 class="flex items-center gap-1 text-xs font-medium text-foreground/80">
                    <UIcon name="tabler:sunset-2" size="16" />
                    <span>
                        {{
                            `${getPartOfDay()}好，${userStore.isLogin ? userStore.userInfo?.nickname : ''}！`
                        }}
                    </span>
                </h1>

                <div class="flex min-h-full flex-col gap-4 md:bg-background">
                    <div class="py-8">
                        <div
                            v-auth
                            class="flex items-center gap-4"
                            @click="navigateTo('/profile/profile-edit')"
                        >
                            <div
                                class="flex-shrink-0 rounded-full border-2 border-background center dark:border-white/10"
                            >
                                <UAvatar
                                    class=""
                                    :src="userStore.userInfo?.avatar"
                                    icon="tabler:user"
                                    size="2xl"
                                    :ui="{
                                        icon: { base: 'text-gray-300' },
                                    }"
                                />
                            </div>
                            <div>
                                <h2 class="">{{ userStore.userInfo?.nickname || '未登录' }}</h2>
                                <p v-if="userStore.isLogin" class="text-sm text-foreground/60">
                                    {{
                                        userStore.userInfo?.mobile
                                            ? dataMasking(userStore.userInfo?.mobile, 6)
                                            : '未绑定手机号'
                                    }}
                                </p>
                                <p class="mt-0.5 flex items-center text-xs text-foreground/60">
                                    <span class="text-primary">
                                        {{ appStore.siteConfig?.unit.power }}:
                                        {{ userStore.userInfo?.user_money }}
                                    </span>
                                    <UIcon name="heroicons:bolt-16-solid text-primary " />
                                </p>
                            </div>
                            <div class="ml-auto mr-8 text-foreground/70">
                                <UIcon name="tabler:chevron-right" size="20" />
                            </div>
                        </div>
                    </div>

                    <ProCard
                        class="flex justify-around bg-background/70 backdrop-blur-md dark:bg-background/50"
                    >
                        <div
                            v-for="(item, index) in userStore.userInfo?.static"
                            :key="`${item.label}${index}`"
                            class="flex flex-col items-center gap-2"
                        >
                            <span class="text-xl font-medium text-primary">
                                {{ item.value }}
                            </span>
                            <span class="text-xs text-foreground/60">{{ item.label }}</span>
                        </div>
                    </ProCard>

                    <ProCard
                        class="flex flex-1 flex-col gap-2 bg-background/70 dark:bg-background/50"
                        padding="px-2 py-4"
                    >
                        <ProMenuLink
                            v-for="item in menuList"
                            :key="item.path"
                            class="flex items-center gap-2 rounded-lg px-4 py-2 active:bg-primary/5 active:text-primary"
                            :menu="item"
                        >
                            <UIcon :name="item.icon" />
                            <span>{{ item.title }}</span>
                            <UIcon class="ml-auto" name="tabler:chevron-right" />
                        </ProMenuLink>
                    </ProCard>
                </div>
            </div>
        </div>
    </PageContainer>
</template>

<script lang="ts" setup>
import profileMobileBg from '~/assets/images/profile-bg.png';
import profilePcBg from '~/assets/images/profile-bg-pc.png';
useHead({
    title: '个人信息',
});

definePageMeta({
    layout: 'profile',
});

const userStore = useUserStore();
const appStore = useAppStore();
const controlStore = useControlsStore();

const menuList: MenuItem[] = [
    {
        icon: 'tabler:battery-vertical-charging-2',
        title: '充值中心',
        path: '/profile/recharge',
    },
    {
        icon: 'tabler:clock-record',
        title: '充值记录',
        path: '/profile/recharge-record',
    },
    {
        icon: 'tabler:file-text',
        title: '账单明细',
        path: '/profile/power-flow',
    },
    {
        icon: 'tabler:user-question',
        title: '帮助中心',
        path: '/help',
    },
    {
        icon: 'tabler:book-2',
        title: '政策协议',
        path: '/agreement',
        target: '_blank',
    },
    {
        icon: 'tabler:message-chatbot',
        title: '人工客服',
        path: '',
        target: '',
        click: () => controlStore.setCustomerModal(true),
    },
    {
        icon: 'tabler:logout-2',
        title: '退出登录',
        path: '',
        click: () => userStore.logout(),
    },
];

const getPartOfDay = (): string => {
    const now = new Date();
    const hours = now.getHours();

    if (hours >= 0 && hours < 6) {
        return '凌晨';
    } else if (hours >= 6 && hours < 9) {
        return '早上';
    } else if (hours >= 9 && hours < 12) {
        return '上午';
    } else if (hours >= 12 && hours < 18) {
        return '下午';
    } else {
        return '晚上';
    }
};
</script>

<style lang="scss" scoped></style>
