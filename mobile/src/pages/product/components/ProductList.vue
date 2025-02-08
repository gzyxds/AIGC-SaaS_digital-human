<script setup lang="ts">
import { apiGetAiAvatarList, apiPostAiAvatarDelete } from '@/api/avatar';
import { TaskStatusEnum } from '@/enums/variableEnum';
import { isEqual } from 'lodash-es';
import { ref } from 'vue';

const props = defineProps<{
    type: number | string; // ''-全部 1-完成 2-进行中 3-失败
}>();

// 操作弹窗ref
const actionSheetRef = ref();

// 当前操作id
const actionId = ref<number>();
const actionVideo = ref<string>();
const actionStatus = ref<string>();

// 分页
const paging = shallowRef<any>(null);
const list = ref<any>([]);

// 轮询
let clearPolling: () => void;

const queryList = async (page_no: number, page_size: number) => {
    try {
        const { lists } = await apiGetAiAvatarList({
            page_no,
            page_size,
            status: props.type,
        });
        paging.value.complete(lists);

        // 有生成中的任务就开启轮询
        const loadingNum = list.value.filter((item) => isTaskPendding(item.status)).length;
        if (clearPolling) {
            clearPolling();
        }
        if (loadingNum) {
            polling(page_no * page_size);
        }
    } catch (e) {
        console.log('报错=>', e);
        paging.value.complete(false);
    }
};

// 轮询请求
const polling = async (page_size: number) => {
    // 列表生成中的任务数量
    console.log('开始轮询');
    const { start, clear } = usePollingTask(async (stop) => {
        const { lists: pollingLists } = await apiGetAiAvatarList({
            page_no: 1,
            page_size,
            status: props.type,
        });

        // 更新数据
        pollingLists.forEach((item: any, index) => {
            // 原列表生成中的任务 且 状态发生改变,更新数据
            if (
                isTaskPendding(list.value[index].status) &&
                !isEqual(item.status, list.value[index].status)
            ) {
                list.value[index] = item;
            }
        });
        // 列表生成中的任务数量
        const loadingNum = pollingLists.filter((item) => isTaskPendding(item.status)).length;
        if (loadingNum === 0) {
            stop();
        }
    });
    clearPolling = clear;
    start();
};

// 删除
const handleDelete = async () => {
    await apiPostAiAvatarDelete({ id: actionId.value! });
    useToast('删除成功');
    paging.value.refresh();
};

// 下载
const handleDownload = () => {
    const downloadTask = useDownloadFile({
        type: 'video',
        fileUrl: actionVideo.value!,
        success: (res) => {
            console.log('保存成功', res);
        },
        fail: (err) => {
            console.log('保存失败', err);
        },
    });

    downloadTask?.onProgressUpdate((res) => {
        uni.showLoading({
            title: '下载进度：' + `${res.progress}%`,
            mask: true,
        });
        console.log('下载进度：', `${res.progress}%`);
        // console.log('已下载：', res.totalBytesWritten);
        // console.log('总共：', res.totalBytesExpectedToWrite);
        if (res.progress === 100) {
            uni.hideLoading();
        }
    });
};

function openPop(id: number, video: string, status: string) {
    actionId.value = id;
    actionVideo.value = video;
    actionStatus.value = status;
    actionSheetRef.value.open();
}

// 去创建
const toPage = () => {
    uni.navigateTo({
        url: '/pages/digital_people/index',
    });
};

onShow(() => {
    nextTick(() => {
        paging.value?.refresh();
    });
});

onBeforeUnmount(() => {
    // 清除轮询
    if (clearPolling) {
        clearPolling();
    }
});
</script>

<template>
    <view class="list-height">
        <z-paging ref="paging" v-model="list" :fixed="false" height="100%" @query="queryList">
            <view class="grid grid-cols-2 gap-22rpx px-3">
                <product-card
                    v-for="item in list"
                    :key="item.id"
                    :images="item.cover"
                    :time="item.duration"
                    :url="item.resultFile"
                    :status="item.status"
                    :fail-reason="item.fail_reason"
                >
                    <template #content>
                        <view class="line-clamp-1 mb-1 px-[28rpx]" text="sm foreground">
                            {{ item.title }}
                        </view>

                        <view class="flex items-center justify-between px-[16rpx]">
                            <!-- 左侧声音 -->
                            <view
                                class="mr-2 flex items-center rounded-[28rpx] bg-background-muted py-[8rpx] pl-[8rpx] pr-[16rpx]"
                            >
                                <image
                                    src="@/static/icons/icon-sound.png"
                                    class="h-[40rpx] w-[40rpx] flex-none"
                                />
                                <view text="xs foreground-muted" line-clamp-1 ml-1>
                                    {{ item?.voice_name }}
                                </view>
                            </view>
                            <!-- 右侧操作项 -->
                            <view
                                class="flex items-center justify-center rounded-[28rpx] bg-background-muted"
                                min-h-56rpx
                                min-w-56rpx
                                @click="openPop(item.id, item.resultFile, item.status)"
                            >
                                <image
                                    src="@/static/icons/icon-setting.png"
                                    class="h-[40rpx] w-[40rpx]"
                                />
                            </view>
                        </view>
                    </template>
                </product-card>
            </view>

            <template #empty>
                <view h-full w-full>
                    <empty-status button-text="创建数字人" @handle="toPage" />
                </view>
            </template>
        </z-paging>

        <!-- 操作弹窗 -->
        <action-sheet
            ref="actionSheetRef"
            :actions="[
                {
                    title: '下载',
                    type: 'primary',
                    show: Number(actionStatus) === Number(TaskStatusEnum.SUCCESS),
                    click: handleDownload,
                },
                // {
                //     title: '创建副本',
                //     click: () => {
                //         console.log('创建副本');
                //     },
                // },
                {
                    title: '删除',
                    type: 'danger',
                    click: handleDelete,
                },
            ]"
        />
    </view>
</template>

<style lang="scss" scoped>
.list-height {
    height: calc(100vh - 202rpx - env(safe-area-inset-bottom));
}
</style>
