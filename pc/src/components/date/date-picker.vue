<template>
    <UPopover :popper="{ placement: 'bottom-start' }">
        <UButton
            icon="tabler:calendar-month"
            color="white"
            :ui="{
                color: { white: { solid: 'text-gray-400 dark:text-gray-500' } },
                icon: {
                    size: {
                        sm: 'h-4 w-4',
                    },
                },
            }"
            :label="format(date, 'yyy/MM/dd', { locale: zhCN })"
        />

        <template #panel="{ close }">
            <DatePanel v-model="date" is-required @close="close" />
        </template>
    </UPopover>
</template>

<script setup lang="ts">
import { format } from 'date-fns';
import { zhCN } from 'date-fns/locale';

const props = withDefaults(
    defineProps<{
        modelValue?: Date | number;
    }>(),
    {
        modelValue: () => new Date(),
    }
);

const emit = defineEmits(['update:model-value', 'close']);

const date = computed({
    get: () => props.modelValue,
    set: (value) => {
        emit('update:model-value', value);
        emit('close');
    },
});
</script>
