<!-- 创建声音 -->
<script lang="ts" setup>
import { ref } from 'vue';

// 弹窗Ref
const popupRef = ref();

// 确定选择
const handleConfirm = () => {
    // 关闭弹窗
    popupRef.value.close();
};

// 去协议页面
const toAgreement = () => {
    uni.navigateTo({
        url: '/bundle/pages/agreement/index?type=use',
    });
};

defineExpose({
    // 打开弹窗
    open: () => {
        popupRef.value.open();
    },
});
</script>

<template>
    <uni-popup ref="popupRef" border-radius="24rpx" type="bottom" :is-mask-click="false">
        <view class="rounded-t-[var(--ui-radius)]" bg-background>
            <view class="mx-40rpx mb-28rpx pt-40rpx text-foreground-muted font-500">
                注意事项
            </view>

            <view mx-5 text="sm">
                <view text-warning> 1.请严格按照要求的内容文本进行录制 </view>
                <view text-foreground-light> 2.请在安静的环境下进行录音，避免噪音干扰 </view>
                <view text-foreground-light> 3.请使用标准普通话，吐字清晰，语速适当 </view>
                <view text-foreground-light>
                    4.录音时长控制在<span text-warning>6～12秒</span>最佳，最多不超过<span
                        text-warning
                        >14秒</span
                    >
                </view>
                <view text-foreground-light> 5.录制完成后先试听看是否达到要求再提交 </view>
            </view>

            <!-- 底部按钮 -->
            <view class="footer" mt-13.5 w-full bg-background px-3>
                <button type="primary" @click="handleConfirm">创建声音</button>
                <view mt-24rpx text-center text-sm>
                    <span text-foreground-muted>我已知晓并同意</span>
                    <span text-primary @click="toAgreement">《使用协议》</span>
                </view>
            </view>
        </view>
    </uni-popup>
</template>

<style lang="scss" scoped>
.footer {
    padding-bottom: calc(env(safe-area-inset-bottom) + 24rpx);
}
</style>
