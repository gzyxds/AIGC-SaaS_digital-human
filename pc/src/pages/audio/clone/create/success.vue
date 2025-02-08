<template>
    <div class="h-full flex-1 bg-background md:rounded-xl">
        <div class="flex-col pb-8 pt-20 center">
            <UIcon name="heroicons:check-circle-20-solid" class="text-green-500" size="80" />
            <span class="pb-4 pt-2 font-medium">创建成功</span>
            <span class="text-sm text-foreground/70">请在后台【声音克隆】-【我的音色】中查看</span>
        </div>
        <div class="gap-4 center">
            <UButton to="/audio/clone/create">继续创建</UButton>
            <UButton to="/audio/clone/">前往列表</UButton>
        </div>
    </div>
</template>

<script lang="ts" setup>
useHead({
    title: '创建结果',
});

definePageMeta({
    auth: false,
});
</script>

<style lang="scss" scoped></style>
