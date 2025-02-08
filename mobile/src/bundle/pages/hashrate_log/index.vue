<script setup lang="ts">
import HashrateLogList from './components/HashrateLogList.vue';

// 算力明细类型 ''-全部 1-获得 2-消耗
const type = ref<string | number>('');

const tabsList = [
    { name: '全部', type: '' },
    { name: '消耗', type: 2 },
    { name: '获得', type: 1 },
];
</script>

<template>
    <!-- 算力明细 -->
    <view h-full px-24rpx pb-40rpx pt-20rpx>
        <!-- tabs -->
        <view between px-200rpx pb-5>
            <view v-for="item in tabsList" :key="item.type" flex flex-col @click="type = item.type">
                <view :class="[{ 'text-foreground/70': type !== item.type }]">
                    {{ item.name }}
                </view>
                <view
                    class="tab-line"
                    :class="[{ 'bg-primary': type === item.type }]"
                    mt-1
                    h-1
                    w-full
                />
            </view>
        </view>

        <hashrate-log-list v-show="!type" :type="type" />

        <hashrate-log-list v-show="Number(type) === 1" :type="type" />

        <hashrate-log-list v-show="Number(type) === 2" :type="type" />
    </view>
</template>

<style lang="scss" scoped></style>
