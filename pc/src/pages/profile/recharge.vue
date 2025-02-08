<template>
    <PageContainer
        :scroll="true"
        breadcrumb="充值中心"
        prose
        padding="p-4 md:p-0"
        :rounded="false"
        :background="true"
    >
        <div class="relative md:flex md:justify-center md:p-6">
            <div class="flex w-full max-w-prose flex-col gap-4 pb-32 md:pb-28">
                <div>
                    <UserCard />
                </div>

                <div>
                    <div class="text-md mb-4 font-medium">套餐列表</div>
                    <UCarousel
                        v-if="list.length > 0"
                        v-slot="{ item, index }: { item: PowerPackage; index: number }"
                        v-load="state.pedding"
                        class="min-h-48"
                        :items="list"
                        arrows
                        :ui="{
                            wrapper: 'group',
                            container: 'gap-6',
                            default: {
                                prevButton: {
                                    class: '!opacity-0 group-hover:!opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity !bg-black/50 dark:!bg-white/20',
                                },
                                nextButton: {
                                    class: '!opacity-0 group-hover:!opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity !bg-black/50 dark:!bg-white/50',
                                },
                            },
                        }"
                    >
                        <ProCard
                            class="relative box-border min-w-48 cursor-pointer overflow-hidden"
                            :class="activePlan?.id === item.id ? 'md:bg-primary/10' : ''"
                            :border="
                                activePlan?.id === item.id
                                    ? 'border-[1.5px] border-primary'
                                    : 'border-[1.5px] border-border/30'
                            "
                            @click="selectHandle(item)"
                        >
                            <div class="flex flex-col gap-1">
                                <h1 class="font-bold text-foreground/70">
                                    {{ item.title || `套餐${index + 1}` }}
                                </h1>
                                <p class="flex items-center text-2xl text-primary">
                                    <img class="h-5 w-5" :src="FlashIcon" alt="电量图标" />
                                    {{ item.power }}
                                </p>
                                <p v-if="item.gift === 1" class="text-sm text-green-500">
                                    赠：{{ item.gift_power }}
                                </p>
                                <p class="text-xs text-foreground/40 line-through">
                                    原价：¥{{ item.original_cost || '--' }}
                                </p>
                                <p class="text-amber-400">¥{{ item.cost }}</p>

                                <UDivider class="my-2" />
                                <p class="text-xs text-foreground/70">
                                    有效期：{{ item.expire_time || '永久有效' }}
                                </p>
                                <p class="text-xs text-foreground/80">备注：{{ item.note }}</p>
                            </div>
                            <div
                                v-if="activePlan?.id === item.id"
                                class="absolute -bottom-5 -right-5 flex h-10 w-10 rotate-45 items-center justify-start bg-primary"
                            >
                                <UIcon name="tabler:check" class="-rotate-45 text-white" />
                            </div>
                        </ProCard>
                    </UCarousel>
                    <div v-else class="w-full py-20 center">
                        <div v-if="state.pedding" class="flex-col text-primary center">
                            <UIcon size="24" name="tabler:loader-2" class="animate-spin" />
                            <span class="text-sm">正在加载</span>
                        </div>
                        <ProException v-else type="empty" text="暂无套餐" />
                    </div>
                </div>

                <div>
                    <div class="text-md mb-4 font-medium">支付方式</div>

                    <div class="flex gap-4">
                        <ProCard
                            v-for="(item, index) in payWayList"
                            :key="item.id"
                            padding="px-6 py-4"
                            class="relative flex cursor-pointer items-center gap-2 overflow-hidden"
                            :border="
                                activePayway === index
                                    ? 'border-[1.5px] border-primary'
                                    : 'border-[1.5px] border-border/30'
                            "
                            @click="activePayway = index"
                        >
                            <img :src="item.icon" :alt="item.name" class="size-10" />
                            <span class="text-sm">{{ item.name }}</span>
                            <div
                                v-if="activePayway === index"
                                class="absolute -bottom-5 -right-5 flex h-10 w-10 rotate-45 items-center justify-start bg-primary"
                            >
                                <UIcon name="tabler:check" class="-rotate-45 text-white" />
                            </div>
                        </ProCard>
                    </div>
                </div>

                <div>
                    <div class="text-md mb-4 font-medium">
                        {{ appStore.siteConfig?.unit.power }}说明
                    </div>

                    <div class="whitespace-pre-wrap text-sm leading-6">
                        <div v-dompurify-html="powerDescribe" />
                    </div>
                </div>

                <div
                    class="fixed bottom-4 left-0 flex w-full pr-2 center md:bottom-6 md:pl-64 lg:pl-72"
                    :class="inTabbar($route.path) ? 'bottom-16' : 'bottom-0'"
                >
                    <div
                        class="mx-4 flex max-w-prose flex-1 items-center justify-between rounded-xl bg-background p-4 dark:md:bg-background-soft"
                        style="box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.05)"
                    >
                        <div>
                            <div>
                                <span class="text-sm font-bold">需支付：</span>
                                <span class="mr-2 text-2xl font-bold text-red-500">
                                    ¥{{ activePlan?.cost || '--' }}
                                </span>
                                <span class="inline-flex items-center text-xs">
                                    <span>总得 </span>
                                    <span class="mx-1 text-sm font-bold text-primary">
                                        {{
                                            activePlan?.gift == 1
                                                ? useSum(
                                                      Number(activePlan?.power),
                                                      Number(activePlan?.gift_power)
                                                  ).value
                                                : activePlan?.power || '--'
                                        }}
                                    </span>

                                    <span>{{ appStore.siteConfig?.unit.power }}</span>
                                    <UPopover mode="click">
                                        <span class="ml-1 text-xs text-foreground/50">明细</span>

                                        <template #panel>
                                            <div class="p-2">
                                                <p>
                                                    <span> 充值数量：</span>
                                                    <span class="text-primary">
                                                        {{ Number(activePlan?.power) }}
                                                    </span>
                                                </p>
                                                <p v-if="activePlan?.gift == 1">
                                                    <span> 加赠数量：</span>
                                                    <span class="text-green-500">
                                                        {{ Number(activePlan?.gift_power) }}
                                                    </span>
                                                </p>
                                            </div>
                                        </template>
                                    </UPopover>
                                </span>
                            </div>
                            <span class="text-xs">
                                支付即代表你同意<NuxtLink
                                    class="text-primary"
                                    to="/agreement?type=agreement&item=recharge"
                                    target="_blank"
                                >
                                    《充值协议》
                                </NuxtLink>
                            </span>
                        </div>
                        <UButton size="lg" :disabled="activePlan === null" @click="handlePay"
                            >立即支付</UButton
                        >
                    </div>
                </div>
            </div>
            <ProModal v-model="showPayCode" title="微信支付">
                <div class="flex-col center">
                    <VueQrcode
                        v-if="payCode"
                        class="size-52 rounded-lg"
                        :value="payCode"
                        :color="{ dark: '#000000', light: '#ffffff' }"
                        type="image/png"
                    />
                    <p class="mt-2 flex items-center text-xl font-bold text-red-500">
                        <span class="text-base text-foreground">合计：</span>¥{{
                            activePlan?.cost || '--'
                        }}
                    </p>
                    <p class="mt-2">请使用微信扫码支付</p>
                    <p class="mt-4 text-xs text-foreground/60">
                        如遇问题无法解决时，请联系站点管理员
                    </p>
                </div>
            </ProModal>
        </div>
    </PageContainer>
</template>

<script lang="ts" setup>
import VueQrcode from 'vue-qrcode';

import { apiGetPolicy } from '~/api/common';
import { apiGetPayWayList, apiPostPayStatus, apiPostPrePay, apiPostRecharge } from '~/api/pay';
import { apiGetPowerPlanList } from '~/api/power';
import FlashIcon from '~/assets/icons/flash.png';
import { PayStatusEnum } from '~/enums/variableEnum';

import UserCard from './components/user-card.vue';

useHead({
    title: '充值中心',
});

definePageMeta({
    layout: 'profile',
});

const userStore = useUserStore();
const appStore = useAppStore();
const activePlan = ref<PowerPackage | null>(null);
const activePayway = ref<number>(0);
const payWayList = ref<PayWayItem[]>([]);
const payCode = ref<string>('');
const showPayCode = ref<boolean>(false);
const powerDescribe = ref<string>('');
let clearPolling: () => void;

const { getList, list, state } = apiGetPowerPlanList();

onMounted(async () => {
    payWayList.value = await apiGetPayWayList();
    await getList();
    if (list.value.length > 0) {
        activePlan.value = list.value[0];
    }
    powerDescribe.value = (await apiGetPolicy('currency')).content;
});

const selectHandle = (item: PowerPackage) => {
    activePlan.value = item;
};

const handlePay = useDebounceFn(async () => {
    const recharge = await apiPostRecharge({ package: activePlan.value?.id as number });
    const prepay = await apiPostPrePay({
        from: 'recharge',
        order_id: recharge.order_id,
        pay_way: '2',
    });
    payCode.value = prepay.config;
    showPayCode.value = true;

    const { start, clear } = usePollingTask(async (stop) => {
        apiPostPayStatus({
            from: 'recharge',
            order_id: recharge.order_id,
        }).then((res) => {
            if (res.pay_status === PayStatusEnum.PAID) {
                stop();
                showPayCode.value = false;
                useMessage().success('支付成功');
                userStore.getUser();
            }
        });
    });
    clearPolling = clear;
    start();
});

onBeforeUnmount(() => {
    if (clearPolling) {
        clearPolling();
    }
});
</script>
