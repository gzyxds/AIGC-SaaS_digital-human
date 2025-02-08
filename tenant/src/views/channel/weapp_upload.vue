<template>
  <div>
      <el-card class="!border-none" shadow="never">
          <el-alert class="text-xxl" type="error" :closable="false" show-icon>
              <template v-slot:title>
                  <div class="py-[3px]">温馨提示：</div>
                  <div class="py-[3px]">
                      1.首次使用小程序一键上传，先安装好node环境，然后在终端下使用cd命令进到项目下的server/extend/miniprogram-ci目录，运行命令 npm install miniprogram-ci --save 建议使用node最新版本。
                  </div>
                  <div class="py-[3px]">
                      2.小程序上传前，请先到本系统的`小程序配置`设置小程序代码上传密钥，如已配置，请忽略；
                  </div>
                  <div class="py-[3px]">3.小程序上传成功后，要前往小程序后台提交审核哦；</div>
              </template>
          </el-alert>
      </el-card>
      <el-form ref="formRef" :model="formData" label-width="120px">
          <el-card class="!border-none mt-4" shadow="never">
              <div class="font-medium mb-7">小程序一键上传</div>
              <el-form-item label="版本号" prop="name">
                  <div class="w-80">
                      <el-input :disabled="true" v-model="appStore.config.version" />
                  </div>
              </el-form-item>
              <el-form-item label="项目备注" prop="original_id">
                  <div class="w-80">
                      <el-input
                          type="textarea"
                          :rows="4"
                          resize="none"
                          v-model="formData.upload_desc"
                          placeholder="请输入项目备注"
                      />
                  </div>
              </el-form-item>
              <el-form-item>
                  <ElButton type="primary" :loading="isLock" @click="lockFn">上传小程序</ElButton>
              </el-form-item>
              <el-form-item v-if="uploadInfo.status !== -1">
                  <div class="bg-page w-80 p-[20px] rounded">
                      <div class="flex items-center" v-if="uploadInfo.status == 1">
                          <Icon name="el-icon-SuccessFilled" class="text-success" :size="18" />
                          <span class="font-bold ml-[10px]"> 上传成功 </span>
                      </div>
                      <div class="flex items-center" v-if="uploadInfo.status == 0">
                          <Icon name="el-icon-CircleCloseFilled" class="text-error" :size="18" />
                          <span class="font-bold ml-[10px]"> 上传失败 </span>
                      </div>
                      <span
                          class="msg break-words"
                          :class="{
                              'text-error': uploadInfo.status == 0,
                              'text-tx-secondary': uploadInfo.status == 1
                          }"
                      >
                          {{ uploadInfo.msg }}
                      </span>
                      <div class="text-tx-secondary text-sm">{{ uploadInfo.time }}</div>
                  </div>
              </el-form-item>
          </el-card>
      </el-form>
  </div>
</template>
<script lang="ts" setup name="weappUpload">
import type { FormInstance } from 'element-plus'

import { uploadMnp } from '@/api/channel/weapp'
import { useLockFn } from '@/hooks/useLockFn'
import useAppStore from '@/stores/modules/app'
import cache from '@/utils/cache'
import feedback from '@/utils/feedback'
import { timeFormat } from '@/utils/util'
const cacheKey = 'mnp_upload_info'
const appStore = useAppStore()
const formData = reactive({
  upload_desc: ''
})
const formRef = shallowRef<FormInstance>()
const uploadInfo = reactive({
  status: -1,
  msg: '',
  time: ''
})
const { lockFn, isLock } = useLockFn(async () => {
  feedback.loading('正在上传中...')
  try {
      const { code } = await uploadMnp(formData)
      uploadInfo.status = code
      if (code === 1) {
          uploadInfo.msg = '请前往小程序后台提交审核！'
      } else {
          uploadInfo.msg = '请重新上传'
      }
  } catch (error) {
      uploadInfo.status = 0
      uploadInfo.msg = '请重新上传'
      console.error(error)
  } finally {
      uploadInfo.time = timeFormat(Date.now(), 'yyyy-mm-dd hh:MM:ss')
      cache.set(cacheKey, uploadInfo, 24 * 3600)
      feedback.closeLoading()
  }
})

onMounted(() => {
  const info = cache.get(cacheKey)
  info && Object.assign(uploadInfo, info)
})
</script>
