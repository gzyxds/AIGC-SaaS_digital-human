<script setup lang="ts">
import { apiGetPolicy } from '@/api/common'
import { AgreementTypeEnum } from '@/enums/variableEnum'
import { ref } from 'vue'

const type = ref<AgreementTypeEnum>()
const strings = ref('')

const getDetail = async () => {
  const res = await apiGetPolicy(type.value!)
  strings.value = res.content

  uni.setNavigationBarTitle({ title: res.title })
}

onLoad((options: any) => {
  type.value = options.type as AgreementTypeEnum || AgreementTypeEnum.SERVICE
  getDetail()
})
</script>

<template>
  <view px-24rpx py-16rpx>
    <mp-html :content="strings" />
  </view>
</template>

<style lang="scss" scoped></style>
