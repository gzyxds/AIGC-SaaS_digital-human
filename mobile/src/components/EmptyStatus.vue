<template>
  <!-- 空状态 -->
  <view class="flex flex-col items-center justify-center h-full">
    <image src="@/static/images/empty.png" class="w-[400rpx] h-[400rpx]"></image>
    <view text="sm foreground-muted">暂无内容，快去创作吧</view>

    <view w-full px-15 mt-6>
      <button v-if="showButton" type="primary" @click="emits('handle')">
        {{ buttonText }}
      </button>
    </view>
  </view>
</template>

<script lang="ts" setup>

const props = withDefaults(
  defineProps<{
    showButton?: boolean	// 是否显示按钮
    buttonText?: string		// 按钮文字
  }>(),
  {
    showButton: true,
    buttonText: '',
  }
)

const emits = defineEmits<{
  (event: 'handle'): void 	// 按钮操作事件
}>()
</script>

<style lang="scss" scoped></style>
