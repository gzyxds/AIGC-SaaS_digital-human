<script lang="ts" setup>
import LoginPop from '@/components/LoginPop/index.vue';
import { useAppStore } from '@/stores/app';
import { useUserStore } from '@/stores/user';
import { onMounted, ref, watch } from 'vue';

const userStore = useUserStore();
const appStore = useAppStore();

// 引用 LoginPop 组件的实例
const loginPopRef = ref<InstanceType<typeof LoginPop> | null>(null);

// 获取当前页面并刷新
const refreshPage = () => {
    const pages = getCurrentPages();
    const currentPage = pages[pages.length - 1];
    currentPage.onLoad && currentPage.onLoad();
    currentPage.onShow && currentPage.onShow();
};

// 初始逻辑判断
const checkLoginStatus = () => {
    if (!userStore.isLogin) {
        loginPopRef.value?.open();
    }
    // 已登录
    if (userStore.isLogin) {
        loginPopRef.value?.close();

        console.log('checkLoginStatus isLogin 的变化，已登录');
        refreshPage();
    }
};

onMounted(() => {
    // 初始判断登录状态
    checkLoginStatus();
});

// 监听 isLogin 的变化
watch(
    () => userStore.isLogin,
    (newValue) => {
        // 未登录
        if (!newValue) {
            loginPopRef.value?.open();
        }
        // 已登录
        if (newValue) {
            loginPopRef.value?.close();
            console.log('checkLoginStatus isLogin 的变化，已登录');
            refreshPage();
        }
    }
);
</script>

<template>
    <layout-theme-provider>
        <slot />
        <!-- 登录弹窗 -->
        <login-pop ref="loginPopRef" />
    </layout-theme-provider>
</template>
