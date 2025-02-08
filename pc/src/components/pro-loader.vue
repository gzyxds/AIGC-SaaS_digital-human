<template>
    <div class="inline-flex flex-col items-center justify-center gap-1 text-primary">
        <UIcon name="tabler:loader-2" :class="{ 'animate-spin': animaotion }" :size="iconSize" />
        <p :class="`text-xs ${textClass}`">{{ text }}</p>
    </div>
</template>

<script lang="ts" setup>
withDefaults(
    defineProps<{
        text?: string;
        iconSize?: string | number;
        textClass?: string;
        animaotion?: boolean;
    }>(),
    {
        text: '正在加载',
        iconSize: 22,
        textClass: '',
        animaotion: true,
    }
);
</script>

<style lang="scss" scoped></style>
