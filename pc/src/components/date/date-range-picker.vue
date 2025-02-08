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
        >
            <span class="text-gray-900 dark:text-white">
                {{ format(selected.start, 'yyy/MM/dd', { locale: zhCN }) }} -
                {{ format(selected.end, 'yyy/MM/dd', { locale: zhCN }) }}
            </span>
        </UButton>

        <template #panel="{ close }">
            <div class="flex items-center divide-gray-200 dark:divide-gray-800 sm:divide-x">
                <div class="hidden flex-col py-4 sm:flex">
                    <UButton
                        v-for="(range, index) in ranges"
                        :key="index"
                        :label="range.label"
                        color="gray"
                        variant="ghost"
                        class="rounded-none px-6"
                        :class="[
                            isRangeSelected(range.duration)
                                ? 'bg-gray-100 dark:bg-gray-800'
                                : 'hover:bg-gray-50 dark:hover:bg-gray-800/50',
                        ]"
                        truncate
                        @click="selectRange(range.duration)"
                    />
                </div>

                <DatePanel v-model="selected" @close="close" />
            </div>
        </template>
    </UPopover>
</template>

<script setup lang="ts">
import { type Duration, format, isSameDay, sub } from 'date-fns';
import { zhCN } from 'date-fns/locale';

const ranges = [
    { label: '近7天', duration: { days: 7 } },
    { label: '近14天', duration: { days: 14 } },
    { label: '近30天', duration: { days: 30 } },
    { label: '近3个月', duration: { months: 3 } },
    { label: '近6个月', duration: { months: 6 } },
    { label: '近1年', duration: { years: 1 } },
];

const props = withDefaults(
    defineProps<{
        modelValue?: {
            start: Date;
            end: Date;
        };
    }>(),
    {
        modelValue: () => ({
            start: sub(new Date(), { days: 14 }),
            end: new Date(),
        }),
    }
);

const emit = defineEmits<{
    'update:model-value': [
        value: {
            start: Date;
            end: Date;
        },
    ];
    close: [];
    change: [value: { start_time: string; end_time: string }];
}>();

const selected = computed({
    get: () => {
        return {
            start: props.modelValue.start,
            end: props.modelValue.end,
        };
    },
    set: (value) => {
        emit('change', {
            start_time: `${format(value.start, 'yyyy-MM-dd')} 00:00:00`,
            end_time: `${format(value.end, 'yyyy-MM-dd')} 00:00:00`,
        });
        emit('update:model-value', value);
        emit('close');
    },
});

function isRangeSelected(duration: Duration) {
    return (
        isSameDay(selected.value.start, sub(new Date(), duration)) &&
        isSameDay(selected.value.end, new Date())
    );
}

function selectRange(duration: Duration) {
    selected.value = { start: sub(new Date(), duration), end: new Date() };

    emit('change', {
        start_time: `${format(selected.value.start, 'yyyy-MM-dd')} 00:00:00`,
        end_time: `${format(selected.value.end, 'yyyy-MM-dd')} 00:00:00`,
    });
}
</script>

<style lang="scss" scoped>
.grid-cols-signle {
    grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
}
@media (min-width: 640px) {
    .grid-cols-signle {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}

:deep(.vc-pane-layout) {
    @extend .grid-cols-signle;
}
</style>
