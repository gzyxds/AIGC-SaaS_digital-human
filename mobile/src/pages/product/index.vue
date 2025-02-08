<script setup lang="ts">
import { TaskStatusEnum } from '@/enums/variableEnum';
import { ref } from 'vue';
import ProductList from './components/ProductList.vue';

const tabsList = ref(['全部', '完成', '进行中', '失败']);
// 当前选中的tab
const currentTab = ref(0);

// tabs切换
const changeTab = (e: any) => {
    console.log('选中tabs => ', e);
    currentTab.value = e.index;
};

// 选中tab对应状态
const selectTabStatus = computed(() => {
    switch (currentTab.value) {
        case 0:
            return '';
        case 1:
            return TaskStatusEnum.SUCCESS;
        case 2:
            return TaskStatusEnum.PENDDING;
        case 3:
            return TaskStatusEnum.FAIL;
        default:
            return '';
    }
});
</script>

<template>
    <view class="product">
        <!-- tabs标签 -->
        <fui-tabs
            :current="currentTab"
            :tabs="tabsList"
            background="#0d0d0d"
            color="#ffffffb3"
            selected-color="#ffffff"
            slider-background="#1453e9"
            :is-fixed="true"
            :z-index="2"
            @change="changeTab"
        />

        <!-- 内容卡片 -->
        <view class="h-full" pt-102rpx>
            <product-list v-if="currentTab === 0" :type="selectTabStatus" />

            <product-list v-if="currentTab === 1" :type="selectTabStatus" />

            <product-list v-if="currentTab === 2" :type="selectTabStatus" />

            <product-list v-if="currentTab === 3" :type="selectTabStatus" />
        </view>
    </view>
    <!-- tabbar -->
    <tabbar :current="1" />
</template>

<style lang="scss" scoped></style>

<route lang="json">
{
    "layout": "auth"
}
</route>
