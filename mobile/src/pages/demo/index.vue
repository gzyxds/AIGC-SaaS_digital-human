<script setup lang="ts">
import type { UniPopupInstance } from '@uni-helper/uni-ui-types'
import { apiGetPowerFlow } from '@/api/user'
import ActionSheet from '@/components/ActionSheet.vue'
import ConfirmModal from '@/components/ConfirmModal.vue'
import ProPicker from '@/components/ProPicker.vue'
import { useDownloadFile } from '@/composables/useRequest'
import { useToast } from '@/composables/useToast'
import { getComponentExpose } from '@/utils/helper'

const selected = ref<string>('')
const confirmModalRef = getComponentExpose(ConfirmModal)
const actionSheetRef = getComponentExpose(ActionSheet)
const proPickerRef = getComponentExpose(ProPicker)

const uniConfirmModalRef = ref<UniPopupInstance>()

const range = [
  { value: 0, text: '篮球' },
  { value: 1, text: '足球' },
  { value: 2, text: '游泳' },
  { value: 3, text: '跳绳' },
  { value: 4, text: '摸鱼' },
  { value: 5, text: '跑步' },
]

const range2 = [
  { value: 0, text: '篮球' },
  { value: 1, text: '足球' },
  { value: 2, text: '游泳' },
  { value: 3, text: '跳绳' },
  { value: 4, text: '摸鱼' },
  { value: 5, text: '跑步' },
]

const range3 = [
  [1, 2, 3],
  [4, 5, 6],
  [7, 8, 9],
]

function openConfirmModal() {
  confirmModalRef.value?.open()
}

function openUniConfirmModal() {
  uniConfirmModalRef.value?.open?.()
}

onMounted(() => {

})

const { getList, list } = apiGetPowerFlow()

async function req() {
  await getList()
  console.log(list.value)
}

const download = () => {
  // url: 'https://ocrr3faw.yixiangonline.com/uploads/video/20250109/20250109101801c40079459.mp4',
  //  url: 'https://ocrr3faw.yixiangonline.com/uploads/file/20241226/2024122617134709d5c8883.mp3',
  useDownloadFile({
    type: 'image',
    fileUrl: 'https://ocrr3faw.yixiangonline.com/uploads/video/20250109/20250109101801c40079459.mp4',
    success: (res) => {
      console.log(res)
    },
    fail: (err) => {
      console.log(err)
    },
  })
}
</script>

<template>
  <view p="4" space="y-sm">
    <view flex="~ col">
      <text mb-1>
        网络请求
      </text>

      <view flex gap="2">
        <button type="default" @click="req()">
          发起请求
        </button>
        <button type="default" @click="download()">
          文件下载
        </button>
      </view>
    </view>

    <view flex="~ col">
      <text mb-1>
        主题色
      </text>

      <view flex="~ col" gap="2">
        <view flex gap="2">
          <view p="2" flex="1" center rounded-ui bg="primary">
            primary
          </view>
          <view p="2" flex="1" center rounded-ui bg="primary-bold">
            加深
          </view>
          <view p="2" flex="1" center rounded-ui bg="primary-muted">
            减弱
          </view>
        </view>

        <view flex gap="2">
          <view p="2" flex="1" center rounded-ui bg="success">
            success
          </view>
          <view p="2" flex="1" center rounded-ui bg="success-bold">
            加深
          </view>
          <view p="2" flex="1" center rounded-ui bg="success-muted">
            减弱
          </view>
        </view>

        <view flex gap="2">
          <view p="2" flex="1" center rounded-ui bg="danger">
            danger
          </view>
          <view p="2" flex="1" center rounded-ui bg="danger-bold">
            加深
          </view>
          <view p="2" flex="1" center rounded-ui bg="danger-muted">
            减弱
          </view>
        </view>

        <view flex gap="2">
          <view p="2" flex="1" center rounded-ui bg="warning">
            warning
          </view>
          <view p="2" flex="1" center rounded-ui bg="warning-bold">
            加深
          </view>
          <view p="2" flex="1" center rounded-ui bg="warning-muted">
            减弱
          </view>
        </view>

        <view flex gap="2">
          <view p="2" flex="1" center rounded-ui bg="background">
            background
          </view>
          <view p="2" flex="1" center rounded-ui bg="background-bold">
            加深
          </view>
          <view p="2" flex="1" center rounded-ui bg="background-muted">
            减弱
          </view>
        </view>

        <view flex gap="2">
          <view p="2" flex="1" center rounded-ui bg="border">
            border
          </view>
          <view p="2" flex="1" center rounded-ui bg="border-bold">
            加深
          </view>
          <view p="2" flex="1" center rounded-ui bg="border-muted">
            减弱
          </view>
        </view>

        <view flex gap="2">
          <view p="2" flex="1" center rounded-ui text="black" bg="foreground">
            foreground
          </view>
          <view p="2" flex="1" center rounded-ui text="black" bg="foreground-bold">
            加深
          </view>
          <view p="2" flex="1" center rounded-ui text="black" bg="foreground-muted">
            浅色90%
          </view>
          <view p="2" flex="1" center rounded-ui text="black" bg="foreground-light">
            浅色70%
          </view>
          <view p="2" flex="1" center rounded-ui text="black" bg="foreground-placeholder">
            浅色50%
          </view>
        </view>
      </view>
    </view>

    <view flex="~ col">
      <text mb-1>
        选择器（picker）&动作面板（action-sheet）
      </text>

      <view flex="~ items-center" gap="2">
        <view w-full>
          <picker :value="0" :range="range2" range-key="text" mode="date">
            <button type="default">
              原生picker
            </button>
          </picker>
        </view>
        <button type="default" @click="proPickerRef?.open()">
          自定义picker
        </button>
      </view>
      <pro-picker ref="proPickerRef" :range="range3" :value="[0, 0, 1]" />
    </view>

    <view flex="~ col">
      <text mb-1>
        动作面板（action-sheet）
      </text>

      <button type="default" @click="actionSheetRef?.open()">
        打开动作面板
      </button>
      <action-sheet
        ref="actionSheetRef" :actions="[
          {
            title: '无颜色',
            click: () => {
              console.log('click')
            },
          },
          {
            title: '点击回调操作',
            type: 'primary',
            click: () => {
              console.log('click')
            },
          },
          {
            title: '删除',
            desc: '一段描述文字',
            type: 'danger',
            click: () => {
              console.log('点击删除了')
              useToast('删除了')
            },
          },
        ]"
      />
    </view>

    <view flex="~ col">
      <text mb-1>
        确认弹窗（button）
      </text>
      <view flex="~ items-center" gap="2">
        <button type="default" @click="openConfirmModal">
          自定义封装
        </button>
        <button type="default" @click="openUniConfirmModal">
          uni-popup-dialog
        </button>
      </view>
      <confirm-modal ref="confirmModalRef" title="通知" cancel-text="取消喇" confirm-text="确定喇">
        确认要这样做吗？
      </confirm-modal>
      <uni-popup ref="uniConfirmModalRef" type="dialog" style="z-index: 999;">
        <uni-popup-dialog type="warn" cancel-text="关闭" confirm-text="同意" title="通知" content="欢迎使用 uni-popup!" />
      </uni-popup>
    </view>

    <view flex="~ col">
      <text mb-1>
        按钮（button）
      </text>
      <view flex="~ items-center" gap="2">
        <button type="default">
          按钮
        </button>
        <button type="primary">
          按钮
        </button>
        <button type="warn">
          按钮
        </button>
      </view>
    </view>

    <view flex="~ col">
      <text mb-1>
        镂空按钮（button plain）
      </text>
      <view flex="~ items-center" gap="2">
        <button type="default" plain>
          按钮
        </button>
        <button type="primary" plain>
          按钮
        </button>
        <button type="warn" plain>
          按钮
        </button>
      </view>
    </view>

    <view flex="~ col">
      <text mb-1>
        带图标按钮（button icon）
      </text>
      <view flex="~ items-center" gap="2">
        <button type="default" rounded-full>
          <view i-tabler:bulb />
          按钮
        </button>
        <button type="primary" rounded-full>
          <view i-tabler:microphone />
          按钮
        </button>
        <button type="warn" rounded-full>
          <view i-tabler:check />
          按钮
        </button>
      </view>
    </view>

    <view flex="~ col">
      <text mb-1>
        半圆按钮（button rounded-full）
      </text>
      <view flex="~ items-center" gap="2">
        <button type="default" rounded-full>
          按钮
        </button>
        <button type="primary" rounded-full>
          按钮
        </button>
        <button type="warn" rounded-full>
          按钮
        </button>
      </view>
    </view>

    <view flex="~ col">
      <text mb-1>
        禁用状态按钮（disabled）
      </text>
      <view flex="~ items-center" gap="2">
        <button type="default" disabled>
          按钮
        </button>
        <button type="primary" disabled>
          按钮
        </button>
        <button type="warn" disabled>
          按钮
        </button>
      </view>
    </view>
    <view flex="~ col">
      <text mb-1>
        加载状态按钮（loading）
      </text>
      <view flex="~ items-center" gap="2">
        <button type="default" loading>
          加载中
        </button>
        <button type="primary" loading>
          加载中
        </button>
        <button type="warn" loading>
          加载中
        </button>
      </view>
    </view>
    <view flex="~ col">
      <text mb-1>
        输入框（input）不建议使用
      </text>
      <input type="text" placeholder="请输入内容">
    </view>
    <view flex="~ col">
      <text mb-1>
        加强输入框（uni-easyinput）
      </text>
      <uni-easyinput placeholder="请输入内容" type="text" />
    </view>
    <view flex="~ col">
      <text mb-1>
        下拉菜单（uni-data-select）
      </text>
      <uni-data-select v-model="selected" placeholder="请选择内容" :localdata="range" placement="bottom" />
    </view>
    <view flex="~ col">
      <text mb-1>
        文本框（textarea）
      </text>
      <textarea rows="4" placeholder="请输入内容" :maxlength="900" />
    </view>
  </view>
</template>

<style></style>

<route type="home" lang="json">
{}
</route>
