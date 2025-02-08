<!-- 视频播放页面 -->

<script setup lang="ts">
const videoUrl = ref('');
onLoad((options: any) => {
    console.log('options.videoUrl =>', options?.videoUrl);
    videoUrl.value = options.videoUrl;
});
</script>

<template>
    <view px-3 py-2>
        <video v-if="videoUrl" :src="videoUrl" controls :show-mute-btn="true" :vslide-gesture="true" class="video-play"/>
        <view v-else> 缺少视频资源 </view>
    </view>
</template>

<style lang="scss" scoped>
.video-play {
    height: calc(100vh - 32rpx - env(safe-area-inset-bottom));
    width: 100%;
}
</style>
