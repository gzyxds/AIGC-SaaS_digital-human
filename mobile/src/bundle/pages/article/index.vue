<script setup lang="ts">
import { apiGetArticleList } from '@/api/article';

const paging = shallowRef<any>(null)
const list = ref<any>()

const queryList = async (page_no: number, page_size: number) => {
  try {
    const { lists } = await apiGetArticleList({
      page_no,
      page_size
    })
    paging.value.complete(lists)
  } catch (e) {
    console.log('报错=>', e)
    paging.value.complete(false)
  }
}


onMounted(() => {
})

</script>

<template>
  <view px-24rpx py-16rpx class="article">
    <z-paging ref="paging" v-model="list" @query="queryList" :fixed="false" height="100%">
      <!-- 资讯卡片 -->
      <article-card v-for="item in list" :key="item.id" :data="item"></article-card>
    </z-paging>
  </view>

</template>

<style lang="scss" scoped>
.article {
  height: calc(100vh - 32rpx - env(safe-area-inset-bottom));
}
</style>
