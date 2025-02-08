<template>
    <div class="relative min-h-full">
        <div class="gradient h-[50vh] w-screen md:h-screen"></div>
        <div
            class="landing-grid absolute inset-0 z-[-1] max-h-screen [mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]"
        />

        <div class="z-10 mx-auto flex min-h-full flex-col items-center">
            <div class="container flex items-center justify-between p-4">
                <NuxtLink to="/" class="transition-opacity">
                    <h1 class="flex items-center gap-2">
                        <img
                            v-if="appStore.siteConfig?.website.pc_logo"
                            :src="appStore.siteConfig?.website.pc_logo || LogoIcon"
                            class="size-8"
                            alt=""
                        />
                        <USkeleton v-else class="size-8" />

                        <p v-if="appStore.siteConfig?.website.pc_title" class="font-medium">
                            {{ appStore.siteConfig?.website.pc_title }}
                        </p>
                        <USkeleton v-else class="h-4 w-20" />
                    </h1>
                </NuxtLink>

                <div
                    ref="indexNavbarRef"
                    class="fixed left-1/2 top-4 z-50 hidden -translate-x-1/2 items-center gap-6 rounded-full border border-border/50 bg-background/60 px-8 py-2 backdrop-blur-sm lg:flex"
                >
                    <div
                        v-for="item in homeMenus"
                        :key="item.path"
                        :to="item.path"
                        class="hover:text-primary"
                    >
                        <LoginGuard>
                            <NuxtLink :to="item.path">
                                {{ item.title }}
                            </NuxtLink>
                        </LoginGuard>
                    </div>
                </div>

                <div class="hidden items-center gap-4 lg:flex">
                    <ThemeToggle />
                    <user-profile size="sm" />
                    <UButton
                        v-if="userstore.isLogin"
                        :ui="{ rounded: 'rounded-full' }"
                        to="/dashboard"
                        >我的工作台</UButton
                    >
                </div>

                <div class="flex flex-1 justify-end gap-2 lg:hidden">
                    <user-profile size="sm" />
                    <UButton
                        color="gray"
                        variant="ghost"
                        icon="tabler:menu-deep"
                        square
                        :ui="{ icon: { size: { md: 'size-5' } } }"
                        size="md"
                        padded
                        @click="isOpen = true"
                    />
                </div>
                <USlideover v-model="isOpen">
                    <div class="flex items-center justify-between p-4">
                        <h1 class="text-xl font-bold">页面导航</h1>
                        <UButton
                            color="gray"
                            variant="ghost"
                            size="sm"
                            icon="tabler:x"
                            square
                            padded
                            @click="isOpen = false"
                        />
                    </div>
                    <div class="flex flex-1 flex-col p-4 pt-0">
                        <NuxtLink
                            v-for="item in homeMenus"
                            :key="item.path"
                            :to="item.path"
                            class="flex items-center gap-2 rounded-xl p-4 hover:bg-primary/5 active:bg-primary/5 active:text-primary"
                        >
                            <UIcon :name="item.icon" size="18" />
                            {{ item.title }}
                        </NuxtLink>
                    </div>
                    <div class="flex justify-between p-4">
                        <ThemeToggle />
                    </div>
                </USlideover>
            </div>

            <div class="container py-20 md:py-36">
                <div class="flex-col gap-8 center">
                    <!-- <UBadge
                        :ui="{ rounded: 'rounded-full' }"
                        color="primary"
                        variant="outline"
                        size="md"
                    >
                        🎉 数字人分身平台v1.0.0正式发布<UIcon
                            name="tabler:external-link"
                            class="ml-1"
                        />
                    </UBadge> -->
                    <h1
                        v-if="decorateConfig?.[0].prop.title"
                        class="mx-8 text-center text-3xl font-bold sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl"
                    >
                        {{ decorateConfig?.[0].prop.title }}
                    </h1>
                    <USkeleton v-else class="h-12 w-2/3 !bg-white/30" />
                    <p v-if="decorateConfig?.[0].prop.subtitle" class="px-4 text-center">
                        {{ decorateConfig?.[0].prop.subtitle }}
                    </p>
                    <USkeleton v-else class="h-6 w-full !bg-white/30" />

                    <div class="flex gap-4">
                        <UButton
                            :ui="{ rounded: 'rounded-full' }"
                            size="lg"
                            color="white"
                            @click="toDemo"
                        >
                            查看案例
                        </UButton>
                        <LoginGuard>
                            <UButton
                                trailing-icon="tabler:arrow-right"
                                :ui="{ rounded: 'rounded-full' }"
                                size="lg"
                                to="/avatar"
                            >
                                开始构建
                            </UButton>
                        </LoginGuard>
                    </div>
                </div>
            </div>

            <div id="__demo_container__" class="container flex-1 p-4 md:p-12">
                <UCarousel
                    v-if="decorateConfig?.[1].prop.data.length"
                    ref="carouselRef"
                    v-slot="{ item }: { item: { image: string } }"
                    loop
                    :items="decorateConfig?.[1].prop.data"
                    class="overflow-hidden rounded-lg"
                    arrows
                    :ui="{
                        item: 'basis-full',
                        wrapper: 'group',
                        container: 'gap-6',
                        default: {
                            prevButton: {
                                class: '!opacity-0 group-hover:!opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity !bg-black/50 dark:!bg-white/20',
                            },
                            nextButton: {
                                class: '!opacity-0 group-hover:!opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity !bg-black/50 dark:!bg-white/50',
                            },
                        },
                    }"
                    @mouseenter="pauseAutoPlay"
                    @mouseleave="resumeAutoPlay"
                >
                    <AspectRatio :draggable="false" :src="getImageUrl(item.image)" />
                </UCarousel>
                <ProPlaceholder v-else />
            </div>

            <!-- <div class="container my-12 p-4 md:my-28 md:p-8">
                <div class="flex flex-col">
                    <h1 class="text-center text-2xl font-medium md:text-3xl lg:text-5xl">
                        为什么选择我们的平台？
                    </h1>
                    <p class="mb-16 mt-6 text-center text-foreground/60">
                        因为我们平台非常强，非常厉害，这是一段凑数文字，主要彰显字数效果；因为我们平台非常强，非常厉害，这是一段凑数文字，主要彰显字数效果
                    </p>
                    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        <ProCard v-for="item in 6" :key="item" class="bg-transparent" border>
                            <div class="flex flex-col gap-3">
                                <UIcon name="tabler:brand-appstore" size="40" />
                                <h2 class="text-xl font-medium">资深的研发团队</h2>
                                <p class="text-sm text-foreground/70">
                                    黑狐创客科技拥有1200+员工组成的资深研发团队，这样那样凑字数，黑狐创客科技拥有1200+员工组成的资深研发团队，这样那样凑字数
                                </p>
                            </div>
                        </ProCard>
                    </div>
                </div>
            </div> -->

            <!-- <div class="my-16 min-h-[400px] w-full bg-primary/10 p-16 center md:my-28">
                <div class="container py-8 center md:py-16">
                    <div class="w-2/3">
                        <ProPlaceholder />
                    </div>
                </div>
            </div> -->

            <!-- <div class="container flex flex-col gap-28 p-4 md:p-8">
                <div class="grid grid-cols-1 gap-8 md:gap-20 lg:grid-cols-2">
                    <div class="flex flex-col justify-center gap-4">
                        <h1 class="text-3xl font-medium">这是一个产品标题这是一个产品标题</h1>
                        <p class="">
                            这是一段产品介绍这是一段产品介绍这是一段产品介绍这是一个产品标题这是一个产品标题
                            这是一个产品标题
                        </p>
                        <div class="flex flex-col gap-4">
                            <UAlert
                                :ui="{ padding: 'p-0' }"
                                variant="soft"
                                icon="tabler:recharging"
                                description="You can add components to your app using the cli."
                                title="Heads up!"
                            />
                            <UAlert
                                :ui="{ padding: 'p-0' }"
                                variant="soft"
                                icon="tabler:recharging"
                                description="You can add components to your app using the cli."
                                title="Heads up!"
                            />
                            <UAlert
                                :ui="{ padding: 'p-0' }"
                                variant="soft"
                                icon="tabler:recharging"
                                description="You can add components to your app using the cli."
                                title="Heads up!"
                            />
                        </div>
                    </div>
                    <div class="flex-1">
                        <ProPlaceholder />
                    </div>
                </div>

                <div class="grid gap-8 md:grid-cols-2 md:gap-20">
                    <div class="flex flex-col justify-center gap-4 md:order-last">
                        <h1 class="text-3xl font-medium">这是一个产品标题这是一个产品标题</h1>
                        <p>
                            这是一段产品介绍这是一段产品介绍这是一段产品介绍这是一个产品标题这是一个产品标题
                            这是一个产品标题
                        </p>
                        <div class="flex flex-col gap-4">
                            <UAlert
                                :ui="{ padding: 'p-0' }"
                                variant="soft"
                                icon="tabler:recharging"
                                description="You can add components to your app using the cli."
                                title="Heads up!"
                            />
                            <UAlert
                                :ui="{ padding: 'p-0' }"
                                variant="soft"
                                icon="tabler:recharging"
                                description="You can add components to your app using the cli."
                                title="Heads up!"
                            />
                            <UAlert
                                :ui="{ padding: 'p-0' }"
                                variant="soft"
                                icon="tabler:recharging"
                                description="You can add components to your app using the cli."
                                title="Heads up!"
                            />
                        </div>
                    </div>
                    <div class="flex-1">
                        <ProPlaceholder />
                    </div>
                </div>

                <div class="grid gap-8 md:grid-cols-2 md:gap-20">
                    <div class="flex flex-col justify-center gap-4">
                        <h1 class="text-3xl font-medium">这是一个产品标题这是一个产品标题</h1>
                        <p>
                            这是一段产品介绍这是一段产品介绍这是一段产品介绍这是一个产品标题这是一个产品标题
                            这是一个产品标题
                        </p>
                        <div class="flex flex-col gap-4">
                            <UAlert
                                :ui="{ padding: 'p-0' }"
                                variant="soft"
                                icon="tabler:recharging"
                                description="You can add components to your app using the cli."
                                title="Heads up!"
                            />
                            <UAlert
                                :ui="{ padding: 'p-0' }"
                                variant="soft"
                                icon="tabler:recharging"
                                description="You can add components to your app using the cli."
                                title="Heads up!"
                            />
                            <UAlert
                                :ui="{ padding: 'p-0' }"
                                variant="soft"
                                icon="tabler:recharging"
                                description="You can add components to your app using the cli."
                                title="Heads up!"
                            />
                        </div>
                    </div>
                    <div class="flex-1">
                        <ProPlaceholder />
                    </div>
                </div>
            </div> -->

            <!-- <div class="w-full border-t border-gray-900/10 center dark:border-white/10">
                <div
                    class="container grid grid-cols-1 gap-8 p-4 py-8 md:grid-cols-2 md:p-8 md:py-16 lg:grid-cols-5"
                >
                    <div class="flex flex-col gap-4">
                        <h2 class="font-medium">政策协议</h2>
                        <NuxtLink v-for="item in 4" :key="item" class="text-foreground/60"
                            >Likeadmin</NuxtLink
                        >
                    </div>
                    <div class="flex flex-col gap-4">
                        <h2 class="font-medium">友情链接</h2>
                        <NuxtLink v-for="item in 4" :key="item" class="text-foreground/60"
                            >Likeadmin</NuxtLink
                        >
                    </div>
                    <div class="flex flex-col gap-4">
                        <h2 class="font-medium">友情链接</h2>
                        <NuxtLink v-for="item in 4" :key="item" class="text-foreground/60"
                            >Likeadmin</NuxtLink
                        >
                    </div>
                </div>
            </div> -->

            <div
                class="flex w-full flex-col border-t border-gray-900/10 center dark:border-white/10"
            >
                <div class="container flex flex-wrap gap-4 p-4 md:px-8 md:pb-0">
                    <NuxtLink
                        class="text-sm text-foreground/50"
                        to="/agreement?type=agreement&item=service"
                        target="_blank"
                    >
                        用户协议
                    </NuxtLink>
                    <NuxtLink
                        class="text-sm text-foreground/50"
                        to="/agreement?type=agreement&item=privacy"
                        target="_blank"
                    >
                        隐私政策
                    </NuxtLink>
                </div>
                <div class="container flex flex-wrap justify-between gap-4 p-4 md:px-8 md:pb-8">
                    <div class="text-sm text-foreground/50">
                        Copyright © {{ new Date().getFullYear() }}. All rights reserved.
                    </div>
                    <a
                        v-for="item in appStore.siteConfig?.copyright"
                        :key="item.key"
                        class="text-sm text-foreground/50"
                        :href="item.value"
                        target="_blank"
                    >
                        {{ item.key }}
                    </a>
                    <!-- <div class="flex items-center gap-4">
                        <UIcon name="tabler:brand-github" size="20" />
                        <UIcon name="tabler:brand-gitlab" size="20" />
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { apiGetDecorateConfig } from '~/api/common';
import LogoIcon from '~/assets/logo.svg';
import UserProfile from '~/components/user-profile.vue';
import { homeMenus } from '~/config/navigation';

const userstore = useUserStore();

const indexNavbarRef = ref<HTMLElement>();
const carouselRef = ref();

const appStore = useAppStore();

useHead({
    title: '首页',
});

definePageMeta({
    layout: 'full-screen',
    auth: false,
});

const isOpen = ref<boolean>(false);
const decorateConfig = ref<PcDecorate>();

let autoPlay: NodeJS.Timeout;

const resolveUrl = (url: string): boolean => {
    return !url.startsWith('http://') || !url.startsWith('https://');
};

const getImageUrl = (url: string) => {
    const runtime = useRuntimeConfig();
    return url.indexOf('http') ? `${runtime.public.apiBase}/${url}` : url;
};

const toDemo = () => {
    document.getElementById('__demo_container__')?.scrollIntoView({ behavior: 'smooth' });
};

// 启动自动播放
const startAutoPlay = () => {
    autoPlay = setInterval(() => {
        if (!carouselRef.value) return;

        if (carouselRef.value.page === carouselRef.value.pages) {
            return carouselRef.value.select(0);
        }

        carouselRef.value.next();
    }, 3000);
};

// 暂停自动播放
const pauseAutoPlay = () => {
    clearInterval(autoPlay);
};

// 恢复自动播放
const resumeAutoPlay = () => {
    startAutoPlay();
};

onMounted(async () => {
    document.body.classList.add('md:to-background');
    const res = await apiGetDecorateConfig();
    const data: PcDecorate = JSON.parse(res.config.data);
    data[1].prop.data.map((item) => {
        return item;
    });
    decorateConfig.value = data;
    startAutoPlay();
});

onBeforeUnmount(() => {
    document.body.classList.remove('md:to-background');
    pauseAutoPlay();
});
</script>

<style lang="scss" scoped></style>

<style lang="scss"></style>
