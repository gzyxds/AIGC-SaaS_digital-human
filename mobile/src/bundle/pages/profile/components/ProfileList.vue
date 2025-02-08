<script setup lang="ts">
import { apiGetAvatarVideoList, apiPostAvatarVideoDelete } from '@/api/avatar';
import { usePollingTask } from '@/composables/usePollingTask';
import { useDownloadFile } from '@/composables/useRequest';
import { TaskStatusEnum } from '@/enums/variableEnum';
import { isEqual } from 'lodash-es';
import { ref } from 'vue';

const actionSheetRef = ref();
// 当前操作id
const actionId = ref<number>();
const actionImage = ref<string>();
const actionVideo = ref<string>();
const actionStatus = ref<string>();

// 轮询
let clearPolling: () => void;

// 分页
const paging = shallowRef<any>(null);
const list = ref<any>([]);

const queryList = async (page_no: number, page_size: number) => {
    try {
        const { lists } = await apiGetAvatarVideoList({
            page_no,
            page_size,
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
        const { lists: pollingLists } = await apiGetAvatarVideoList({
            page_no: 1,
            page_size,
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

// 打开弹窗
const openPop = (id: number, cover: string, video: string, status: string) => {
    actionId.value = id;
    actionImage.value = cover;
    actionVideo.value = video;
    actionStatus.value = status;
    actionSheetRef.value.open();
};
// 跳转页面
const toPage = () => {
    uni.navigateTo({
        url: '/pages/create_profile/index',
    });
};

// 删除
const handleDelete = async () => {
    await apiPostAvatarVideoDelete({ id: actionId.value! });
    paging.value.refresh();
};

// 使用
const handleUse = () => {
    uni.navigateTo({
        url: `/pages/digital_people/index?profileId=${actionId.value}&profileImage=${actionImage.value}`,
    });
};

// 下载
const handleDownload = () => {
    // console.log('下载');
    const downloadTask = useDownloadFile({
        type: 'video',
        fileUrl: actionVideo.value!,
        success: (res) => {
            console.log(res);
        },
        fail: (err) => {
            console.log(err);
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

onBeforeUnmount(() => {
    // 清除轮询
    if (clearPolling) {
        clearPolling();
    }
});
</script>

<template>
    <view class="h-full">
        <z-paging ref="paging" v-model="list" :fixed="false" height="100%" @query="queryList">
            <view grid grid-cols-2 gap-x-22rpx>
                <product-card
                    v-for="item in list"
                    :key="item.id"
                    :images="item.cover"
                    :time="item.duration"
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
                                class="flex items-center justify-center rounded-[28rpx] bg-background-muted"
                                min-h-56rpx
                                min-w-56rpx
                                @click.stop="
                                    openPop(item.id, item.cover, item.video_url, item.status)
                                "
                            >
                                <image
                                    src="@/static/icons/icon-setting.png"
                                    class="h-[44rpx] w-[44rpx]"
                                />
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

        <!-- 操作弹窗 -->
        <action-sheet
            ref="actionSheetRef"
            :actions="[
                {
                    title: '使用形象',
                    type: 'primary',
                    show: Number(actionStatus) === Number(TaskStatusEnum.SUCCESS),
                    click: handleUse,
                },
                {
                    title: '下载',
                    show: Number(actionStatus) === Number(TaskStatusEnum.SUCCESS),
                    click: handleDownload,
                },
                {
                    title: '删除',
                    type: 'danger',
                    click: handleDelete,
                },
            ]"
        />
    </view>
</template>

<style lang="scss" scoped></style>
