<!-- 卡片标题组件 -->
<script setup lang="ts">
defineProps<{ title: string }>()

const emits = defineEmits<{
  (e: 'toPage'): void
}>()
</script>

<template>
  <view between>
    <view center>
      <view class="my-title" relative z-2 text="base foreground">{{ title || '' }}</view>
      <image src="@/static/icons/icon-star.png" w-4 h-4 mb-1.5></image>
    </view>
    <view py-1 px-2.5 text="xs foreground-muted" rounded-7 border="2rpx solid border-muted" @click="emits('toPage')">
      查看更多</view>
  </view>
</template>

<style lang="scss" scoped>
.my-title::after {
  @apply bg-primary;
  content: "";
  display: block;
  width: 100%;
  height: 12rpx;
  position: absolute;
  left: 0;
  bottom: 5rpx;
  z-index: -1;
}
</style>
