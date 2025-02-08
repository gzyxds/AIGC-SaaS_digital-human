<script lang="ts" setup>
const height = ref<number>(44)
const rightSafeWidth = ref<number>(0)

onMounted(async () => {
  // #ifndef H5
  const systemInfo = uni.getWindowInfo()
  const statusBarHeight = systemInfo.statusBarHeight || 0
  const menuInfo = uni.getMenuButtonBoundingClientRect()
  const menuOffsetTop = (menuInfo.top - statusBarHeight) * 2

  height.value = menuOffsetTop + menuInfo.height
  rightSafeWidth.value = systemInfo.screenWidth - menuInfo.left + systemInfo.screenWidth - menuInfo.right
  // #endif
})
</script>

<template>
  <LayoutSafeAreaTop />
  <view :style="{ height: `${height}px`, padding: `0 ${rightSafeWidth}px` }" flex items-center justify-center>
    title{{ height }}
  </view>
</template>

<style lang="scss" scoped>

</style>
