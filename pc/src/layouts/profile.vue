<template>
    <div class="flex flex-1 md:overflow-y-hidden md:rounded-xl md:bg-background">
        <div
            class="hidden h-full w-44 flex-col gap-4 border-border/40 bg-background p-6 transition-[width] md:flex md:border-r lg:w-52"
        >
            <NuxtLink
                v-for="item in profileSideNavs"
                :key="item.path"
                class="flex cursor-pointer items-center gap-2 rounded-lg px-4 py-2 text-sm hover:bg-primary/5"
                :class="{
                    'bg-primary/10 text-primary':
                        item.path === $route.path || `${item.path}/` === $route.path,
                }"
                @click="
                    () => {
                        if (item.path) {
                            navigateTo(item.path);
                        } else {
                            item.click?.();
                        }
                    }
                "
            >
                <UIcon v-if="item.icon" :name="item.icon" :size="16" />
                <span>{{ item.title }}</span>
            </NuxtLink>
        </div>
        <div class="flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <NuxtPage />
        </div>
    </div>
</template>

<script lang="ts" setup>
import Breadcrumb from '~/components/layout/breadcrumb.vue';
import { profileSideNavs } from '~/config/navigation';
</script>

<style lang="scss" scoped></style>
