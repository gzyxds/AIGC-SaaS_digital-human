<template>
    <NuxtLoadingIndicator :throttle="0" color="#135dec" />
    <NuxtLayout name="default" />
    <LoginModal />
    <CustomerModal />
    <UNotifications />
</template>

<script lang="ts" setup>
import LoginModal from '~/components/login/login-modal.vue';

import { siteConfig } from './config/system';
const appStore = useAppStore();

useHead({
    titleTemplate: `%s - ${appStore.siteConfig?.website.pc_title || siteConfig.name}`,
    link: [
        {
            rel: 'icon',
            type: 'image/x-icon',
            href: appStore.siteConfig?.website.pc_ico || '/favicon.ico',
        },
    ],
    script: appStore.siteConfig?.siteStatistics.clarity_code
        ? [
              {
                  type: 'text/javascript',
                  innerHTML: `
                    (function(c,l,a,r,i,t,y){
                    c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                    t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
                    })(window, document, "clarity", "script", "${appStore.siteConfig?.siteStatistics.clarity_code}");
                `,
              },
          ]
        : [],
});

useSeoMeta({
    keywords: appStore.siteConfig?.website.pc_keywords,
    description: appStore.siteConfig?.website.pc_desc,
    viewport:
        'width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no,minimal-ui,viewport-fit=cover',
});
</script>

<style lang="scss">
.page-enter-active,
.page-leave-active {
    transition: 0.1s;
}

.page-leave-from {
    transform: translateX(0%);
    opacity: 1;
}

.page-leave-to {
    opacity: 0;
    transform: translateX(-2%);
}

.page-enter-from {
    opacity: 0;
    transform: translateX(2%);
}

.page-enter-to {
    transform: translateX(0%);
    opacity: 1;
}

.layout-enter-active,
.layout-leave-active {
    transition: 0.1s;
}

.layout-leave-from {
    transform: translateX(0%);
    opacity: 1;
}

.layout-leave-to {
    opacity: 0;
    transform: translateX(-2%);
}

.layout-enter-from {
    opacity: 0;
    transform: translateX(2%);
}

.layout-enter-to {
    transform: translateX(0%);
    opacity: 1;
}

.component-enter-active,
.component-leave-active {
    transition: 0.1s;
}

.component-leave-from {
    transform: scale(1);
    opacity: 1;
}

.component-leave-to {
    opacity: 0;
    transform: scale(0.8);
}

.component-enter-from {
    opacity: 0;
    transform: scale(0.8);
}

.component-enter-to {
    transform: scale(1);
    opacity: 1;
}

.content-enter-active,
.content-leave-active {
    transition: 0.1s;
}

.content-leave-from {
    opacity: 1;
}

.content-leave-to {
    opacity: 0;
}

.content-enter-from {
    opacity: 0;
}

.content-enter-to {
    opacity: 1;
}

.login-enter-active,
.login-leave-active {
    transition: 0.1s;
}

.login-leave-from {
    transform: translateX(0);
    opacity: 1;
}

.login-leave-to {
    transform: translateX(-5%);
    opacity: 0;
}

.login-enter-from {
    transform: translateX(5%);
    opacity: 0;
}

.login-enter-to {
    transform: translateX(0);
    opacity: 1;
}
</style>
