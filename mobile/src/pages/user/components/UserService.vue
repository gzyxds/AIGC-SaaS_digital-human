<script setup lang="ts">
import { ref } from 'vue';
import KefuPop from './KefuPop.vue';

const props = defineProps({
    type: {
        type: Number,
        default: 1,
    },
});

const kefuPopRef = ref();

// 打开客服弹窗
const openKeFuPop = () => {
    kefuPopRef.value.open();
};

// 页面跳转
function goPage(url: string) {
    uni.navigateTo({
        url,
    });
}
</script>

<template>
    <view>
        <!-- 竖排 -->
        <view v-if="type === 1" mx-3 overflow-hidden rounded-28rpx>
            <view
                between
                bg-background
                py-4
                pl-4
                pr-6
                @click="goPage('/bundle/pages/hashrate/index')"
            >
                <view center>
                    <view i-tabler:wallet text="foreground-muted" />
                    <view text="foreground-muted" ml-2.5 font-500> 充值中心 </view>
                </view>
                <view i-tabler:chevron-right text="sm foreground-light" />
            </view>

            <view
                between
                bg-background
                py-4
                pl-4
                pr-6
                @click="goPage('/bundle/pages/hashrate_log/index')"
            >
                <view center>
                    <view i-tabler:file-text text="foreground-muted" />
                    <view text="foreground-muted" ml-2.5 font-500> 算力明细 </view>
                </view>
                <view i-tabler:chevron-right text="sm foreground-light" />
            </view>

            <view between bg-background py-4 pl-4 pr-6 @click="openKeFuPop">
                <view center>
                    <view i-tabler:message-circle-2 text="foreground-muted" />
                    <view text="foreground-muted" ml-2.5 font-500> 联系客服 </view>
                </view>
                <view i-tabler:chevron-right text="sm foreground-light" />
            </view>

            <!-- <view between bg-background pl-4 pr-6 py-4>
        <view center>
          <view i-streamline:yuan-circle text="foreground-muted" />
          <view text="foreground-muted" font-500 ml-2.5>卡密兑换</view>
        </view>
        <view i-tabler:chevron-right text="sm foreground-light" />
      </view> -->

            <view
                between
                bg-background
                py-4
                pl-4
                pr-6
                @click="goPage('/bundle/pages/user_set/index')"
            >
                <view center>
                    <view i-tabler:settings-2 text="foreground-muted" />
                    <view text="foreground-muted" ml-2.5 font-500> 账号设置 </view>
                </view>
                <view i-tabler:chevron-right text="sm foreground-light" />
            </view>

            <view
                between
                bg-background
                py-4
                pl-4
                pr-6
                @click="goPage('/bundle/pages/agreement/index?type=service')"
            >
                <view center>
                    <view i-icon-park-outline:agreement text="foreground-muted" />
                    <view text="foreground-muted" ml-2.5 font-500> 服务协议 </view>
                </view>
                <view i-tabler:chevron-right text="sm foreground-light" />
            </view>
            <view
                between
                bg-background
                py-4
                pl-4
                pr-6
                @click="goPage('/bundle/pages/agreement/index?type=privacy')"
            >
                <view center>
                    <view i-icon-park-outline:agreement text="foreground-muted" />
                    <view text="foreground-muted" ml-2.5 font-500> 隐私协议 </view>
                </view>
                <view i-tabler:chevron-right text="sm foreground-light" />
            </view>
        </view>

        <!-- 横排 -->
        <view
            v-if="type === 2"
            grid
            grid-cols-4
            mx-3
            gap-y-6
            overflow-hidden
            rounded-28rpx
            bg-background
            py-4.5
        >
            <view v-for="item in 6" :key="item" center flex-col>
                <view i-tabler:wallet text="52rpx foreground-muted" />
                <view text="sm foreground-muted" mt-1> 充值中心 </view>
            </view>
        </view>

        <!-- 客服弹窗 -->
        <kefu-pop ref="kefuPopRef" />
    </view>
</template>

<style lang="scss" scoped></style>
