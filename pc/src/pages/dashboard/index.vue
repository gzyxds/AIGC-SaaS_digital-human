<template>
    <PageContainer
        :scroll="true"
        :breadcrumb="false"
        padding="md:p-0 p-4"
        :background="false"
        :breadcrumb-fixed="false"
    >
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <ProCard
                v-for="item in navigateList"
                :key="item.id"
                class="border border-white bg-gradient-to-b to-white !p-0 shadow-none dark:border-white/5 dark:to-background"
                :class="item.styles"
            >
                <div class="flex h-full justify-between rounded-lg md:rounded-xl">
                    <div class="flex flex-1 flex-col gap-2 p-4 md:p-6">
                        <h1 class="text-lg font-medium md:text-xl xl:text-2xl">{{ item.title }}</h1>
                        <p class="text-gray-500">{{ item.desc }}</p>
                        <NuxtLink :to="item.to" class="mt-auto">
                            <UButton
                                class="w-fit !text-white"
                                trailing-icon="tabler:chevron-right"
                                :ui="{ rounded: 'rounded-full' }"
                                >{{ item.btnText }}</UButton
                            >
                        </NuxtLink>
                    </div>
                    <img
                        :src="item.icon"
                        :alt="item.title"
                        class="aspect-[227/190] w-36 transition-[width] md:w-36 lg:w-44 2xl:w-auto"
                    />
                </div>
            </ProCard>

            <div
                class="relative flex flex-col overflow-hidden rounded-lg bg-primary p-4 text-white md:col-span-1 md:rounded-xl md:p-6 xl:hidden"
            >
                <h1 class="pb-4 text-lg font-medium md:text-xl">我的钱包</h1>
                <div v-if="powerStatic.length > 0" class="flex gap-8">
                    <div class="flex flex-col">
                        <label class="text-white/70"
                            >{{ appStore.siteConfig?.unit.power }}余额</label
                        >
                        <span class="text-2xl md:text-3xl">{{
                            powerStatic?.[0]?.amount || 0
                        }}</span>
                        <span class="flex items-center text-sm"
                            >今日充值：<UIcon name="tabler:plus" />
                            {{ powerStatic?.[0]?.changeAmount || 0 }}</span
                        >
                    </div>
                    <div class="flex flex-col">
                        <label class="text-white/70">今日用量</label>
                        <span class="text-2xl md:text-3xl">{{
                            powerStatic?.[1]?.amount || 0
                        }}</span>
                        <span class="flex items-center text-sm">
                            <span>较昨日</span>
                            <!-- 1 增加 2 减少 3 相等 -->
                            <UIcon
                                :name="
                                    {
                                        1: 'tabler:arrow-narrow-up-dashed',
                                        2: 'tabler:arrow-narrow-down-dashed',
                                        3: 'tabler:arrows-down-up',
                                    }[powerStatic?.[1]?.type as 1 | 2 | 3]
                                "
                            />
                            <span>{{ powerStatic?.[1]?.changeAmount || 0 }}</span>
                        </span>
                    </div>
                    <div
                        class="absolute -top-1/4 right-0 size-28 translate-x-1/4 rounded-full bg-white/10 p-4 center sm:size-48"
                    >
                        <UIcon name="tabler:coin-yen" class="text-8xl text-white/20 sm:text-9xl" />
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-3 2xl:grid-cols-4">
            <div
                class="col-span-4 rounded-lg bg-background p-4 md:p-6 xl:col-span-2 2xl:col-span-3"
            >
                <h1 class="pb-4 text-lg font-medium md:text-xl">
                    <span>数据概览</span>
                    <span class="ml-2 text-xs text-foreground/60">
                        更新于 {{ format(new Date(), 'yyy-MM-dd HH:mm', { locale: zhCN }) }}
                    </span>
                </h1>
                <div v-if="worksStatic.length > 0" class="grid grid-cols-4 gap-4">
                    <div v-for="item in worksStatic" :key="item.label" class="flex flex-col gap-2">
                        <label class="text-sm">{{ item.label }}</label>
                        <span class="text-xl sm:text-3xl">{{ item.all }}</span>
                        <span class="flex items-center text-xs">
                            今日：<UIcon name="tabler:plus" />{{ item.today }}
                        </span>
                    </div>
                </div>
                <div v-else class="grid grid-cols-4 gap-4">
                    <div v-for="item in 4" :key="item" class="flex flex-col gap-2">
                        <USkeleton class="h-4 w-32" />
                        <USkeleton class="h-6 w-20" />
                        <USkeleton class="h-4 w-32" />
                    </div>
                </div>
            </div>
            <div
                class="relative hidden flex-col overflow-hidden rounded-lg bg-primary p-4 text-white md:col-span-1 md:rounded-xl md:p-6 xl:flex"
            >
                <h1 class="pb-4 text-lg font-medium md:text-xl">
                    <span>我的钱包</span>
                    <span class="ml-2 text-xs text-white/60">
                        更新于 {{ format(new Date(), 'yyy-MM-dd HH:mm', { locale: zhCN }) }}
                    </span>
                </h1>

                <div class="flex gap-8">
                    <div v-if="powerStatic.length === 0" class="flex w-full gap-8">
                        <div v-for="item in 2" :key="item" class="flex w-full flex-col gap-2">
                            <USkeleton class="h-4 w-24 bg-white/40" />
                            <USkeleton class="h-6 w-36 bg-white/40" />
                            <USkeleton class="h-4 w-24 bg-white/40" />
                        </div>
                    </div>
                    <div v-if="powerStatic.length > 0" class="flex flex-col">
                        <label class="text-white/70"
                            >{{ appStore.siteConfig?.unit.power }}余额</label
                        >
                        <span class="text-2xl md:text-3xl">{{
                            powerStatic?.[0]?.amount || 0
                        }}</span>
                        <span class="flex items-center text-sm"
                            >今日充值：<UIcon name="tabler:plus" />
                            {{ powerStatic?.[0]?.changeAmount || 0 }}</span
                        >
                    </div>
                    <div v-if="powerStatic.length > 0" class="flex flex-col">
                        <label class="text-white/70">今日用量</label>
                        <span class="text-2xl md:text-3xl">{{
                            powerStatic?.[1]?.amount || 0
                        }}</span>
                        <span class="flex items-center text-sm">
                            <span>较昨日</span>
                            <!-- 1 增加 2 减少 3 相等 -->
                            <UIcon
                                :name="
                                    {
                                        1: 'tabler:arrow-narrow-up-dashed',
                                        2: 'tabler:arrow-narrow-down-dashed',
                                        3: 'tabler:arrows-down-up',
                                    }[powerStatic?.[1]?.type as 1 | 2 | 3]
                                "
                            />
                            <span>{{ powerStatic?.[1]?.changeAmount || 0 }}</span>
                        </span>
                    </div>
                    <div
                        class="absolute -top-1/4 right-0 size-28 translate-x-1/4 rounded-full bg-white/10 p-4 center sm:size-48"
                    >
                        <UIcon name="tabler:coin-yen" class="text-8xl text-white/20 sm:text-9xl" />
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 grid flex-1 grid-cols-1 gap-4 md:grid-cols-2">
            <div
                class="flex h-full min-h-72 flex-col rounded-lg bg-background p-4 md:min-h-full md:rounded-xl md:p-6"
            >
                <h1 class="pb-4 text-lg font-medium md:text-xl">
                    声音合成
                    <span class="ml-2 text-xs text-foreground/60">
                        更新于 {{ format(new Date(), 'yyy-MM-dd HH:mm', { locale: zhCN }) }}
                    </span>
                </h1>
                <div id="__voice_charts__" class="h-full w-full flex-1"></div>
            </div>
            <div
                class="flex h-full min-h-72 flex-col rounded-lg bg-background p-4 md:min-h-full md:rounded-xl md:p-6"
            >
                <h1 class="pb-4 text-lg font-medium md:text-xl">
                    数字分身
                    <span class="ml-2 text-xs text-foreground/60">
                        更新于 {{ format(new Date(), 'yyy-MM-dd HH:mm', { locale: zhCN }) }}
                    </span>
                </h1>
                <div id="__avatar_charts__" class="h-full w-full flex-1"></div>
            </div>
        </div>
    </PageContainer>
</template>

<script lang="ts" setup>
import { format } from 'date-fns';
import { zhCN } from 'date-fns/locale';
import * as echarts from 'echarts';

import {
    apiGetPowerStatic,
    apiGetWorkbenchChart,
    apiGetWorksStatic,
    type PoverStaticItem,
    type WorkbenchDataChartItem,
    type WorksStatic,
} from '~/api/dashboard';
import Home1 from '~/assets/icons/home/1.svg';
import Home2 from '~/assets/icons/home/2.svg';
import Home3 from '~/assets/icons/home/3.svg';

useHead({
    title: '数据看板',
});

const appStore = useAppStore();

const workbenchChart = ref<WorkbenchDataChartItem[]>([]);
const powerStatic = ref<PoverStaticItem[]>([]);
const worksStatic = ref<WorksStatic[]>([]);
const inital = ref<boolean>(false);

const navigateList = [
    {
        id: 1,
        title: '数字分身',
        desc: '无需真人拍摄低成本 高效制作视频',
        btnText: '立即克隆',
        bgColor: '#F5F9FD',
        bgColorDark: '#429ffb20',
        styles: 'from-[#F5F9FD] dark:from-[#429ffb20]',
        to: '/avatar',
        icon: Home1,
    },
    {
        id: 2,
        title: '声音合成',
        desc: '视频配音、IP专属声音 高度还原真人音色，更多人选择',
        btnText: '立即合成',
        bgColor: '#F2F1FF',
        bgColorDark: '#f2f1ff20',
        styles: 'from-[#F1F6FF] dark:from-[#4385ff20]',
        to: '/audio/synthesis',
        icon: Home2,
    },
    {
        id: 3,
        title: '声音克隆',
        desc: '有声播报、个性体验，仅需20句 克隆你的声音',
        btnText: '立即克隆',
        bgColor: '#F5FCFF',
        bgColorDark: '#43c7ff20',
        styles: 'from-[#F5FCFF] dark:from-[#43c7ff20]',
        to: '/audio/clone',
        icon: Home3,
    },
];

onMounted(async () => {
    powerStatic.value = await apiGetPowerStatic();
    worksStatic.value = await apiGetWorksStatic();
    workbenchChart.value = await apiGetWorkbenchChart();

    // 基于准备好的dom，初始化echarts实例
    const voiceEl = document.getElementById('__voice_charts__');
    const videoEl = document.getElementById('__avatar_charts__');

    const voiceCharts = echarts.init(voiceEl);
    const avatarCharts = echarts.init(videoEl);

    // 绘制图表
    if (voiceCharts) {
        voiceCharts.setOption({
            animation: true,
            tooltip: {
                trigger: 'axis',
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: workbenchChart.value[1].date,
            },
            yAxis: {
                type: 'value',
            },
            series: [
                {
                    name: '合成作品数量',
                    type: workbenchChart.value[1].type,
                    smooth: true,
                    data: workbenchChart.value[1].list,
                    lineStyle: { color: '#ba00d2', width: 3 },
                    itemStyle: { color: '#ba00d2' },
                    areaStyle: {
                        color: {
                            type: 'linear',
                            x: 0,
                            y: 0,
                            x2: 0,
                            y2: 1,
                            colorStops: [
                                {
                                    offset: 0,
                                    color: '#ba00d2',
                                },
                                {
                                    offset: 1,
                                    color: '#00000000',
                                },
                            ],
                        },
                    },
                },
            ],
        });
    }

    if (avatarCharts) {
        avatarCharts.setOption({
            animation: true,
            animationDuration: 1500,
            tooltip: {
                trigger: 'axis',
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: workbenchChart.value[0].date,
            },
            yAxis: {
                type: 'value',
            },
            series: [
                {
                    name: '合成作品数量',
                    type: workbenchChart.value[0].type,
                    smooth: true,
                    data: workbenchChart.value[0].list,
                    lineStyle: { color: '#3553fe', width: 3 },
                    itemStyle: { color: '#3553fe' },
                    areaStyle: {
                        color: {
                            type: 'linear',
                            x: 0,
                            y: 0,
                            x2: 0,
                            y2: 1,
                            colorStops: [
                                {
                                    offset: 0,
                                    color: '#3553fe',
                                },
                                {
                                    offset: 1,
                                    color: '#00000000',
                                },
                            ],
                        },
                    },
                },
            ],
        });
    }

    useResizeObserver(document.body, () => {
        if (inital.value) {
            voiceCharts.resize({
                animation: {
                    duration: 1000,
                    easing: 'cubicInOut',
                },
            });
            avatarCharts.resize({
                animation: {
                    duration: 1000,
                    easing: 'cubicInOut',
                },
            });
        }
    });
    inital.value = true;
});
</script>

<style lang="scss" scoped></style>
