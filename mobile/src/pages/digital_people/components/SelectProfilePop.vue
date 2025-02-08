<!-- 选择形象弹窗 -->
<script lang="ts" setup>
import { apiGetAvatarVideoList } from '@/api/avatar';
import { TaskStatusEnum } from '@/enums/variableEnum';
import { ref } from 'vue';

const props = defineProps<{
    profileId: number; // 形象id
    profileImage: string; // 形象图片
}>();
const emits = defineEmits<{
    (event: 'update:profileId', id: number): void;
    (event: 'update:profileImage', image: string): void;
}>();

// 弹窗Ref
const popupRef = ref();

// 选中的形象
const selectProfileId = ref<number>();
const selectProfileImage = ref<string>();

// 分页
const paging = shallowRef<any>(null);
const list = ref<any>([]);

const queryList = async (page_no: number, page_size: number) => {
    try {
        const { lists } = await apiGetAvatarVideoList({
            page_no,
            page_size,
            status: Number(TaskStatusEnum.SUCCESS),
        });
        paging.value.complete(lists);
    } catch (e) {
        console.log('报错=>', e);
        paging.value.complete(false);
    }
};

// 选中形象
const handleSelect = (id: number, cover: string) => {
    console.log('radioChange id=> ', id);
    console.log('radioChange cover=> ', cover);
    selectProfileId.value = id;
    selectProfileImage.value = cover;
};

// 确定选择
const handleConfirm = () => {
    if (selectProfileId.value) {
        emits('update:profileId', selectProfileId.value!);
        emits('update:profileImage', selectProfileImage.value!);
    }
    // 关闭弹窗
    popupRef.value.close();
};

// 按钮操作
const toPage = () => {
    console.log('按钮操作');
    // 关闭弹窗
    popupRef.value.close();

    uni.navigateTo({
        url: '/pages/create_profile/index',
    });
};

onMounted(() => {
    nextTick(() => {
        paging.value?.refresh();
    });
});

defineExpose({
    // 打开弹窗
    open: (id?: number) => {
        if (id) {
            console.log('id', id);
            selectProfileId.value = Number(id);
        }

        popupRef.value.open();
    },
});
</script>

<template>
    <!-- 选择弹窗 -->
    <uni-popup ref="popupRef" border-radius="24rpx" type="bottom">
        <view class="select-Pop h-[1240rpx] rounded-t-[var(--ui-radius)]" bg-background>
            <view class="mx-40rpx mb-28rpx pt-40rpx text-foreground-muted font-500">
                选择形象
            </view>
            <!-- 列表 -->
            <scroll-view :scroll-y="true" class="h-full">
                <view class="scroll-y-box h-full px-[24rpx]">
                    <z-paging
                        ref="paging"
                        v-model="list"
                        :fixed="false"
                        height="100%"
                        z-paging-refresh="false"
                        @query="queryList"
                    >
                        <!-- 形象卡片 -->
                        <view class="grid grid-cols-2 gap-x-22rpx">
                            <product-card
                                v-for="item in list"
                                :key="item.id"
                                :images="item.cover"
                                :time="item.duration"
                                bg-color="bg-background-muted"
                                :url="item.video_url"
                                :status="item.status"
                            >
                                <template #content>
                                    <view between pl-3.5 pr-2 text="sm foreground">
                                        <!-- 左侧 -->
                                        <view text="sm foreground" line-clamp-1>
                                            {{ item.name }}
                                        </view>
                                        <!-- 右侧操作项 -->
                                        <view
                                            between
                                            pl-3.5
                                            pr-2
                                            text="sm foreground"
                                            @click="handleSelect(item.id, item.cover)"
                                        >
                                            <view
                                                class="h-[48rpx] w-[48rpx] flex items-center justify-center border-[4rpx] rounded-[30rpx] border-solid"
                                                :class="
                                                    Number(item.id) === Number(selectProfileId)
                                                        ? 'bg-primary border-primary'
                                                        : 'border-foreground'
                                                "
                                            >
                                                <image
                                                    v-if="
                                                        Number(item.id) === Number(selectProfileId)
                                                    "
                                                    src="@/static/icons/icon-select.png"
                                                    class="h-[44rpx] w-[44rpx]"
                                                />
                                            </view>
                                        </view>
                                    </view>
                                </template>
                            </product-card>
                        </view>

                        <template #empty>
                            <view h-full w-full>
                                <empty-status button-text="创建形象" @handle="toPage" />
                            </view>
                        </template>
                    </z-paging>
                </view>
            </scroll-view>

            <!-- 底部按钮 -->
            <view
                v-if="list.length"
                class="footer fixed bottom-0 left-0 z-10 w-full px-3"
                bg-background
            >
                <button type="primary" @click="handleConfirm">使用形象</button>
            </view>
        </view>
    </uni-popup>
</template>

<style lang="scss" scoped>
.select-Pop {
    .scroll-y-box {
        padding-bottom: calc(env(safe-area-inset-bottom) + 240rpx);
    }

    .footer {
        padding-bottom: calc(env(safe-area-inset-bottom) + 24rpx);
    }
}
</style>
