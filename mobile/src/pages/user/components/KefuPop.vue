<script lang="ts" setup>
import { apiGetCustomerConfig } from '@/api/common';
import { useDownloadFile } from '@/composables/useRequest';
import { ref } from 'vue';

const customerConfig = ref<CustomerConfig>();

onLoad(async () => {
    customerConfig.value = await apiGetCustomerConfig();
});

// 弹窗Ref
const popupRef = ref();

// 保存二维码图片
function handleConfirm() {
    if (!customerConfig.value?.manual_kf.qr_code) return console.log('客服二维码为空');
    const downloadTask = useDownloadFile({
        type: 'image',
        fileUrl: customerConfig.value?.manual_kf.qr_code,
        success: (res) => {
            console.log(res);
        },
        fail: (err) => {
            console.log(err);
        },
    });
}

defineExpose({
    // 打开弹窗
    open: () => {
        popupRef.value.open();
    },
});
</script>

<template>
    <!-- 联系客服 -->
    <uni-popup ref="popupRef" border-radius="24rpx" type="bottom" :safe-area="false">
        <view center flex-col bg-background class="rounded-t-[var(--ui-radius)]">
            <view w-full pl-5 pt-5 text-left text-foreground-muted font-500> 联系客服 </view>
            <view mt-10.5 w-fit rounded-28rpx bg-background-muted p-3>
                <image :src="customerConfig?.manual_kf.qr_code" h-46 w-46 rounded-28rpx />
            </view>

            <view
                v-if="isEnable(customerConfig?.manual_kf.title.status)"
                mt-9
                text-foreground-muted
            >
                {{ customerConfig?.manual_kf.title.value }}
            </view>

            <view
                v-if="isEnable(customerConfig?.manual_kf.service_time.status)"
                mt-2
                text-foreground-muted
            >
                服务时间：{{ customerConfig?.manual_kf.service_time.value }}
            </view>
            <view
                v-if="isEnable(customerConfig?.manual_kf.phone.status)"
                mt-2
                text-foreground-muted
            >
                服务热线：<span text-primary>{{ customerConfig?.manual_kf.phone.value }}</span>
            </view>
            <view class="footer" mt-10.5 w-full px-5>
                <button type="primary" @click="handleConfirm">保存二维码图片</button>
            </view>
        </view>
    </uni-popup>
</template>

<style lang="scss" scoped>
.footer {
    /* #ifdef H5 */
    padding-bottom: calc(env(safe-area-inset-bottom) + 160rpx);
    /* #endif */

    /* #ifndef H5 */
    padding-bottom: calc(env(safe-area-inset-bottom) + 60rpx);
    /* #endif */
}
</style>
