<template>
    <header
        class="fixed left-0 top-0 z-50 h-[50px] w-full opacity-100 transition-[height,opacity] md:h-0 md:overflow-hidden md:opacity-0"
    >
        <div
            class="flex size-full items-center justify-between border-b border-white bg-background/70 p-2 px-4 backdrop-blur-lg dark:border-gray-950"
        >
            <NuxtLink
                to="/"
                class="transition-opacity"
                :class="appStore.showNavbarTitle ? 'opacity-0' : 'opacity-100'"
            >
                <h1 class="flex items-center gap-2">
                    <UAvatar
                        :ui="{ rounded: 'rounded-none', size: { md: 'h-7 w-7' } }"
                        :src="appStore.siteConfig?.website.pc_logo || LogoIcon"
                        alt="LOGO"
                        size="md"
                    />
                    <p class="font-medium">
                        {{ appStore.siteConfig?.website.pc_title || siteConfig.name }}
                    </p>
                </h1>
            </NuxtLink>

            <div class="flex flex-1 justify-end">
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
        </div>
        <USlideover v-model="isOpen">
            <Slideover @close="isOpen = false" />
        </USlideover>
    </header>
</template>

<script setup lang="ts">
import LogoIcon from '~/assets/logo.svg';
import { siteConfig } from '~/config/system';

import Slideover from './slideover.vue';

const appStore = useAppStore();
const isOpen = ref<boolean>(false);
</script>

<style lang="scss" scoped></style>
