<script setup lang="ts">
import { apiGetArticleList } from '@/api/article';
import { apiGetAiAvatarList } from '@/api/avatar';
import { apiGetIndexDecorateConfig } from '@/api/common';
import { useAppStore } from '@/stores/app';
// import { currentPage } from '@/utils/util';
import { isEqual } from 'lodash-es';

const appStore = useAppStore();

// 获取文章列表
const articleList = ref<any[]>([]);
// 获取作品列表
const avatarList = ref<any[]>([]);

// 首页装修数据
const decorate = ref<any>([]);
// 轮播图
const carouselImages = ref<any>([]);

// 轮询
let clearPolling: () => void;

// 获取文章列表
const getArticleList = async () => {
    const res = await apiGetArticleList({
        page_no: 1,
        page_size: 10,
    });
    articleList.value = res.lists;
};

// 获取作品列表
const getAvatarList = async () => {
    const res = await apiGetAiAvatarList({
        page_no: 1,
        page_size: 5,
    });
    avatarList.value = res.lists;

    // 有生成中的任务就开启轮询
    const loadingNum = avatarList.value.filter((item) => isTaskPendding(item.status)).length;
    if (loadingNum) {
        polling(5);
    }
};

// 轮询获取作品列表
const polling = async (page_size: number) => {
    // 列表生成中的任务数量
    console.log('开始轮询');
    const { start, clear } = usePollingTask(async (stop) => {
        const { lists: pollingLists } = await apiGetAiAvatarList({
            page_no: 1,
            page_size,
        });
        // 更新数据
        pollingLists.forEach((item: any, index) => {
            // 原列表生成中的任务 且 状态发生改变,更新数据
            if (
                isTaskPendding(avatarList.value[index].status) &&
                !isEqual(item.status, avatarList.value[index].status)
            ) {
                avatarList.value[index] = item;
            }
        });
        // 列表生成中的任务数量
        if (pollingLists.filter((item) => isTaskPendding(item.status)).length === 0) {
            stop();
        }
    });
    clearPolling = clear;
    start();
};

// 获取首页装修数据
const getDecorate = async () => {
    const res = await apiGetIndexDecorateConfig({
        type: 1,
    });
    console.log('首页装修数据 =>', JSON.parse(res.data));

    decorate.value = JSON.parse(res.data);
    carouselImages.value = decorate.value[0]?.content?.data || [];
};

const toPage = (url: string) => {
    uni.navigateTo({ url });
};

const toTabbarPage = (url: string) => {
    uni.switchTab({ url });
};

onShow(() => {
    getArticleList();
    getAvatarList();
    getDecorate();
    console.log('首页onBeforeUpdate执行');
});

onBeforeUpdate(() => {
    console.log('首页onBeforeUpdate执行');
});

onLoad(() => {
    console.log('首页onLoaded执行');
});

onMounted(() => {
    console.log('首页onMounted执行');
});

onBeforeUnmount(() => {
    // 清除轮询
    if (clearPolling) {
        clearPolling();
    }
});

interface ShareAppMessageEvent {
    from: 'button' | 'menu';
}

// #ifdef MP-WEIXIN
// 小程序分享功能
// 分享好友
onShareAppMessage((e: ShareAppMessageEvent) => {
    if (e.from === 'button') {
        console.log('来自页面内转发按钮');
    } else if (e.from === 'menu') {
        console.log('右上角菜单转发按钮');
    }
    return {
        title: appStore?.siteConfig?.website?.shop_name || 'AI数字人',
        // desc: appStore?.siteConfig?.website?.shop_name || 'AI数字人',
        // path: currentPage()?.path || '/pages/home/index',
        path: '/pages/home/index',
        imageUrl: appStore?.siteConfig?.share_image || '',
    };
});

// 分享朋友圈
onShareTimeline(() => {
    return {
        title: appStore?.siteConfig?.website?.shop_name || 'AI数字人',
        // desc: appStore?.siteConfig?.website?.shop_name || 'AI数字人',
        path: '/pages/home/index',
        imageUrl: appStore?.siteConfig?.website?.shop_logo || '',
    };
});
// #endif
</script>

<template>
    <!-- 顶部轮播背景图 -->
    <swiper circular :indicator-dots="false" :autoplay="false" h="672rpx">
        <swiper-item v-for="item in carouselImages" :key="item" h="672rpx">
            <image w="full" h="672rpx" block :src="joinUrl(item.image)" mode="aspectFill" />
        </swiper-item>
    </swiper>

    <!-- 按钮 -->
    <view mt="-52rpx" mx="144rpx">
        <button type="primary" rounded-full @click="toPage('/pages/digital_people/index')">
            <view i-ri:lightbulb-flash-fill />
            创作数字人视频
        </button>
    </view>

    <!-- 菜单卡片 -->
    <view ml-3 mt-40rpx flex="~ wrap">
        <view
            class="card-item"
            flex="~ center"
            mb-24rpx
            mr-24rpx
            h-148rpx
            w-338rpx
            rounded-28rpx
            bg-background
            py-4
            pl-28rpx
            pr-20rpx
            @click="toPage('/pages/create_profile/index')"
        >
            <view mr-10rpx>
                <view text="base foreground"> 形象克隆 </view>
                <view text="xs foreground-placeholder" line-clamp-1> 1:1轻松克隆形象 </view>
            </view>
            <image src="@/static/icons/icon-xxkl.png" h-84rpx w-84rpx flex-none />
        </view>
        <view
            class="card-item"
            flex="~ center"
            mb-24rpx
            mr-24rpx
            h-148rpx
            w-338rpx
            rounded-28rpx
            bg-background
            py-4
            pl-28rpx
            pr-20rpx
            @click="toPage('/pages/create_sound/index')"
        >
            <view mr-10rpx>
                <view text="base foreground"> 声音克隆 </view>
                <view text="xs foreground-placeholder" line-clamp-1> 高度还原真人音色 </view>
            </view>
            <image src="@/static/icons/icon-sykl.png" h-84rpx w-84rpx flex-none />
        </view>
    </view>

    <!-- 我的作品 -->
    <view v-if="avatarList.length" class="mt-[56rpx]" mx-3>
        <!-- 卡片标题组件 -->
        <view mb-30rpx>
            <title-content title="我的作品" @to-page="toTabbarPage('/pages/product/index')" />
        </view>

        <scroll-view :scroll-x="true" :show-scrollbar="false" class="w-full whitespace-nowrap">
            <view v-for="item in avatarList" :key="item.id" class="mr-3 inline-block">
                <product-card
                    :images="item.cover"
                    :time="item.duration"
                    :url="item.resultFile"
                    :status="item.status"
                    :fail-reason="item.fail_reason"
                    image-width="288rpx"
                    image-height="384rpx"
                >
                    <template #content>
                        <view
                            class="flex items-center justify-between pb-[12rpx] pl-[28rpx] pr-[16rpx] pt-[10rpx]"
                        >
                            <view class="line-clamp-1 text-base text-foreground">
                                {{ item.title }}
                            </view>
                            <!-- 右侧操作项 -->
                            <!-- <view class="flex items-center justify-center">
                                <image
                                    src="@/static/icons/icon-setting.png"
                                    class="h-[36rpx] w-[36rpx]"
                                />
                            </view> -->
                        </view>
                    </template>
                </product-card>
            </view>
        </scroll-view>
    </view>

    <!-- 文章资讯 -->
    <view mx-3 mt-7>
        <!-- 卡片标题组件 -->
        <view mb-30rpx>
            <title-content title="文章资讯" @to-page="toPage('/bundle/pages/article/index')" />
        </view>
        <!-- 资讯卡片组件 -->
        <article-card v-for="item in articleList" :key="item.id" :data="item" />
    </view>

    <!-- tabbar -->
    <tabbar :current="0" />
</template>

<style lang="scss" scoped>
.card-item {
    &:nth-child(2n) {
        margin-right: 0rpx;
    }
}
</style>

<route lang="json">
{
    "layout": "auth"
}
</route>
