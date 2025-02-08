<template>
    <div
        class="flex size-full flex-col bg-background sm:center md:rounded-xl md:bg-background md:py-4"
    >
        <h1 class="p-4 pb-0 text-xl font-medium md:hidden">声音录制</h1>

        <UForm
            :schema="schema"
            :state="formData"
            class="flex max-w-md flex-1 flex-col border-border/50 sm:min-w-96 sm:p-4 md:rounded-3xl md:border md:p-6"
            :validate-on="['submit']"
            @submit="onSubmit"
        >
            <div class="flex-1 space-y-6 rounded-lg p-4 pb-6 md:!p-0">
                <UFormGroup label="音色名称" name="name" required>
                    <UInput
                        v-model="formData.name"
                        size="xl"
                        placeholder="请填写音色名称"
                        color="gray"
                        :disabled="!isHttpsEnv"
                    />
                </UFormGroup>

                <UFormGroup label="音色头像" name="cover" help="Tips：建议上传1:1比例的图片">
                    <ProUploader
                        v-model="formData.cover"
                        type="image"
                        :max-size="5"
                        :disabled="!isHttpsEnv"
                    />
                </UFormGroup>
                <UAlert
                    v-if="!isHttpsEnv"
                    icon="tabler:alert-circle"
                    color="red"
                    variant="outline"
                    title="警告"
                    description="该站点未部署有效SSL证书，无法获取麦克风权限，请联系站点管理员处理"
                />
            </div>
            <div class="flex flex-col gap-4 rounded-t-lg py-8">
                <div>
                    <div class="mb-2 px-4 text-sm text-orange-500">
                        <UAlert
                            icon="tabler:alert-circle"
                            color="orange"
                            variant="outline"
                            title="提示"
                            description="录音时请严格按照以下内容进行朗读，否则可能导致克隆失败或合成效果不好"
                        />
                    </div>
                    <div class="mx-4 rounded-xl bg-background-soft p-4">
                        <!-- TODO 需要联调配置 -->
                        {{ voice_text || '我的声音将用于平台克隆，并合法使用，为自己的行为负责' }}
                    </div>
                </div>
                <p class="text-lg center">
                    {{ formatSecond(NP.divide(duration, 1000)) }}
                </p>
                <div class="flex justify-around">
                    <div class="flex-col gap-1 center">
                        <UButton
                            :ui="{ rounded: 'rounded-full' }"
                            size="lg"
                            color="white"
                            icon="tabler:reload"
                            :disabled="!status.isEnd || !isHttpsEnv || btnStatus"
                            @click="reset()"
                        />
                        <span class="text-sm">重录</span>
                    </div>
                    <AudioPlayer ref="audioPlayerRef" hidden>
                        <template #default="{ status: audioStatus, play: audioPlay }">
                            <div v-if="status.isEnd" class="flex-col gap-1 center">
                                <UButton
                                    :class="{ 'music-player': audioStatus === 1 }"
                                    :disabled="btnStatus"
                                    :ui="{
                                        rounded: 'rounded-full',
                                        icon: {
                                            size: {
                                                xl: 'h-8 w-8',
                                            },
                                        },
                                    }"
                                    size="xl"
                                    :icon="
                                        audioStatus === 0
                                            ? 'tabler:player-play-filled'
                                            : 'tabler:player-stop-filled'
                                    "
                                    :color="audioStatus === 0 ? 'amber' : 'red'"
                                    @click="audioPlay(result.url)"
                                />
                                <span class="text-sm">
                                    {{ audioStatus === 0 ? '播放' : '结束' }}
                                </span>
                            </div>
                            <div v-else class="flex-col gap-1 center">
                                <UButton
                                    :disabled="!isHttpsEnv"
                                    :class="{ 'music-player': status.isRecording }"
                                    :ui="{
                                        rounded: 'rounded-full',
                                        icon: {
                                            size: {
                                                xl: 'h-8 w-8',
                                            },
                                        },
                                    }"
                                    size="xl"
                                    :icon="
                                        status.isRecording
                                            ? 'tabler:player-stop-filled'
                                            : 'tabler:microphone'
                                    "
                                    :color="status.isRecording ? 'red' : 'primary'"
                                    @click="handleRecord()"
                                />
                                <span class="text-sm">
                                    {{ status.isRecording ? '结束录音' : '开始录音' }}
                                </span>
                            </div>
                        </template>
                    </AudioPlayer>
                    <div class="flex-col gap-1 center">
                        <UButton
                            type="submit"
                            :disabled="!result.url || !formData.name || !isHttpsEnv"
                            :ui="{ rounded: 'rounded-full' }"
                            size="lg"
                            color="emerald"
                            :loading="btnStatus"
                            icon="tabler:check"
                        />
                        <span class="text-sm">保存</span>
                    </div>
                </div>
                <!-- <p class="text-center text-xs text-primary md:text-start">如何开启麦克风权限？</p> -->
            </div>
        </UForm>
        <UModal
            v-if="isMobileDevice !== undefined"
            v-model="isPrecautions"
            :ui="{ height: 'h-full', container: 'items-center', width: 'sm:max-w-2xl' }"
            :fullscreen="isMobileDevice"
        >
            <div class="flex h-full flex-col overflow-y-auto p-4 pb-8">
                <div class="flex-1 overflow-y-auto">
                    <h1 class="text-lg font-medium">克隆流程</h1>
                    <div class="py-2 text-sm leading-7">
                        <p class="flex items-center gap-2">
                            <UIcon
                                name="tabler:hexagon-number-1-filled"
                                class="text-primary"
                                size="18"
                            />
                            点击开始录音，朗读规定内容文本
                        </p>
                        <p class="flex items-center gap-2">
                            <UIcon
                                name="tabler:hexagon-number-2-filled"
                                class="text-primary"
                                size="18"
                            />
                            填写音色信息，如音色名、头像等
                        </p>
                        <p class="flex items-center gap-2">
                            <UIcon
                                name="tabler:hexagon-number-3-filled"
                                class="text-primary"
                                size="18"
                            />
                            提交保存，返回列表查看
                        </p>
                    </div>
                    <h1 class="text-lg font-medium">注意事项</h1>
                    <div class="py-2 text-sm leading-7">
                        <p class="flex items-center gap-2 font-bold text-orange-500">
                            <UIcon
                                name="tabler:circle-number-1-filled"
                                class="flex-shrink-0 text-primary"
                                size="18"
                            />
                            请严格按照要求的内容文本进行录制
                        </p>
                        <p class="flex items-center gap-2">
                            <UIcon
                                name="tabler:circle-number-2-filled"
                                class="flex-shrink-0 text-primary"
                                size="18"
                            />
                            请在安静的环境下进行录音，避免噪音干扰
                        </p>
                        <p class="flex items-center gap-2">
                            <UIcon
                                name="tabler:circle-number-3-filled"
                                class="flex-shrink-0 text-primary"
                                size="18"
                            />
                            请使用标准普通话，吐字清晰，语速适当
                        </p>
                        <p class="flex items-center gap-2">
                            <UIcon
                                name="tabler:circle-number-4-filled"
                                class="flex-shrink-0 text-primary"
                                size="18"
                            />
                            录音时长控制在<span class="font-bold text-orange-500">6～12秒</span
                            >最佳，最多不超过14秒
                        </p>
                        <p class="flex items-center gap-2">
                            <UIcon
                                name="tabler:circle-number-5-filled"
                                class="flex-shrink-0 text-primary"
                                size="18"
                            />
                            录制完成后先试听看是否达到要求再提交
                        </p>
                    </div>
                </div>
                <div class="flex flex-col justify-center gap-4 pt-4">
                    <UCheckbox
                        v-model="isConfirmAgreement"
                        :ui="{ inner: 'ms-2' }"
                        name="notifications"
                        label="Notifications"
                    >
                        <template #label>
                            <p class="text-sm text-foreground/50">
                                <span>已阅读并同意</span>
                                <NuxtLink
                                    class="mx-[1px] font-medium text-primary"
                                    to="/agreement?type=agreement&item=use"
                                    target="_blank"
                                >
                                    《使用协议》
                                </NuxtLink>
                            </p>
                        </template>
                    </UCheckbox>
                    <UButton
                        :disabled="!isConfirmAgreement"
                        block
                        size="xl"
                        :ui="{ rounded: 'rounded-full' }"
                        trailing-icon="tabler:arrow-right"
                        @click="isPrecautions = false"
                    >
                        开始录制
                    </UButton>
                </div>
            </div>
        </UModal>
    </div>
</template>

<script lang="ts" setup>
import NP from 'number-precision';
import { object, string } from 'yup';

import { apiGetVoiveCloneConfig, apiPostVoiceCreate } from '~/api/audio';
import { uploadAudio } from '~/api/common';
import AudioPlayer from '~/components/audio-player.vue';

useHead({
    title: '声音克隆',
});

definePageMeta({
    auth: false,
});

const route = useRoute();
const router = useRouter();
const userStore = useUserStore();
userStore.setTemporaryToken(route.query.access_token as string);

const isMobileDevice = ref<boolean | undefined>();
const btnStatus = ref<boolean>(false);
/** 引导教程 */
const isPrecautions = ref<boolean>(true);
/** 同意协议 */
const isConfirmAgreement = ref<boolean>(isDev() as boolean);
const voice_text = ref<string>();
const isHttpsEnv = ref<boolean>(true);
const audioPlayerRef = getComponentExpose(AudioPlayer);

const schema = object({
    name: string().max(20, '音色名最长为20个字符').required('请填写音色名'),
});

const formData = reactive<CreateVoice>({
    cover: '',
    name: '',
    file_id: '',
    duration: 0,
    expected_content: '',
});

const onProcess = (duration: number) => {
    if (duration >= NP.times(14, 1000)) {
        useMessage().success('已录制14秒，自动停止录制');
        stop();
    }
};

const { pause, reset, result, resume, start, stop, status, statusCode, duration } = useRecorder({
    onProcess,
});

const handleRecord = () => {
    if (status.value.isInit) {
        return start();
    }
    if (status.value.isRecording) {
        if (duration.value < NP.times(6, 1000)) {
            useMessage().warn('请至少录制6秒');
            reset();
            return;
        }
        return stop((_, duration: number) => {
            formData.duration = NP.divide(duration, 1000);
        });
    }
};

async function onSubmit() {
    btnStatus.value = true;
    if (result.blob) {
        const file = blobToFile(result.blob, 'mp3');
        await uploadAudio({ file })
            .then(async (res) => {
                const { id } = res;
                formData.file_id = id;
                formData.expected_content = voice_text.value || '';
                try {
                    await apiPostVoiceCreate(formData);
                    navigateTo('/audio/clone/create/success');
                } catch (error) {
                    btnStatus.value = false;
                    console.error(error);
                }
            })
            .catch((err) => {
                btnStatus.value = false;
                console.error(err);
            });
    } else {
        btnStatus.value = false;
        useMessage().error('请先录制音频再试');
    }
}

onMounted(async () => {
    isHttpsEnv.value = isHttps();
    if (!route.query.access_token && !userStore.isLogin) {
        useMessage().error('链接已失效，请重新生成');
        setTimeout(() => {
            router.back();
        }, 1000);
    }
    isMobileDevice.value = isMobile();
    voice_text.value = (await apiGetVoiveCloneConfig()).voice_copy;
});
</script>

<style lang="scss" scoped>
/* 播放器的样式 */
.music-player {
    border-radius: 50%; /* 变成圆形 */
    position: relative;
    z-index: 1;
}

/* 波纹效果 */
.music-player::before,
.music-player::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0); /* 初始大小为0 */
    animation: ripple 2s infinite;
    z-index: -1;
    @apply bg-red-500/80;
}

/* 设置不同伪元素的动画延迟，形成交替波纹 */
.music-player::after {
    animation-delay: 1s;
}

/* 波纹动画 */
@keyframes ripple {
    0% {
        transform: translate(-50%, -50%) scale(0.5);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(1.6); /* 最终放大 */
        opacity: 0; /* 最终透明 */
    }
}
</style>
