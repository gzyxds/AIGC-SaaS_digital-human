<script setup lang="ts">
import { apiPostAvatarVideoCreate } from '@/api/avatar';
import { useUpload } from '@/composables/useRequest';
import { debounce } from 'lodash-es';
import { ref } from 'vue';

const formRef = ref();
const formData = ref<CreateAvatarVideo>({
    name: '',
    file_id: 0,
    duration: 0,
    cover: '',
});
// 视频上传进度
const progress = ref(0);
// 视频链接
const videoUrl = ref('');

// 表单校验规则
const rules = {
    name: {
        rules: [
            {
                required: true,
                errorMessage: '请输入形象名称',
            },
        ],
    },
};

// 去到协议页面
const toAgreement = () => {
    uni.navigateTo({
        url: '/bundle/pages/agreement/index',
    });
};

const handleConfirm = debounce(async () => {
    // 表单校验
    formRef.value
        .validate()
        .then(async (res: any) => {
            console.log('表单校验通过', res);

            await apiPostAvatarVideoCreate(formData.value);
            uni.navigateTo({
                url: '/bundle/pages/profile/index',
            });
            console.log('创建形象 请求成功', res);
        })
        .catch((err: any) => {
            console.log('表单校验失败', err);
        });
}, 800);

// 选择视频
const chooseVideo = () => {
    uni.chooseMedia({
        count: 1,
        mediaType: ['video'],
        sourceType: ['album', 'camera'],
        maxDuration: 30, // 拍摄视频最长拍摄时间，单位秒。时间范围为 3s 至 30s 之间
        camera: 'back',
        success: (res) => {
            console.log('视频选择成功', res);
            console.log('视频封面', res.tempFiles[0]?.thumbTempFilePath);

            // 重新选择
            if (progress.value === 100) {
                progress.value = 0;
                videoUrl.value = '';
            }

            // #ifdef MP-WEIXIN
            // 上传视频封面
            useUpload({
                type: 'image',
                data: { file: res.tempFiles[0]?.thumbTempFilePath },
                success: (res) => {
                    formData.value.cover = res.uri;
                    console.log('视频封面,上传成功', res);
                },
                fail: (errMsg) => {
                    console.log('视频封面,上传失败', errMsg);
                },
            });
            // #endif

            // 上传视频
            const uploadTask = useUpload({
                type: 'video',
                data: { file: res.tempFiles[0]?.tempFilePath },
                success: (res) => {
                    console.log(res);
                    formData.value.file_id = res.id;
                    videoUrl.value = res.uri;
                },
                fail: (errMsg) => {
                    console.log(errMsg);
                },
            });

            uploadTask.onProgressUpdate((res) => {
                progress.value = res.progress;
                // console.log('上传进度：', `${res.progress}%`);
                // console.log('已上传：', res.totalBytesSent);
                // console.log('总共：', res.totalBytesExpectedToSend);
            });
        },
        fail: (err) => {
            console.error('视频选择失败', err);
        },
    });
};
</script>

<template>
    <view class="digital-people h-full px-[24rpx] py-[16rpx]">
        <!-- 选择我的形象 -->
        <view class="flex">
            <view h-674rpx w-502rpx center flex-col rounded-6 bg-background>
                <!-- 未上传 -->
                <image
                    v-if="progress === 0"
                    src="@/static/icons/icon-image.png"
                    class="h-[80rpx] w-[80rpx]"
                    @click="chooseVideo"
                />
                <view
                    v-if="progress === 0"
                    class="mt-[12rpx] text-center font-[400]"
                    text="sm"
                    @click="chooseVideo"
                >
                    <view text="foreground-muted"> 点击上传视频 </view>
                    <view text="foreground-muted"> 大小限制1GB内 </view>
                </view>

                <!-- 上传中 -->
                <view
                    v-if="progress > 0 && progress < 100"
                    class="mt-[12rpx] text-center font-[400]"
                    text="sm"
                >
                    <view text="foreground-muted"> 上传中... </view>
                    <view text="primary"> {{ progress }}% </view>
                </view>

                <!-- 上传完成 -->
                <view v-if="progress === 100" relative>
                    <video :src="videoUrl" controls class="h-674rpx w-502rpx rounded-6" />
                    <view
                        position="absolute top-0 left-0"
                        text="xs foreground-muted"
                        center
                        rounded-lt-6
                        rounded-rb-6
                        p-2
                        class="bg-background-muted/50"
                        @click="chooseVideo"
                    >
                        <view> 重新选择 </view>
                        <view i-tabler:refresh />
                    </view>
                </view>
            </view>

            <view class="ml-[24rpx] flex flex-col">
                <!-- 正脸自拍 -->
                <view class="">
                    <image
                        src="@/static/images/digital/szr_zlzp.png"
                        mode="aspectFill"
                        class="h-[176rpx] w-[176rpx] rounded-[28rpx]"
                    />
                    <view class="mb-[4rpx] flex items-center justify-center">
                        <image src="@/static/icons/icon-yes.png" class="h-[28rpx] w-[28rpx]" />
                        <view ml-1 text="xs foreground-muted"> 正脸自拍 </view>
                    </view>
                </view>
                <!-- 可微张口 -->
                <view class="">
                    <image
                        src="@/static/images/digital/szr_kwzk.png"
                        mode="aspectFill"
                        class="h-[176rpx] w-[176rpx] rounded-[28rpx]"
                    />
                    <view class="mb-[4rpx] flex items-center justify-center">
                        <image src="@/static/icons/icon-yes.png" class="h-[28rpx] w-[28rpx]" />
                        <view ml-1 text="xs foreground-muted"> 可微张口 </view>
                    </view>
                </view>
                <!-- 面部干扰 -->
                <view class="">
                    <image
                        src="@/static/images/digital/szr_mbgr.png"
                        mode="aspectFill"
                        class="h-[176rpx] w-[176rpx] rounded-[28rpx]"
                    />
                    <view class="mb-[4rpx] flex items-center justify-center">
                        <image src="@/static/icons/icon-no.png" class="h-[28rpx] w-[28rpx]" />
                        <view ml-1 text="xs foreground-muted"> 面部干扰 </view>
                    </view>
                </view>
            </view>
        </view>

        <!-- 请输入数字人名称 -->
        <uni-forms ref="formRef" :model-value="formData" :rules="rules">
            <view class="mx-[24rpx] mt-[60rpx]">
                <uni-forms-item label="" name="name">
                    <uni-easyinput v-model="formData.name" placeholder="请输入形象名称" />
                </uni-forms-item>
            </view>
        </uni-forms>

        <view mx-2 mt-2 class="flex flex-col text-sm text-foreground-placeholder">
            <span>1.视频时长要求在30秒～5分钟，文件大小不超过1GB</span>
            <span
                >2.为保障效果，视频必须保证每一帧都要正面露脸，脸部无任何遮挡，并且视频中只能出现同一个人脸</span
            >
            <span
                >3.视频人物建议闭口或微微张口，张口幅度不宜过大；距离镜头一定距离。可根据合成效果自行调整。</span
            >
            <span>4.视频格式为MP4/MOV，建议分辨率1080p~4K</span>
        </view>

        <!-- 底部按钮 创建形象 -->
        <view
            class="footer"
            position="fixed left-0 bottom-0"
            z-2
            w-full
            center
            flex-col
            bg-background
            p-3
        >
            <button type="primary" @click="handleConfirm">创建形象</button>
            <view class="mt-[24rpx] text-center text-sm">
                <span text="foreground-muted">我已知晓并同意</span>
                <span class="text-primary" @click="toAgreement">《使用协议》</span>
            </view>
        </view>
    </view>
</template>

<style lang="scss" scoped>
.digital-people {
    .footer {
        padding-bottom: calc(env(safe-area-inset-bottom) + 24rpx);
    }

    padding-bottom: calc(env(safe-area-inset-bottom) + 240rpx);
}
</style>
