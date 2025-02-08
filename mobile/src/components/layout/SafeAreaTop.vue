<script lang="ts" setup>
const height = ref<number>(0)
onMounted(() => {
  const systemInfo = uni.getWindowInfo()
  height.value = systemInfo.statusBarHeight // 状态栏高度
})
</script>

<template>
  <view :style="{ height: `${height}px` }">
    {{ height }}
  </view>
</template>

<style lang="scss" scoped>

</style>
