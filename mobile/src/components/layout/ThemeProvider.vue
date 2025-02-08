<script lang="ts" setup>
// const appStore = useAppStore()
// onLoad(() => {
//   const { inital } = useTheme(appStore.theme)
//   setTimeout(() => {
//     uni.setBackgroundColor({ backgroundColor: '#ff0000', backgroundColorBottom: '#ff0000', backgroundColorTop: '#ff0000' })
//   }, 1000)

//   inital()
// })
</script>

<template>
  <view class="dark">
    <slot />
  </view>
</template>

<style lang="scss" scoped>

</style>
