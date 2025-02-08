<template>
    <div v-if="$slots.default">
        <audio
            v-if="audioSrc && show"
            ref="audioRef"
            :src="audioSrc"
            @timeupdate="updateProgress"
            @loadedmetadata="updateDuration"
            @ended="handleEnded"
        />
        <slot :status="status" :play="togglePlay" />
    </div>
    <div
        v-else
        class="flex w-full min-w-40 flex-col gap-2"
        :class="{ 'pointer-events-none absolute -z-50 h-0 w-0 overflow-hidden opacity-0': hidden }"
    >
        <ClientOnly>
            <audio
                v-if="audioSrc"
                ref="audioRef"
                :src="audioSrc"
                @timeupdate="updateProgress"
                @loadedmetadata="updateDuration"
                @ended="handleEnded"
            />
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1">
                    <UTooltip :text="['播放', '暂停', '重播'][status]">
                        <div
                            class="cursor-pointer rounded-full bg-primary/5 p-1 center hover:bg-primary/10"
                            :class="{ 'music-player !bg-primary text-white': status === 1 }"
                            @click="togglePlay()"
                        >
                            <UIcon
                                class="cursor-pointer"
                                :name="
                                    ['tabler:player-play', 'tabler:player-pause', 'tabler:reload'][
                                        status
                                    ]
                                "
                                :size="sizeMap[size]"
                            />
                        </div>
                    </UTooltip>

                    <div v-if="src" class="ml-2 whitespace-nowrap" :class="fontSizeMap[size]">
                        {{ formatSecond(currentTime) }} / {{ formatSecond(duration) }}
                    </div>
                    <div
                        v-else
                        class="ml-2 whitespace-nowrap text-red-500"
                        :class="fontSizeMap[size]"
                    >
                        暂无音源
                    </div>
                </div>

                <div class="flex items-center">
                    <!-- <a
                        v-if="download"
                        :href="src"
                        target="_blank"
                        :download="audioSrc.split('/')[audioSrc.split('/').length - 1]"
                        class="cursor-pointer rounded-full p-1 center hover:bg-gray-600/5"
                    >
                        <UIcon name="tabler:cloud-download" :size="sizeMap[size]" />
                    </a> -->
                    <a
                        v-if="download"
                        class="cursor-pointer rounded-full p-1 center hover:bg-gray-600/5"
                        @click="downloadAudio"
                    >
                        <UIcon name="tabler:cloud-download" :size="sizeMap[size]" />
                    </a>

                    <UPopover mode="hover" :close-delay="200" :popper="{ placement: 'bottom-end' }">
                        <div
                            class="translate-y-[2px] cursor-pointer rounded-full p-1 center hover:bg-gray-600/5"
                        >
                            <UIcon name="tabler:volume" :size="sizeMap[size]" />
                        </div>

                        <template #panel>
                            <div class="flex w-48 items-center gap-2 p-4">
                                <URange
                                    v-model="volume"
                                    :size="rangeSizeMap[size]"
                                    :max="1"
                                    :step="0.01"
                                    @change="changeVolume"
                                />
                                <span class="min-w-7 text-xs">
                                    {{ `${NP.times(volume, 100)}%` }}
                                </span>
                            </div>
                        </template>
                    </UPopover>
                </div>
            </div>

            <div class="pb-1 pl-1.5">
                <URange
                    v-model="currentTime"
                    :size="rangeSizeMap[size]"
                    :max="duration"
                    :step="0.001"
                    @change="seekAudio"
                    @input="isSeeking = true"
                />
            </div>
        </ClientOnly>
    </div>
</template>

<script lang="ts" setup>
import NP from 'number-precision';
type SizeType = 'xs' | 'sm' | 'md' | 'lg' | 'xl' | '2xl';

enum PlayStatusEnum {
    PAUSED = 0,
    PLAYING = 1,
    ENDED = 2,
}

const props = withDefaults(
    defineProps<{ src?: string; size?: SizeType; hidden?: boolean; download?: boolean }>(),
    {
        src: '',
        size: 'sm',
        hidden: false,
        download: true,
    }
);

const emit = defineEmits<{
    getDuration: [value: number];
    onProgress: [value: number];
    onEnded: [];
}>();

// 状态变量
/** 0-停止 1-播放中 2-播放完成 */
const show = ref<boolean>(true);
const audioSrc = ref<string>(props.src);
const status = ref<PlayStatusEnum>(PlayStatusEnum.PAUSED);
const audioRef = ref<HTMLAudioElement | null>(null);
const duration = ref<number>(0);
const currentTime = ref<number>(0);
const volume = ref<number>(1);
const isSeeking = ref<boolean>(false);
const sizeMap: Record<SizeType, number> = { xs: 16, sm: 18, md: 20, lg: 22, xl: 24, '2xl': 26 };
const fontSizeMap: Record<SizeType, string> = {
    xs: 'text-xs',
    sm: 'text-sm',
    md: 'text-md',
    lg: 'text-lg',
    xl: 'text-xl',
    '2xl': 'text-2xl',
};
const rangeSizeMap: Record<SizeType, SizeType> = {
    xs: 'xs',
    sm: 'xs',
    md: 'sm',
    lg: 'md',
    xl: 'lg',
    '2xl': 'xl',
};

// 播放、暂停功能
const togglePlay = async (src?: string) => {
    if (src) {
        audioSrc.value = src || props.src;
        await nextTick();
    }
    return {
        [PlayStatusEnum.PAUSED]: () => {
            if (audioRef.value) {
                audioRef.value.play();
                status.value = PlayStatusEnum.PLAYING;
            }
        },
        [PlayStatusEnum.PLAYING]: () => {
            if (audioRef.value) {
                if (props.hidden) {
                    handleEnded();
                } else {
                    audioRef.value.pause();
                    status.value = PlayStatusEnum.PAUSED;
                }
            }
        },
        [PlayStatusEnum.ENDED]: () => {
            if (audioRef.value) {
                audioRef.value.play();
                status.value = PlayStatusEnum.PLAYING;
            }
        },
    }[status.value]();
};

/** 播放结束事件 */
const handleEnded = async () => {
    if (props.hidden) {
        audioRef.value?.pause();
        status.value = PlayStatusEnum.PAUSED;
        show.value = false;
        await nextTick(() => (show.value = true));
    } else {
        status.value = PlayStatusEnum.ENDED;
    }

    emit('onEnded');
};

/** 更新进度条和时间 */
const updateProgress = () => {
    if (audioRef.value && !isSeeking.value) {
        currentTime.value = audioRef.value.currentTime;
        emit('onProgress', audioRef.value.currentTime);
    }
};

/** 设置音频进度 */
const seekAudio = async (progress: number) => {
    await nextTick();
    if (audioRef.value) {
        if (progress) {
            currentTime.value = Number(progress);
            audioRef.value.currentTime = Number(progress);
        } else {
            audioRef.value.currentTime = currentTime.value;
        }
        isSeeking.value = false;
    }
};
/** 获取音频总时长 */
const updateDuration = () => {
    if (audioRef.value) {
        duration.value = audioRef.value.duration;
        emit('getDuration', duration.value);
    }
};

/** 控制音量 */
const changeVolume = (e: number) => {
    if (audioRef.value) {
        audioRef.value.volume = volume.value;
    }
};

/** 下载音频 */
const downloadAudio = async () => {
    await downloadFile({ src: audioSrc.value });
};
</script>

<style lang="scss" scoped>
/* 播放器的样式 */
.music-player {
    border-radius: 50%; /* 变成圆形 */
    position: relative;
    z-index: 999;
}

/* 波纹效果 */
.music-player::before,
.music-player::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0); /* 初始大小为0 */
    animation: ripple 2s infinite;
    z-index: -1;
    @apply bg-primary/80;
}

/* 设置不同伪元素的动画延迟，形成交替波纹 */
.music-player::after {
    animation-delay: 1s;
}

/* 波纹动画 */
@keyframes ripple {
    0% {
        transform: translate(-50%, -50%) scale(0.5);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(1.6); /* 最终放大 */
        opacity: 0; /* 最终透明 */
    }
}
</style>
