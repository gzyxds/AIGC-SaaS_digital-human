<!-- 选择弹窗 音色 -->
<script lang="ts" setup>
import { apiGetVoiceList } from '@/api/audio';
import { useAudio } from '@/composables/useAudio';
import { TaskStatusEnum } from '@/enums/variableEnum';
import { ref } from 'vue';

const props = defineProps<{
    soundId: number; // 音色id
    soundName: string; // 音色名称
}>();
const emits = defineEmits<{
    (event: 'update:soundId', value: number): void;
    (event: 'update:soundName', value: string): void;
}>();

// 弹窗Ref
const popupRef = ref();

// 选中的音色
const selectSoundId = ref<number>();
const selectSoundName = ref<string>();

const { play, pause, stop, isPlaying } = useAudio();
// 当前播放id
const playId = ref();

// 分页
const paging = shallowRef<any>(null);
const list = ref<any>([]);

const queryList = async (page_no: number, page_size: number) => {
    try {
        const { lists } = await apiGetVoiceList({
            page_no,
            page_size,
            status: TaskStatusEnum.SUCCESS,
        });
        paging.value.complete(lists);
    } catch (e) {
        console.log('报错=>', e);
        paging.value.complete(false);
    }
};

// 选中形象/音色
const handleSelect = (id: number, name: string) => {
    console.log('radioChange => ', id, name);
    selectSoundId.value = id;
    selectSoundName.value = name;
};

// 确定选择
const handleConfirm = () => {
    if (selectSoundId.value) {
        emits('update:soundId', selectSoundId.value!);
        emits('update:soundName', selectSoundName.value!);
    }

    // 关闭弹窗
    popupRef.value.close();
};

// 去创建
const toPage = () => {
    // 关闭弹窗
    popupRef.value.close();

    uni.navigateTo({
        url: '/pages/create_sound/index',
    });
};

// 播放
const handlePlay = (id: number, url: string) => {
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
            selectSoundId.value = Number(id);
        }
        popupRef.value.open();
    },
});
</script>

<template>
    <uni-popup ref="popupRef" border-radius="24rpx" type="bottom">
        <view class="select-Pop h-[1240rpx] rounded-t-[var(--ui-radius)]" bg-background>
            <view class="mx-40rpx mb-28rpx pt-40rpx text-foreground-muted font-500">
                选择音色
            </view>
            <!-- 列表 -->
            <scroll-view :scroll-y="true" class="h-full">
                <!-- 音色卡片 -->
                <view class="scroll-y-box h-full px-[24rpx]">
                    <z-paging
                        ref="paging"
                        v-model="list"
                        :fixed="false"
                        height="100%"
                        z-paging-refresh="false"
                        @query="queryList"
                    >
                        <view
                            v-for="item in list"
                            :key="item.id"
                            class="mb-[24rpx] flex items-center justify-between rounded-[28rpx] bg-background-muted px-[36rpx] py-[34rpx]"
                            @click="handleSelect(item.id, item.name)"
                        >
                            <view class="flex items-center">
                                <!-- 播放图标 -->
                                <image
                                    v-if="isPlaying && playId === item.id"
                                    src="@/static/icons/icon-sound-play.png"
                                    class="h-[80rpx] w-[80rpx] flex-none"
                                    @click.stop="pause"
                                />
                                <image
                                    v-else
                                    src="@/static/icons/icon-sound.png"
                                    class="h-[80rpx] w-[80rpx] flex-none"
                                    @click.stop="handlePlay(item.id, item.voice_url)"
                                />
                                <view class="ml-[32rpx]">
                                    <view line-clamp-1 mb-12rpx>
                                        {{ item?.name }}
                                    </view>
                                    <view text="xs foreground-placeholder">
                                        {{ item?.create_time }}
                                    </view>
                                </view>
                            </view>
                            <!-- 右侧操作项 -->
                            <view
                                class="h-[48rpx] w-[48rpx] flex items-center justify-center border-[4rpx] rounded-[30rpx] border-solid"
                                :class="
                                    Number(item.id) === Number(selectSoundId)
                                        ? 'bg-primary border-primary'
                                        : 'border-foreground'
                                "
                            >
                                <image
                                    v-if="Number(item.id) === Number(selectSoundId)"
                                    src="@/static/icons/icon-select.png"
                                    class="h-[44rpx] w-[44rpx]"
                                />
                            </view>
                        </view>
                        <template #empty>
                            <view h-full w-full>
                                <empty-status button-text="去创建" @handle="toPage" />
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
                <button type="primary" @click="handleConfirm">使用音色</button>
            </view>
        </view>
    </uni-popup>
</template>

<style lang="scss" scoped>
.select-Pop {
    .scroll-y-box {
        padding-bottom: calc(env(safe-area-inset-bottom) + 240rpx);
    }

    .select-item:nth-child(2n) {
        margin-right: 0rpx;
    }

    .footer {
        padding-bottom: calc(env(safe-area-inset-bottom) + 24rpx);
    }
}
</style>
