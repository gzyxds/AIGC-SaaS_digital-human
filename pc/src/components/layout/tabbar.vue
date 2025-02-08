<template>
    <div
        :class="inTabbar(route.path) ? 'translate-y-0' : 'translate-y-full'"
        class="fixed bottom-0 left-0 z-50 h-16 w-full overflow-hidden border-t border-white opacity-100 transition-all dark:border-gray-900 md:h-0 md:opacity-0"
    >
        <div
            class="flex w-full bg-background/70 pb-[calc(env(safe-area-inset-bottom)_+_0.75rem)] pt-3 backdrop-blur-lg"
        >
            <NuxtLink
                v-for="item in tabbarMenus"
                :key="item.path"
                :to="item.path"
                :class="`flex-1 flex-col gap-1 center ${item.path === route.path ? 'text-primary' : ''}`"
            >
                <UIcon :name="item.icon" class="h-5 w-5" />
                <span class="text-center text-xs">{{ item.title }}</span>
            </NuxtLink>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { tabbarMenus } from '~/config/navigation';

const route = useRoute();
</script>

<style lang="scss" scoped></style>
