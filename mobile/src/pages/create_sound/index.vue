<script setup lang="ts">
import { apiPostVoiceCreate } from '@/api/audio';
import { useRecording } from '@/composables/useRecording';
import { ProfileTabEnum, RecorderStatusEnum } from '@/enums/variableEnum';
import { debounce } from 'lodash-es';
import { computed, ref } from 'vue';
import SoundPop from './components/SoundPop.vue';

const soundPopRef = ref();
const formRef = ref();
// 录音状态
const recordingStatus = ref(RecorderStatusEnum.INIT);

// 使用 useRecording hooks
const {
    isRecording,
    audioFilePath,
    recordingDuration,
    startRecording,
    stopRecording,
    resetRecording,
    playRecording,
    pauseRecording,
    isPlaying,
} = useRecording();

const formData = ref<CreateVoice>({
    name: '',
    record: '',
    file_id: '',
    cover: '',
    duration: 0,
    expected_content: '我的声音将用于平台克隆，并合法使用，为自己的行为负责',
});

const rules = {
    name: {
        rules: [
            {
                required: true,
                errorMessage: '请输入声音名称',
            },
        ],
    },
};

function recordingChange(status: number) {
    recordingStatus.value = status;
}

// 录音描述
const RecordingDesc = computed(() => {
    switch (recordingStatus.value) {
        case RecorderStatusEnum.INIT:
            return '点击开始录音';
        case RecorderStatusEnum.RECORDING:
            return '正在录音中...';
        case RecorderStatusEnum.END:
            return '点击播放';
        case RecorderStatusEnum.PAUSE:
            return '点击暂停';
    }
});

function formatDuration(seconds: number) {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}
// 开始录音
function handleStartRecording() {
    startRecording();
    recordingStatus.value = RecorderStatusEnum.RECORDING;
}
// 停止录音
function handleStopRecording() {
    stopRecording();
    recordingStatus.value = RecorderStatusEnum.END;
    console.log('录音时长', recordingDuration.value);

    if (recordingDuration.value < 6) {
        useToast('录音时长至少6秒');
        // 重置录音
        handleResetRecording();
    } else if (recordingDuration.value > 14) {
        useToast('录音时长最多14秒');
        // 重置录音
        handleResetRecording();
    } else {
        formData.value.duration = recordingDuration.value;
    }
}

// 重置录音
function handleResetRecording() {
    resetRecording();
    recordingStatus.value = RecorderStatusEnum.INIT;
    formData.value.duration = 0;
}

// 播放录音
function handlePlayRecording() {
    playRecording();
    recordingStatus.value = RecorderStatusEnum.PAUSE;
}
// 暂停录音
function handlePauseRecording() {
    pauseRecording();
    recordingStatus.value = RecorderStatusEnum.END;
}

// 确定上传
const handleSubmit = debounce(async () => {
    const res = await formRef.value.validate();
    console.log('表单校验通过', res);

    // 上传录音
    const uploadTask = useUpload({
        type: 'file',
        data: { file: audioFilePath.value },
        success: (res) => {
            console.log('上传录音成功', res);
            formData.value.file_id = res.id;
            nextTick(() => {
                handleCreate();
            });
        },
        fail: (errMsg) => {
            console.log('上传录音失败', errMsg);
        },
    });

    uploadTask.onProgressUpdate((res) => {
        console.log('上传进度：', `${res.progress}%`);
        // console.log('已上传：', res.totalBytesSent)
        // console.log('总共：', res.totalBytesExpectedToSend)
    });
}, 800);

// 发起创建录音请求
const handleCreate = async () => {
    await apiPostVoiceCreate(formData.value);

    console.log('创建声音 请求成功');
    uni.navigateTo({
        url: `/bundle/pages/profile/index?type=${ProfileTabEnum.MySound}`,
    });
};

onLoad(() => {
    nextTick(() => {
        soundPopRef.value.open();
    });
});
</script>

<template>
    <view px-3 py-2 position="relative">
        <uni-forms ref="formRef" :model-value="formData" :rules="rules">
            <uni-forms-item label="" name="name">
                <uni-easyinput v-model="formData.name" placeholder="输入声音名称" />
            </uni-forms-item>
        </uni-forms>

        <view
            text="sm foreground-muted"
            mt-4.5
            bg-background-muted
            px-2.5
            py-4.5
            class="rounded-[var(--ui-radius)]"
        >
            <view start>
                <view i-tabler:bulb text="sm foreground-muted" />
                <view text="sm foreground-muted"> 提示: </view>
            </view>

            <view> 录音时请严格按照以下内容进行朗读，否则可能导致克隆失败或合成效果不好 </view>
        </view>

        <view
            text="sm foreground-muted"
            mt-4.5
            bg-primary
            px-2.5
            py-4.5
            class="rounded-[var(--ui-radius)]"
        >
            <view start>
                <view i-tabler:microphone text="sm foreground-muted" />
                <view text="sm foreground-muted"> 朗读内容: </view>
            </view>

            <view> 我的声音将用于平台克隆，并合法使用，为自己的行为负责</view>
        </view>

        <!-- 录音操作 -->
        <view position="fixed bottom-0 left-0" w-full center flex-col class="recording-handle">
            <!-- 录音时长 -->
            <view text="52rpx foreground-muted" mb-5.5 font-700>
                {{ formatDuration(recordingDuration) }}
            </view>
            <!-- 点击开始录音 -->
            <view
                v-if="recordingStatus === RecorderStatusEnum.INIT"
                h-148rpx
                w-148rpx
                center
                rounded-full
                class="recording-btn"
                @click="handleStartRecording"
            >
                <view i-tabler:microphone-filled text="7.5 foreground-muted" />
            </view>

            <!-- 正在录音中... -->
            <view
                v-if="recordingStatus === RecorderStatusEnum.RECORDING"
                h-148rpx
                w-148rpx
                center
                rounded-full
                border="6 solid foreground-placeholder"
                @click="handleStopRecording"
            >
                <view h-5 w-5 rounded-full bg-primary />
            </view>

            <!-- 点击播放/暂停  -->
            <view
                v-if="
                    recordingStatus === RecorderStatusEnum.END ||
                    recordingStatus === RecorderStatusEnum.PAUSE
                "
                w-full
                between
                px-108rpx
            >
                <view h-112rpx w-112rpx center rounded-full bg-background-muted>
                    <view
                        i-icon-park-outline:return
                        text="5 foreground-muted"
                        @click="handleResetRecording"
                    />
                </view>
                <view h-148rpx w-148rpx center rounded-full class="recording-btn">
                    <view
                        v-if="recordingStatus === RecorderStatusEnum.END"
                        i-tabler:player-play-filled
                        text="7.5 foreground-muted"
                        @click="handlePlayRecording"
                    />
                    <view
                        v-if="recordingStatus === RecorderStatusEnum.PAUSE"
                        i-tabler:player-pause-filled
                        text="7.5 foreground-muted"
                        @click="handlePauseRecording"
                    />
                </view>
                <view h-112rpx w-112rpx center rounded-full bg-success @click="handleSubmit">
                    <view i-tabler:check text="5 foreground-muted" />
                </view>
            </view>

            <!-- 录音描述 -->
            <view mt-3 text="sm foreground-muted">
                {{ RecordingDesc }}
            </view>
        </view>

        <!-- 提示弹窗 -->
        <sound-pop ref="soundPopRef" />
    </view>
</template>

<style scoped lang="scss">
.recording-btn {
    background: linear-gradient(
        180deg,
        rgb(var(--ui-primary-muted)) 0%,
        rgb(var(--ui-primary)) 100%
    );
}

.recording-handle {
    padding-bottom: calc(env(safe-area-inset-bottom) + 136rpx);
}
</style>
