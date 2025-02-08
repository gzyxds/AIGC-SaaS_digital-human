<!-- 我的声音/声音合成 -->
<script setup lang="ts">
import {
    apiGetCloneVoiceList,
    apiGetVoiceList,
    apiPostCloneVoiceDelete,
    apiPostVoiceDelete,
} from '@/api/audio';
import { useAudio } from '@/composables/useAudio';
import { ProfileTabEnum, TaskStatusEnum } from '@/enums/variableEnum';
import { useCopy } from '@/utils/helper';
import { isEqual } from 'lodash-es';
import { ref } from 'vue';

const props = defineProps({
    type: {
        type: Number,
    },
});

const { play, pause, stop, isPlaying } = useAudio();
// 当前播放id
const playId = ref();

// 当前操作id
const actionId = ref<number>();
const actionName = ref<string>();
const actionUrl = ref<string>();
const actionStatus = ref<number>();

const actionSheetRef = ref();

// 分页
const paging = shallowRef<any>(null);
const list = ref<any>([]);

// 轮询
let clearPolling: () => void;

const queryList = async (page_no: number, page_size: number) => {
    try {
        if (props.type === ProfileTabEnum.MySound) {
            const { lists } = await apiGetVoiceList({
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
        } else {
            const { lists } = await apiGetCloneVoiceList({
                page_no,
                page_size,
            });
            paging.value.complete(lists);
            console.log('page_no * page_size', page_no * page_size);

            // 有生成中的任务就开启轮询
            const loadingNum = list.value.filter((item) => isTaskPendding(item.status)).length;
            if (clearPolling) {
                clearPolling();
            }
            if (loadingNum) {
                polling(page_no * page_size);
            }
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
        let pollingLists = [];
        if (props.type === ProfileTabEnum.MySound) {
            const res = await apiGetVoiceList({
                page_no: 1,
                page_size,
            });
            pollingLists = res.lists;
        } else {
            const res = await apiGetCloneVoiceList({
                page_no: 1,
                page_size,
            });
            pollingLists = res.lists;
        }
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
const openPop = (id: number, name: string, url: string, status: number) => {
    actionId.value = id;
    actionName.value = name;
    actionUrl.value = url;
    actionStatus.value = status;
    actionSheetRef.value.open();
};
// 跳转页面
const toPage = () => {
    uni.navigateTo({
        url: '/pages/create_sound/index',
    });
};

// 删除
const handleDelete = async () => {
    if (props.type === ProfileTabEnum.MySound) {
        await apiPostVoiceDelete({ id: actionId.value! });
    } else {
        await apiPostCloneVoiceDelete({ id: actionId.value! });
    }
    paging.value.refresh();
};

// 使用
const handleUse = () => {
    uni.navigateTo({
        url: `/pages/digital_people/index?soundId=${actionId.value}&soundName=${actionName.value}`,
    });
};

// 下载
const handleDownload = async () => {
    // #ifdef MP-WEIXIN
    if (!actionUrl.value) return useToast('暂无下载链接', { duration: 3000 });
    await useCopy(actionUrl.value!);
    useToast('已经复制音频链接，请到浏览器下载', { duration: 3000 });
    // #endif
    // #ifdef H5
    console.log('下载');
    // #endif
};

// 播放
const handlePlay = (id: number, url: string, status: string) => {
    if (Number(status) !== Number(TaskStatusEnum.SUCCESS)) return;

    if (playId.value && playId.value !== id) {
        // 不是当前播放
        playId.value = id;
        stop();
        play(url);
    } else {
        // 是当前播放
        playId.value = id;
        play(url);
    }
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
            <view
                v-for="item in list"
                :key="item.id"
                class="mb-[24rpx] flex items-center justify-between rounded-[28rpx] px-[36rpx] py-[34rpx]"
                bg-background
            >
                <view class="flex items-center">
                    <!-- 播放图标-正在播放 -->
                    <image
                        v-if="isPlaying && playId === item.id"
                        src="@/static/icons/icon-sound-play.png"
                        class="h-[80rpx] w-[80rpx] flex-none"
                        @click="pause"
                    />
                    <!-- 播放图标-未播放 -->
                    <view v-else relative @click="handlePlay(item.id, item.voice_url, item.status)">
                        <!-- 成功 -->
                        <image src="@/static/icons/icon-sound.png" class="h-10 w-10 flex-none" />
                        <!-- 失败 -->
                        <view
                            v-if="Number(item.status) === Number(TaskStatusEnum.FAIL)"
                            position="absolute left-0 top-0"
                            h-10
                            w-10
                            center
                            rounded-full
                            text-base
                            font-600
                            backdrop-blur-sm
                            class="bg-foreground/10"
                        >
                            失败
                        </view>
                        <!-- todo:进行中 -->
                        <view
                            v-if="Number(item.status) === Number(TaskStatusEnum.PENDDING)"
                            position="absolute left-0 top-0"
                            h-10
                            w-10
                            center
                            rounded-full
                            text-base
                            backdrop-blur-sm
                            class="bg-foreground/50"
                        >
                            <view i-tabler:loader-2 class="animate-spin text-primary" />
                        </view>
                    </view>

                    <view class="ml-[32rpx]">
                        <view text="base foreground" line-clamp-1 mb-1.5 font-500>
                            {{ type === ProfileTabEnum.MySound ? item?.name : item?.title }}
                        </view>
                        <view text="xs foreground-placeholder">
                            {{ item?.create_time }}
                        </view>
                    </view>
                </view>
                <!-- 右侧操作项 -->
                <view
                    class="rounded-[28rpx]"
                    min-h-56rpx
                    min-w-56rpx
                    center
                    bg-background-muted
                    @click.stop="openPop(item.id, item?.name || '', item?.voice_url, item?.status)"
                >
                    <image src="@/static/icons/icon-setting.png" class="h-[44rpx] w-[44rpx]" />
                </view>
            </view>
            <template #empty>
                <view h-full w-full>
                    <empty-status
                        button-text="创建声音"
                        :show-button="type === ProfileTabEnum.MySound"
                        @handle="toPage"
                    />
                </view>
            </template>
        </z-paging>

        <!-- 操作弹窗 -->
        <action-sheet
            ref="actionSheetRef"
            :actions="[
                {
                    title: '使用声音',
                    type: 'primary',
                    show:
                        props.type === ProfileTabEnum.MySound &&
                        Number(actionStatus) === Number(TaskStatusEnum.SUCCESS),
                    click: handleUse,
                },
                {
                    title: '下载',
                    click: handleDownload,
                    show: Number(actionStatus) === Number(TaskStatusEnum.SUCCESS),
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
