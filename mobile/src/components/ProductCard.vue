<!-- 作品、形象卡片 -->
<script setup lang="ts">
import { TaskStatusEnum } from '@/enums/variableEnum';

const props = withDefaults(
    defineProps<{
        images?: string; // 图片
        time?: string; // 时长
        content?: string; // 内容-name
        bgColor?: string; // 背景颜色
        url?: string; // 视频url
        status?: number | string; // 状态
        imageWidth?: string; // 图片宽
        imageHeight?: string; // 图片高
        failReason?: string; // 失败原因
    }>(),
    {
        imageWidth: '340rpx',
        imageHeight: '452rpx',
    }
);

// 播放视频
const playVideo = () => {
    console.log('视频状态=>', Number(props.status));
    console.log('Number(TaskStatusEnum.SUCCESS)=>', Number(TaskStatusEnum.SUCCESS));
    console.log('对比结果=>', Number(props.status) === Number(TaskStatusEnum.SUCCESS));

    if (Number(props.status) === Number(TaskStatusEnum.SUCCESS)) {
        if (props.url) {
            uni.navigateTo({
                url: `/bundle/pages/play_video/index?videoUrl=${props.url}`,
            });
        } else {
            console.log('视频地址为空');
        }
    } else {
        console.log('视频生成中或者合成失败');
    }
};
</script>

<template>
    <view
        :class="`card-item rounded-[28rpx] ${props.bgColor || 'bg-background'}`"
        mb-3
        :style="{ width: props.imageWidth }"
    >
        <view class="relative" :style="{ width: props.imageWidth, height: props.imageHeight }">
            <view
                :style="{ width: props.imageWidth, height: props.imageHeight }"
                rounded-28rpx
                bg-background-muted
            >
                <image
                    :src="images"
                    mode="aspectFit"
                    rounded-28rpx
                    :style="{ width: props.imageWidth, height: props.imageHeight }"
                />
            </view>

            <view
                class="img-mask h-full w-full"
                flex="~ col justify-end items-start"
                absolute
                bottom-0
                left-0
                pb-14rpx
                pl-28rpx
                pr-7rpx
                @click="playVideo()"
            >
                <slot name="status-desc">
                    <view
                        v-if="Number(status) === Number(TaskStatusEnum.SUCCESS)"
                        text="xs foreground-light"
                    >
                        {{ time ? formatSecond(time) : '' }}
                    </view>

                    <view
                        v-if="Number(status) === Number(TaskStatusEnum.FAIL)"
                        text="xs foreground-light"
                        w-full
                        style="
                            overflow: hidden;
                            text-overflow: ellipsis;
                            display: -webkit-box;
                            -webkit-line-clamp: 2;
                            -webkit-box-orient: vertical;
                            white-space: normal;
                        "
                    >
                        生成失败{{ failReason ? `:${failReason}` : '' }}
                    </view>

                    <view
                        v-if="Number(status) === Number(TaskStatusEnum.PENDDING)"
                        text="xs foreground-light"
                    >
                        <view i-tabler:loader-2 class="mr-1 animate-spin text-foreground" />
                        生成中...
                    </view>
                </slot>
            </view>
        </view>

        <view class="pb-[24rpx] pt-[16rpx]" text-sm>
            <slot name="content">
                {{ content || '' }}
            </slot>
        </view>
    </view>
</template>

<style lang="scss" scoped>
.img-mask {
    background: linear-gradient(180deg, rgba(0, 0, 0, 0) 80%, rgb(var(--ui-background)) 100%);
}
</style>
