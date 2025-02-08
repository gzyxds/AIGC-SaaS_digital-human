<script lang="ts" setup>
import type { BaseEvent } from '@uni-helper/uni-types'
import type { UniPopupInstance } from '@uni-helper/uni-ui-types'

const props = withDefaults(
  defineProps<{
    range: any[][]
    value: number[]
    title?: string
    confirmText?: string
    cancelText?: string
  }>(),
  {
    range: () => [],
    value: () => [],
    title: '请选择',
    confirmText: '确定',
    cancelText: '取消',
  },
)

const emit = defineEmits<{
  confirm: [value: number[]]
  close: []
  change: [value: number[]]
  open: []
  pickstart: [value: BaseEvent]
  pickend: [value: BaseEvent]
}>()

const uniPopupRef = ref<UniPopupInstance>()
const selected = ref<number[]>([])

function close() {
  uniPopupRef.value?.close?.()
  console.log('close')
  emit('close')
}

function onChange(e: BaseEvent) {
  selected.value = e.detail.value
  emit('change', selected.value)
}

function open() {
  uniPopupRef.value?.open?.()
  emit('open')
}

function confirm() {
  emit('confirm', selected.value)
  console.log('confirm', selected.value)
  close()
}

onMounted(() => {
  // 初始化默认值, 保证selected的长度和range一致
  const defaultValue = JSON.parse(JSON.stringify(props.value))
  while (defaultValue.length < props.range.length) {
    defaultValue.push(0)
  }

  if (defaultValue.length > props.range.length) {
    defaultValue.splice(props.range.length)
  }

  if (props.value.length === props.range.length) {
    props.range.forEach((item, index) => {
      if (item.length < defaultValue[index]) {
        defaultValue[index] = 0
      }
    })
  }
  selected.value = defaultValue
})

defineExpose({
  open,
  close,
})
</script>

<template>
  <uni-popup ref="uniPopupRef" class="pro-picker-container" type="bottom" :safe-area="false" @mask-click="close">
    <view class="pro-picker-wapper">
      <view class="uni-picker-view-header">
        <view flex-1 active:opacity="70" @click="close">
          {{ props.cancelText }}
        </view>
        <view class="w-2/3" text="center" truncate>
          {{ props.title }}
        </view>
        <view flex-1 text="primary end" active:opacity="70" @click="confirm">
          {{ props.confirmText }}
        </view>
      </view>
      <picker-view
        :value="selected"
        class="h-[476rpx]" w-full mask-class="uni-picker-view-mask" indicator-class="uni-picker-view-indicator"
        @change="onChange"
        @pickend="emit('pickend', $event)"
        @pickstart="emit('pickstart', $event)"
      >
        <picker-view-column v-for="(item, index) in props.range" :key="index">
          <view v-for="(v, k) in item" :key="k" class="picker-view-column-item">
            {{ v }}
          </view>
        </picker-view-column>
      </picker-view>
    </view>
  </uni-popup>
</template>

<style lang="scss" scoped>
.pro-picker-wapper {
  /* #ifdef H5 */
  padding-bottom: calc(env(safe-area-inset-bottom) + 50px);
  /* #endif */
}
</style>
