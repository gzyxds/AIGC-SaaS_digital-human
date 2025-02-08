<template>
    <div class="relative cursor-pointer">
        <div
            v-if="!userStore.isLogin"
            class="absolute inset-0 z-20 size-full"
            @click="checkLogin"
        ></div>
        <div class="relative z-10" :class="containerClass">
            <slot />
        </div>
    </div>
</template>

<script lang="ts" setup>
const userStore = useUserStore();
const { setLoginModal } = useControlsStore();

const props = withDefaults(
    defineProps<{
        class?: string;
    }>(),
    {
        class: '',
    }
);

const containerClass = computed(() => {
    return props.class;
});
const checkLogin = () => {
    if (!userStore.isLogin) {
        setLoginModal(true);
    }
};
</script>

<style lang="scss" scoped></style>
