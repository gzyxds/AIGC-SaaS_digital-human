<template>
    <div class="hidden justify-between md:flex">
        <slot name="title">
            <span class="text-2xl font-medium">{{ title }}</span>
        </slot>
        <slot />
    </div>
</template>

<script lang="ts" setup>
withDefaults(
    defineProps<{
        title?: string;
    }>(),
    {
        title: 'Title',
    }
);
</script>
