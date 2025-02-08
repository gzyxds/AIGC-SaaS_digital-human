<template>
    <div :class="`overflow-auto bg-background ${padding} ${border} ${rounded} ${customClass}`">
        <slot />
    </div>
</template>

<script lang="ts" setup>
import type { AllowedComponentProps, HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
    padding?: 'none' | string;
    border?: boolean | string;
    rounded?: 'none' | string;
}>();

const customClass = computed(() => props.class);
const padding = computed(() => {
    if (props.padding === '') {
        return '';
    } else if (props.padding) {
        return props.padding;
    }
    return 'p-4 md:p-6';
});

const rounded = computed(() => {
    if (props.rounded === '') {
        return '';
    } else if (props.rounded) {
        return props.rounded;
    }
    return 'rounded-lg md:rounded-xl';
});

const border = computed(() => {
    if (props.border === false) {
        return '';
    } else {
        if (props.border === true) {
            return 'border border-border-soft';
        } else if (props.border === '') {
            return '';
        } else {
            return props.border || 'border border-border-soft';
        }
    }
});
</script>
