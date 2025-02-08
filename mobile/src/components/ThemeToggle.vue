<script lang="ts" setup>
const appStore = useAppStore()
</script>

<template>
  <button type="primary" dark:text-red @click="appStore.setTheme()">
    toggle
  </button>
</template>

<style lang="scss" scoped>

</style>
