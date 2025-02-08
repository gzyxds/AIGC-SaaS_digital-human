<template>
    <div class="inline-flex">
        <div v-if="userStore.isLogin" class="flex items-center">
            <div
                :class="{
                    'flex rounded-full border border-foreground/10 dark:border-white/70': border,
                }"
            >
                <UAvatar :src="userStore.userInfo?.avatar" icon="tabler:user" :size="size" />
            </div>
            <div
                v-if="title !== false && title !== undefined"
                class="ml-4 flex flex-col justify-center"
            >
                <span class="font-medium">{{ title }}</span>
                <span class="text-sm text-foreground/60">{{ desc }}</span>
            </div>
        </div>
        <div v-else class="flex items-center">
            <div
                :class="{
                    'flex rounded-full border border-foreground/10 dark:border-white/70': border,
                }"
            >
                <UAvatar icon="tabler:user" alt="未登录" :size="size" />
            </div>
            <div
                v-if="title !== false && title !== undefined"
                class="ml-4 flex flex-col justify-center"
            >
                <span class="font-medium">{{ title }}</span>
                <span class="text-sm text-foreground/60">{{ desc }}</span>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
const userStore = useUserStore();
const props = withDefaults(
    defineProps<{
        size?: '3xs' | '2xs' | 'xs' | 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl';
        border?: boolean;
        title?: boolean | string | number;
        desc?: boolean | string | number;
    }>(),
    {
        size: 'md',
        border: false,
        title: '',
        desc: '',
    }
);

const title = computed(() => {
    if (props.title === undefined) {
        return false;
    } else {
        if (props.title === true) {
            return userStore.userInfo?.nickname;
        } else {
            return props.title;
        }
    }
});

const desc = computed(() => {
    if (props.desc === undefined) {
        return false;
    } else {
        if (props.desc === true) {
            return `UID：${userStore.userInfo?.sn}`;
        } else {
            return props.desc;
        }
    }
});
</script>
