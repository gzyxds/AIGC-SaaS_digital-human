<!-- 文章卡片组件 -->
<script setup lang="ts">

const { data } = defineProps<{
  data: ArticleItem
}>()

// 去到文章详情
const toDetail = () => {
  uni.navigateTo({
    url: '/bundle/pages/article_detail/index?id=' + data.id,
  })
}
</script>

<template>
  <view flex mb-3 bg-background-muted rounded-28rpx @click="toDetail()">
    <image :src="data?.image" mode="aspectFill" w-48 h-27 rounded-28rpx flex-none></image>
    <view flex="~ col" justify-between pl-2.5 pr-1.5 py-2>
      <view text="base foreground" font-500 line-clamp-2>
        {{ data?.title }}
      </view>
      <view text-20rpx text-foreground-placeholder font-400>
        {{ data?.create_time }}
      </view>
    </view>
  </view>
</template>

<style></style>
