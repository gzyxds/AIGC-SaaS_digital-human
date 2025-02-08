<template>
    <ProModal v-model="isOpen" title="音频预览" width="sm:max-w-md">
        <div class="flex w-full max-w-sm items-center gap-4 rounded-xl bg-background-soft p-4">
            <img
                :src="data?.cover || squareDefaultImg"
                class="size-14 flex-shrink-0 rounded-full md:size-20"
                alt="封面"
            />
            <AudioPlayer :src="data?.url" size="sm" />
        </div>
        <slot />
    </ProModal>
</template>

<script lang="ts" setup>
import squareDefaultImg from '~/assets/images/1_1_default.png';

const isOpen = ref<boolean>(false);
const data = ref<{ cover?: string; url: string }>();

const open = (e: { cover?: string; url: string }) => {
    isOpen.value = true;
    data.value = e;
};

defineExpose({ open });
</script>

<style lang="scss" scoped></style>
