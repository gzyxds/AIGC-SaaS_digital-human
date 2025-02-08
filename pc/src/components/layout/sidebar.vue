<template>
    <div
        class="fixed left-0 top-0 z-50 h-full w-0 overflow-y-auto opacity-0 transition-[width,opacity] md:w-20 md:opacity-100"
    >
        <div class="flex size-full flex-col items-center gap-5 py-4">
            <NuxtLink to="/" class="flex justify-center">
                <h1 class="flex items-center gap-2">
                    <UAvatar
                        :ui="{ rounded: 'rounded-none', size: { md: 'h-9 w-9' } }"
                        :src="appStore.siteConfig?.website.pc_logo || LogoIcon"
                        alt="LOGO"
                        size="md"
                    />
                </h1>
            </NuxtLink>
            <NuxtLink
                v-for="item in sidebarMenus"
                :key="item.path"
                :to="item.path"
                :class="`flex flex-col items-center gap-1 ${
                    isActive(item.path) ? 'text-primary' : 'text-foreground/80'
                }`"
            >
                <div
                    class="h-10 w-10 rounded-full transition-colors center hover:bg-foreground/5"
                    :class="isActive(item.path) ? 'bg-primary/[0.09] hover:bg-primary/[0.09]' : ''"
                >
                    <UIcon :name="item.icon" class="h-5 w-5" />
                </div>
                <span class="text-center text-xs">{{ item.title }}</span>
            </NuxtLink>
            <div class="mt-auto flex flex-col items-center gap-6">
                <NuxtLink
                    to="/help"
                    :class="`flex flex-col items-center gap-1 ${
                        isActive('/help') ? 'text-primary' : 'text-foreground/80'
                    }`"
                >
                    <div
                        class="h-10 w-10 rounded-full center hover:bg-foreground/5"
                        :class="isActive('/help') ? 'bg-primary/[0.09]' : ''"
                    >
                        <UIcon name="tabler:user-question" class="h-5 w-5" />
                    </div>
                    <span class="text-center text-xs">帮助</span>
                </NuxtLink>
                <user-profile />
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import LogoIcon from '~/assets/logo.svg';
import UserProfile from '~/components/user-profile.vue';
import { sidebarMenus } from '~/config/navigation';

const appStore = useAppStore();
</script>

<style lang="scss" scoped></style>
