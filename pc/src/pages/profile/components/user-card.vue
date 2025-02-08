<template>
    <ProCard
        class="flex flex-col flex-wrap justify-between gap-4 bg-background md:flex-row md:items-center md:bg-background-soft"
    >
        <div class="flex-shrink-0">
            <UserAvatar size="xl" border title desc />
        </div>
        <div class="flex flex-wrap justify-start gap-8">
            <div class="gap-1 center">
                <div class="flex-col center">
                    <span class="text-2xl font-medium text-primary">
                        {{ userStore.userInfo?.user_money }}
                    </span>
                    <span class="flex items-center text-xs">
                        剩余{{ appStore.siteConfig?.unit.power }}
                        <img class="h-4 w-4" :src="FlashIcon" alt="电量图标" />
                    </span>
                </div>
            </div>
        </div>
    </ProCard>
</template>

<script lang="ts" setup>
import FlashIcon from '~/assets/icons/flash.png';

const userStore = useUserStore();
const appStore = useAppStore();
</script>

<style lang="scss" scoped></style>
