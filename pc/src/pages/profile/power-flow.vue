<template>
    <PageContainer :scroll="true" breadcrumb="账单明细" :rounded="false" :background="true">
        <div class="mb-4 flex gap-4">
            <DateRangePicker
                v-model="dateRange"
                @change="
                    (e) =>
                        refresh({ newParams: { start_time: e.start_time, end_time: e.end_time } })
                "
            />
        </div>
        <div class="hidden flex-1 flex-col overflow-y-auto md:flex">
            <UTable
                class="flex-1 overflow-y-auto"
                :rows="list"
                :columns="columns"
                :loading="state.pedding"
                :ui="{ thead: 'sticky top-0 bg-background' }"
            >
                <template #change_amount-data="{ row }">
                    <span :class="row.action === 1 ? 'text-green-500' : 'text-red-500'">
                        {{ `${row.action === 1 ? '+' : '-'}${Number(row.change_amount)}` }}
                    </span>
                </template>
                <template #empty-state>
                    <div class="py-12 center">
                        <ProException type="empty" text="空空如也" :size="100" />
                    </div>
                </template>
            </UTable>
            <ProPagination v-model="state" class="mt-4" @change="getList" />
        </div>
        <div class="md:hidden">
            <ProList
                v-slot="{ item }"
                :request-instance="apiGetPowerFlow"
                :scroll="true"
                class="flex flex-col gap-4"
            >
                <div class="flex flex-col gap-2 rounded-lg bg-background-soft p-4">
                    <p class="text-xs text-foreground/50">{{ item.remark }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm">{{ item.type_desc }}</span>
                        <span
                            class="text-lg"
                            :class="item.action === 1 ? 'text-green-500' : 'text-red-500'"
                        >
                            {{ `${item.action === 1 ? '+' : '-'}${Number(item.change_amount)}` }}
                        </span>
                    </div>
                    <p class="flex items-center gap-1 text-xs text-foreground/50">
                        <UIcon name="tabler:clock-hour-9" size="12" />
                        <span>{{ item.create_time }}</span>
                    </p>
                </div>
            </ProList>
        </div>
    </PageContainer>
</template>

<script lang="ts" setup>
import { sub } from 'date-fns';

import { apiGetPowerFlow } from '~/api/user';
import DateRangePicker from '~/components/date/date-range-picker.vue';

const appStore = useAppStore();

useHead({
    title: '账单明细',
});

definePageMeta({
    layout: 'profile',
});

const dateRange = ref({ start: sub(new Date(), { days: 14 }), end: new Date() });
const { getList, list, state, refresh } = apiGetPowerFlow();

const columns = [
    {
        key: 'remark',
        label: '流水号',
    },
    {
        key: 'change_amount',
        label: '变动数量',
        sortable: true,
        class: 'min-w-28',
        sort: (a: string, b: string, direction: 'asc' | 'desc') => {
            if (!a || !b) return 0;

            // 将a和b的字符串转换为数字
            const aPrice = Number(a.replace(/[,$]/g, ''));
            const bPrice = Number(b.replace(/[,$]/g, ''));

            // 根据排序方向进行比较
            return direction === 'asc' ? aPrice - bPrice : bPrice - aPrice;
        },
    },
    {
        key: 'type_desc',
        label: '变动类型',
    },
    {
        key: 'create_time',
        label: '变动时间',
    },
];

onMounted(() => {
    if (!isMobile()) {
        getList();
    }
});
</script>
