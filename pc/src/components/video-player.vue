<template>
    <proModal v-model="isOpen" title="视频预览" width="sm:max-w-3xl">
        <div class="size-full rounded-lg bg-background-soft center">
            <video
                v-if="videoUrl"
                class="h-full max-h-[70vh] max-w-full"
                :src="videoUrl"
                preload="auto"
                controls
                autoplay
            />
        </div>
        <div class="gap-4 pt-6 center">
            <UButton color="white" @click="useCopy(videoUrl)"> 复制链接 </UButton>
            <UButton @click="downloadFile({ src: videoUrl })"> 下载视频 </UButton>
        </div>
    </proModal>
</template>

<script setup lang="ts">
const isOpen = ref<boolean>(false);
const videoUrl = ref<string>('');

const open = (src: string) => {
    if (checkVideoUrl(src)) {
        isOpen.value = true;
        videoUrl.value = src;
    } else {
        useMessage().error('视频地址格式有误');
    }
};

defineExpose({ open });
</script>
