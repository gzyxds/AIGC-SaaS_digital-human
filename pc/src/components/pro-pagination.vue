<template>
    <UPagination
        v-model="listState.page"
        :page-count="listState.page_size"
        :max="5"
        :total="listState.count"
        size="lg"
        :ui="{
            rounded: 'record-nth-child-2 record-nth-last-child-2',
            default: {
                firstButton: {
                    class: 'hidden sm:flex',
                },
                lastButton: {
                    class: 'hidden sm:flex',
                },
            },
        }"
        :prev-button="{
            icon: 'tabler:chevron-left',
        }"
        :next-button="{
            icon: 'tabler:chevron-right',
        }"
        :first-button="{
            icon: 'tabler:chevron-left-pipe',
            color: 'gray',
        }"
        :last-button="{
            icon: 'tabler:chevron-right-pipe',
            trailing: true,
            color: 'gray',
        }"
        show-first
        show-last
        @update:model-value="onChange"
    />
</template>

<script lang="ts" setup>
import type { ListState } from '~/composables/useRequest';

const props = withDefaults(
    defineProps<{
        modelValue: ListState;
    }>(),
    {
        modelValue: () => ({
            page: 1,
            page_size: 10,
            pedding: false,
            count: 0,
            isCompleted: false,
        }),
    }
);

const emit = defineEmits<{
    change: [];
    'update:modelValue': [value: ListState];
}>();

const listState = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit('update:modelValue', value);
    },
});

const onChange = () => {
    emit('change');
};
</script>

<style lang="scss" scoped>
.record-nth-child-2 {
    &:nth-child(2) {
        @apply rounded-s-md sm:rounded-none;
    }
}
.record-nth-last-child-2 {
    &:nth-last-child(2) {
        @apply rounded-e-md sm:rounded-none;
    }
}
</style>
