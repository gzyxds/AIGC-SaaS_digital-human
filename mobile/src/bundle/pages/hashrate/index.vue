<script setup lang="ts">
import { apiGetPolicy } from '@/api/common';
import { apiPostPrePay, apiPostRecharge } from '@/api/pay';
import { apiGetPowerPlanList } from '@/api/power';
import { AgreementTypeEnum } from '@/enums/variableEnum';
import { useUserStore } from '@/stores/user';
import { debounce } from 'lodash-es';

const userStore = useUserStore();

const { getList, list } = apiGetPowerPlanList();

// 算力协议
const agreement = ref();

// 选中的套餐Id
const selectId = ref(0);
// 选中套餐的价格
const selectPrice = computed(() => {
    return list.value.find((item) => item.id === selectId.value)?.cost || '';
});

// 二维码支付信息
const payCode = ref('111');
// 支付弹窗-h5
const payPopupRef = ref();

let clearPolling: () => void;

// 选择套餐
const changeSelect = (id: number) => {
    selectId.value = id;
};

// 去到协议页面
const toAgreement = () => {
    uni.navigateTo({
        url: `/bundle/pages/agreement/index?type=${AgreementTypeEnum.RECHARGE}`,
    });
};

// 支付
const handlePay = debounce(async () => {
    // 获取价格
    const recharge = await apiPostRecharge({
        package: selectId.value,
    });

    let code = '';
    // 如果没有绑定微信号，需要传code
    if (!userStore.userInfo?.is_auth) {
        const res: any = await uni.login({
            provider: 'weixin',
        });
        code = res.code;
    }

    // 获取支付信息
    const prepay = await apiPostPrePay({
        from: 'recharge',
        order_id: recharge.order_id,
        pay_way: '2',
        code,
    });

    // #ifdef MP-WEIXIN
    // const data = await apiGetJsConfig({
    //     url: prepay.config,
    // });

    // 唤起微信支付
    uni.requestPayment({
        provider: 'wxpay',
        ...(prepay.config as any),
        success() {
            useToast().success('支付成功');
            userStore.getUser();
            setTimeout(() => {
                uni.navigateTo({ url: '/bundle/pages/hashrate_log/index' });
            }, 500);
        },
        fail() {
            useToast().error('支付失败');
        },
    });
    // #endif

    // #ifdef H5
    // // h5支付
    // payCode.value = prepay.config;
    // payPopupRef.value?.open();
    // // 轮询请求支付结果
    // const { start, clear } = usePollingTask(async (stop) => {
    //     apiPostPayStatus({
    //         from: 'recharge',
    //         order_id: recharge.order_id,
    //     }).then((res) => {
    //         if (res.pay_status === PayStatusEnum.PAID) {
    //             stop();
    //             payPopupRef.value?.close();
    //             useToast().success('支付成功');
    //             userStore.getUser();
    //         }
    //     });
    // });
    // clearPolling = clear;
    // start();
    // #endif
}, 500);

onBeforeUnmount(() => {
    // 清除轮询
    // if (clearPolling) {
    //     clearPolling();
    // }
});

onMounted(async () => {
    await getList();
    selectId.value = list.value[0]?.id;
    const res = await apiGetPolicy(AgreementTypeEnum.CURRENCY);
    agreement.value = res;
    // console.log('协议 =>', res);
});
</script>

<template>
    <!-- 算力充值 -->
    <view class="hashrate">
        <!-- 顶部卡片 -->
        <view position-relative>
            <image src="@/static/images/bg_hashrate.png" mode="scaleToFill" h="392rpx" w-full />
            <view position="absolute top-114rpx left-64rpx" text="xs foreground/60" font-400>
                尊享8+数字权益，每日赠送算力
            </view>
        </view>

        <view mt="-168rpx" bg-background style="backdrop-filter: blur(400rpx)">
            <!-- 选择套餐 -->
            <view>
                <view text="base" mb-28rpx ml-40rpx pt-40rpx font-500> 选择套餐 </view>

                <scroll-view
                    :scroll-x="true"
                    :show-scrollbar="false"
                    class="w-full whitespace-nowrap"
                >
                    <view v-for="item in list" :key="item.id" class="inline-block" pt-20rpx>
                        <view
                            border="4rpx solid"
                            ml-3
                            w-35
                            rounded-28rpx
                            position="relative"
                            :class="item.id === selectId ? 'border-primary' : 'border-#52525F'"
                            @click="changeSelect(item.id)"
                        >
                            <view pb-50rpx pt-74rpx text-center>
                                <view text="">
                                    {{ item.title }}
                                </view>
                                <view text="xl" center font-500>
                                    <image
                                        src="@/static/icons/icon-lightning.png"
                                        h-40rpx
                                        w-40rpx
                                        mode="scaleToFill"
                                        flex-none
                                    />
                                    <span>{{ item.power }}</span>
                                </view>
                            </view>
                            <view
                                rounded-b-24rpx
                                py-1
                                text-center
                                :class="item.id === selectId ? 'bg-primary' : 'bg-#52525F'"
                            >
                                <view text-xl font-500>
                                    <span text-20rpx>￥</span>{{ item.cost }}
                                </view>
                                <view text="20rpx foreground-light" line-through>
                                    原价:￥{{ item.original_cost }}
                                </view>
                            </view>

                            <!-- 定位到顶部 -->
                            <view
                                position="absolute top--16rpx left--4rpx"
                                w-140rpx
                                center
                                rounded-36rpx
                                rounded-bl-0
                                py-0.5
                                text-xs
                                :class="item.id === selectId ? 'bg-primary' : 'bg-#52525F'"
                            >
                                <span>赠:</span>
                                <image
                                    src="@/static/icons/icon-lightning.png"
                                    h-24rpx
                                    w-24rpx
                                    mode="scaleToFill"
                                />
                                <span>{{ item.gift_power }}</span>
                            </view>
                        </view>
                    </view>
                </scroll-view>
            </view>

            <!-- 算力说明 -->
            <view mt-60rpx px-40rpx>
                <view text="base" font-500>
                    {{ agreement?.title }}
                </view>
                <view text="xs foreground-placeholder">
                    <mp-html :content="agreement?.content" />
                </view>
            </view>
        </view>

        <!-- 底部支付按钮 -->
        <view class="footer fixed bottom-0 left-0 z-10 w-full bg-background p-24rpx">
            <button type="primary" @click="handlePay">立即支付 ￥{{ selectPrice }}</button>
            <view class="mt-[24rpx] text-center text-base font-[400]">
                <span text="sm foreground-muted">支付即代表你同意</span>
                <span class="text-primary" @click="toAgreement">《充值协议》</span>
            </view>
        </view>

        <!-- h5-二维码支付弹窗 -->
        <uni-popup
            ref="payPopupRef"
            type="center"
            :animation="true"
            mask-background-color="rgba(0, 0, 0, 0.7)"
            border-radius="1rem"
            background-color="#292929"
            style="z-index: 999"
            :is-mask-click="false"
        >
            <view center flex-col p-6>
                <view w-full between flex-1 pb-3>
                    <view text="base foreground"> 微信支付 </view>
                    <view i-tabler:x @click="payPopupRef?.close()" />
                </view>
                <!-- 二维码 -->
                <uv-qrcode
                    ref="qrcode"
                    canvas-id="qrcode"
                    :value="payCode"
                    size="300rpx"
                    :optional="{ margin: 10 }"
                />
                <view text="sm foreground-light" mt-3>
                    合计：<span text="xl danger">¥{{ selectPrice }}</span>
                </view>
                <view mt-1 text="sm foreground-light"> 请使用微信扫码支付 </view>
                <view mt-1 text="xs foreground-light"> 如遇问题无法解决时，请联系站点管理员 </view>
            </view>
        </uni-popup>
    </view>
</template>

<style lang="scss" scoped>
.hashrate {
    .footer {
        padding-bottom: calc(env(safe-area-inset-bottom) + 24rpx);
    }

    padding-bottom: calc(env(safe-area-inset-bottom) + 240rpx);
}
</style>
