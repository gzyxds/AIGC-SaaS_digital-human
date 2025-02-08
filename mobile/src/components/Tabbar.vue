<script lang="ts" setup>
// import { navigateTo } from '@/utils/util';
import { ref } from 'vue';

const props = defineProps({
    current: {
        type: Number,
    },
});

const current = ref(props.current);

const tabbarStyle = ref({
    activeColor: '#135dec',
    inactiveColor: '#A9AEB0',
    backgroundColor: '#212122',
});

const tabbarList = ref<any>([
    {
        iconPath: '/static/icons/tabbar/home-inactive.png',
        selectedIconPath: '/static/icons/tabbar/home-active.png',
        text: '首页',
        link: { path: '/pages/home/index', canTab: true },
        pagePath: '/pages/home/index',
    },
    {
        iconPath: '/static/icons/tabbar/product-inactive.png',
        selectedIconPath: '/static/icons/tabbar/product-active.png',
        text: '作品',
        link: { path: '/pages/product/index', canTab: true },
        pagePath: '/pages/product/index',
    },
    {
        iconPath: '/static/icons/tabbar/user-inactive.png',
        selectedIconPath: '/static/icons/tabbar/user-active.png',
        text: '我的',
        link: { path: '/pages/user/index', canTab: true },
        pagePath: '/pages/user/index',
    },
]);

const nativeTabbar = ['/pages/home/index', '/pages/product/index', '/pages/user/index'];
const handleChange = (index: number) => {
    // current.value = index;
    console.log(index);
    const selectTab = tabbarList.value[index];
    // const navigateType = nativeTabbar.includes(selectTab.link.path) ? 'switchTab' : 'reLaunch';
    // navigateTo(selectTab.link, navigateType);
    uni.switchTab({
        url: selectTab.pagePath,
    });
};

uni.hideTabBar();
</script>

<template>
    <!-- <u-tabbar
        v-model="current"
        v-bind="tabbarStyle"
        :list="tabbarList"
        :hide-tab-bar="true"
        @change="handleChange"
    /> -->

    <uv-tabbar
        :value="current"
        :custom-style="tabbarStyle"
        :hide-tab-bar="true"
        :border="false"
        @change="handleChange"
    >
        <uv-tabbar-item v-for="(item, index) in tabbarList" :key="index" :text="item.text">
            <template #active-icon>
                <image :src="item.selectedIconPath" h-44rpx w-44rpx />
            </template>
            <template #inactive-icon>
                <image :src="item.iconPath" h-44rpx w-44rpx />
            </template>
        </uv-tabbar-item>
    </uv-tabbar>
</template>
