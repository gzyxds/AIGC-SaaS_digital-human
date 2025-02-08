import { isMPWeixinClient } from '@/utils/client'
import { onMounted, onUnmounted, ref } from 'vue'
import { useAudio } from './useAudio'
/**
 * 录音功能的组合函数
 *
 * 提供录音、播放、暂停功能，支持微信小程序和 H5 录音
 *
 * @returns 录音相关的响应式变量和方法
 */
export function useRecording() {
  // 响应式变量，表示当前是否正在录音
  const isRecording = ref(false)
  // 响应式变量，用于存储录音文件的路径
  const audioFilePath = ref('')
  // 响应式变量，用于存储录音时长
  const recordingDuration = ref(0)
  // MediaRecorder 对象，用于 H5 录音
  let mediaRecorder: MediaRecorder | null = null
  // 存储录音数据块的数组
  let audioChunks: Blob[] = []
  // 存储录音计时的间隔 ID
  let recordingInterval: number | null = null

  // 引入音频控制函数
  const { play, pause, isPlaying } = useAudio()

  /**
   * 开始录音函数
   *
   * 判断当前环境是否支持录音，如果支持则开始录音
   */
  const startRecording = async () => {
    // 如果已经在录音，则直接返回
    if (isRecording.value)
      return

    console.log('是否为微信小程序环境', isMPWeixinClient())
    // 判断是否为微信小程序环境
    if (isMPWeixinClient()) {
      // 获取微信小程序的录音管理器
      const recorderManager = uni.getRecorderManager()
      // 开始录音，格式为 mp3
      recorderManager.start({ format: 'mp3' })
      // 监听录音停止事件，获取录音文件路径
      recorderManager.onStop((res) => {
        audioFilePath.value = res.tempFilePath
      })
    }
    else {
      // if (navigator.mediaDevices || navigator.mediaDevices.getUserMedia)
      const stream = await navigator.mediaDevices.getUserMedia({ audio: true })
      // 创建 MediaRecorder 实例
      mediaRecorder = new MediaRecorder(stream)

      // 监听数据可用事件，将数据块存入数组
      mediaRecorder.ondataavailable = (event) => {
        audioChunks.push(event.data)
      }

      // 监听录音停止事件，生成音频文件路径
      mediaRecorder.onstop = () => {
        const audioBlob = new Blob(audioChunks, { type: 'audio/mp3' })
        audioFilePath.value = URL.createObjectURL(audioBlob)
        audioChunks = []
      }

      // 开始录音
      mediaRecorder.start()
    }

    // 开始计时
    recordingInterval = setInterval(() => {
      recordingDuration.value += 1
    }, 1000)

    // 更新录音状态
    isRecording.value = true
  }

  /**
   * 停止录音函数
   *
   * 判断当前环境是否支持录音，如果支持则停止录音
   */
  const stopRecording = () => {
    // 如果没有在录音，则直接返回
    if (!isRecording.value)
      return

    // 判断是否为微信小程序环境
    if (isMPWeixinClient()) {
      // 获取微信小程序的录音管理器并停止录音
      const recorderManager = uni.getRecorderManager()
      recorderManager.stop()
    }
    else {
      // 停止 H5 录音
      if (mediaRecorder) {
        mediaRecorder.stop()
      }
    }

    // 停止计时
    if (recordingInterval) {
      clearInterval(recordingInterval)
      recordingInterval = null
    }

    // 更新录音状态
    isRecording.value = false
  }

  /**
   * 重新录音函数
   *
   * 停止当前录音并重置录音时长和文件路径
   */
  const resetRecording = () => {
    // 如果正在录音，则停止录音
    if (isRecording.value) {
      stopRecording()
    }
    // 重置录音时长和文件路径
    recordingDuration.value = 0
    audioFilePath.value = ''
  }

  /**
   * 播放录音
   *
   * 如果录音文件路径存在，则播放录音
   */
  const playRecording = () => {
    // 如果录音文件路径为空，则返回
    if (!audioFilePath.value) {
      console.error('录音文件路径为空')
      return
    }
    else {
      console.log('录音文件路径', audioFilePath.value)
    }

    // 播放录音
    play(audioFilePath.value)
  }

  /**
   * 暂停播放录音
   *
   * 如果音频正在播放，则暂停播放
   */
  const pauseRecording = () => {
    // 如果音频正在播放，则暂停
    if (isPlaying.value) {
      pause()
    }
  }

  // 组件挂载时的生命周期钩子
  onMounted(() => {
    // 可以在此处添加更多初始化逻辑
  })

  // 组件卸载时的生命周期钩子
  onUnmounted(() => {
    // 如果正在录音，则停止录音
    if (isRecording.value) {
      stopRecording()
    }
  })

  // 返回录音相关的响应式变量和方法
  return {
    isRecording,
    audioFilePath,
    recordingDuration,
    startRecording,
    stopRecording,
    resetRecording,
    playRecording,
    pauseRecording,
    isPlaying,
  }
}
