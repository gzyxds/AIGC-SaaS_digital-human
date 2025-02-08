<!-- 算力记录列表 -->
<script setup lang="ts">
import { apiGetPowerFlow } from '@/api/user';

const props = defineProps({
    type: {
        type: [Number, String],
    },
});

const paging = shallowRef<any>(null);
const list = ref<any>();

const queryList = async (page_no: number, page_size: number) => {
    try {
        const { lists } = await apiGetPowerFlow({
            page_no,
            page_size,
            action: props.type,
        });
        paging.value.complete(lists);
    } catch (e) {
        console.log('报错=>', e);
        paging.value.complete(false);
    }
};

onLoad(() => {});
</script>

<template>
    <!-- 算力明细 -->
    <view class="hashrate-log-list">
        <z-paging ref="paging" v-model="list" :fixed="false" height="100%" @query="queryList">
            <view
                v-for="(item, index) in list"
                :key="index"
                mb-3
                between
                rounded-3
                bg-background
                py-28rpx
                pl-24rpx
                pr-48rpx
            >
                <view class="">
                    <view text="base foreground-muted" line-clamp-1 mb-10rpx font-500>
                        {{ item.type_desc }}
                    </view>
                    <view mb-1 text="xs foreground-placeholder">
                        {{ item.create_time }}
                    </view>
                    <view line-clamp-1 text="xs foreground-light">
                        <!-- 来源：小程序/PC !!!TODO：待后端完善字段 -->
                        {{ item.type_desc }}
                    </view>
                </view>
                <view
                    ml-10rpx
                    text-36rpx
                    font-700
                    :class="item.action === 1 ? 'text-primary' : 'text-danger'"
                >
                    {{ `${item.action === 1 ? '+' : '-'}${Number(item.change_amount)}` }}
                </view>
            </view>
        </z-paging>
    </view>
</template>

<style lang="scss" scoped>
.hashrate-log-list {
    height: calc(100vh - 160rpx - env(safe-area-inset-bottom));
}
</style>
