<script setup lang="ts">
import { apiGetArticleDetail } from '@/api/article'
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const id = ref<number | string>('')

const data = ref<ArticleDetail>()

const getDetail = async () => {
  const res = await apiGetArticleDetail({
    id: id.value,
  })
  // console.log('文章详情res =>', res)
  data.value = res
}

onLoad((options: any) => {
  // console.log('options =>', options)
  id.value = options.id || ''
  getDetail()
})
</script>

<template>
  <view px-24rpx py-16rpx>
    <view text-52rpx text-foreground>
      {{ data?.title }}
    </view>
    <view text="xs foreground-placeholder" mt-10rpx font-350>
      <span>{{ data?.click }}阅读</span>
      <span> · </span>
      <span>{{ data?.update_time }}</span>
    </view>
    <view mt-60rpx text="base foreground-light">
      <mp-html :content="data?.content" />
    </view>
  </view>
</template>

<style lang="scss" scoped></style>
