<script setup lang="ts">
import { apiPostCreateCompleteFlow } from '@/api/avatar';
import { apiGetPolicy } from '@/api/common';
import { apiGetAvatarPowerConfigAll } from '@/api/power';
import ConfirmModal from '@/components/ConfirmModal.vue';
import { AgreementTypeEnum, VariableEnum } from '@/enums/variableEnum';
import { onLoad } from '@dcloudio/uni-app';
import { debounce } from 'lodash-es';
import { computed, ref } from 'vue';
import SelectProfilePop from './components/SelectProfilePop.vue';
import SelectTonePop from './components/SelectTonePop.vue';
// 选择形象、音色弹窗
const selectProfilePopRef = ref();
const selectTonePopRef = ref();

// 选中的形象
const selectProfileImage = ref<string>();

// 支付弹窗Ref
const payPopupRef = getComponentExpose(ConfirmModal);
// 协议弹窗Ref
const agreementPopupRef = ref();
// 协议内容
const agreementContent = ref<string>('');

// 表单Ref
const formRef = ref();

// 模拟数据
const formData = ref<CreateCompleteFlow>({
    /*  声音模型-现在默认为 1 */
    voice_mode: 1,
    /*  声音id  */
    voice_id: '',
    /** 声音合成内容 */
    content: '',
    /** 音色名称-可选 */
    timbre: '',
    /** 形象 id */
    video_id: '',
    /** 合成模式 */
    video_mode: null,
    /** 数字人名称 */
    video_name: '',
});

// 校验规则
const rules = {
    video_name: {
        rules: [
            {
                required: true,
                errorMessage: '请输入数字人名称',
            },
        ],
    },
    video_mode: {
        rules: [
            {
                required: true,
                errorMessage: '请选择合成模式',
            },
        ],
    },
    content: {
        rules: [
            {
                required: true,
                errorMessage: '请输入需要合成的文本内容',
            },
        ],
    },
};

const contentLength = computed(() => {
    return formData.value.content.length;
});

// 合成价格
const Price = computed(() => {
    return (
        modeList.value.find((item) => item.value === formData.value.video_mode)?.video_power || 0
    );
});

// 合成模式列表
const modeList = ref([
    { value: 0, text: '极速模式', disable: false, video_power: 0 },
    { value: 1, text: '普通模式', disable: false, video_power: 0 },
]);

// 打开协议弹窗
const openAgreementPop = () => {
    agreementPopupRef.value.open();
};

// 去到协议页面
const toAgreement = () => {
    uni.navigateTo({
        url: '/bundle/pages/agreement/index?type=use',
    });
};

// 获取算力配置
const getConfig = async () => {
    const res = await apiGetAvatarPowerConfigAll();
    console.log('算力配置 =>', res);
    modeList.value = res.map((item) => {
        return {
            value: item.mode,
            text: `${item.video_mode_title}（${item.video_power}算力/分钟）`,
            disable: item.video_mode_status === 0,
            video_power: item.video_power,
        };
    });
};

// 获取使用协议
const getAgreement = async () => {
    const res = await apiGetPolicy(AgreementTypeEnum.USE);
    console.log('协议 =>', res);
    agreementContent.value = res.content;
};

// 创建数字人
const handleConfirm = () => {
    if (!formData.value.video_id) {
        return uni.showToast({
            title: '请选择形象',
            icon: 'none',
        });
    }

    if (!formData.value.voice_id) {
        return uni.showToast({
            title: '请选择音色',
            icon: 'none',
        });
    }

    formRef.value
        .validate()
        .then((res: any) => {
            console.log('表单校验通过', res);

            payPopupRef.value?.open();
        })
        .catch((err: any) => {
            console.log('表单校验失败', err);
        });
};

// 创建操作
const handlePay = debounce(async () => {
    // uni.showLoading({
    //     title: '创建中...',
    //     mask: true,
    // });
    try {
        await apiPostCreateCompleteFlow({
            ...formData.value,
            timbre: null,
        });
        console.log('创建请求成功');
        // uni.hideLoading();
        uni.switchTab({
            url: '/pages/product/index',
        });
    } catch (err) {
        console.log('创建请求失败', err);
        // uni.hideLoading();
    }
}, 800);

onLoad((options) => {
    console.log('options =>', options);
    // 形象列表跳转而来
    if (options?.profileId) {
        formData.value.video_id = options?.profileId;
        selectProfileImage.value = options?.profileImage;
    }

    // 语音列表跳转而来
    if (options?.soundId) {
        formData.value.voice_id = options?.soundId;
        formData.value.timbre = options?.soundName;
    }

    nextTick(async () => {
        await getAgreement();

        // 首次进入弹出协议套餐
        const isFirstLaunch = uni.getStorageSync(VariableEnum.CREATE_DIGITAL_FIRST_ENTRY);
        if (!isFirstLaunch) {
            openAgreementPop();
            uni.setStorageSync(VariableEnum.CREATE_DIGITAL_FIRST_ENTRY, true);
        }

        getConfig();
    });
});
</script>

<template>
    <view class="digital-people h-full px-[24rpx] py-[16rpx]">
        <!-- 选择我的形象 -->
        <view
            class="mx-[76rpx] h-[796rpx] flex flex-col items-center justify-center rounded-[48rpx] bg-background"
            @click="selectProfilePopRef.open(formData.video_id || '')"
        >
            <image
                v-if="selectProfileImage"
                h-796rpx
                w-550rpx
                rounded-48rpx
                :src="selectProfileImage"
                mode="aspectFit"
            />

            <image
                v-if="!selectProfileImage"
                src="@/static/icons/icon-image.png"
                class="h-[80rpx] w-[80rpx]"
            />
            <view v-if="!selectProfileImage" mt-12rpx text="3.5 foreground-muted" font-400>
                选择我的形象
            </view>
        </view>

        <uni-forms ref="formRef" :model-value="formData" :rules="rules">
            <!-- 请输入数字人名称 -->
            <view mx-24rpx mt-10>
                <uni-forms-item label="" name="video_name">
                    <uni-easyinput
                        v-model="formData.video_name"
                        placeholder="请输入数字人名称"
                        type="text"
                    />
                </uni-forms-item>
            </view>

            <!-- 选择音色 -->
            <view class="mx-[24rpx] mt-10 rounded-[28rpx] bg-background p-[24rpx]">
                <view
                    class="mb-2 w-fit center rounded-[28rpx] bg-background-muted p-1 pr-[16rpx]"
                    @click="selectTonePopRef.open(formData.voice_id || '')"
                >
                    <image src="@/static/icons/icon-sound.png" class="h-[64rpx] w-[64rpx]" />
                    <view v-if="!formData.timbre" ml-12rpx text="base foreground-muted">
                        选择音色
                    </view>
                    <view v-if="formData.timbre" ml-12rpx text="base foreground-muted">
                        {{ formData.timbre }}
                    </view>
                    <view i-tabler:chevron-right text-foreground-placeholder />
                </view>

                <view>
                    <uni-forms-item label="" name="content">
                        <textarea
                            v-model.trim="formData.content"
                            type="textarea"
                            placeholder="请输入需要合成的文本内容"
                            :maxlength="900"
                        />
                    </uni-forms-item>

                    <view mt-3 end>
                        <!-- <view center bg-background-muted px-2.5 py-1.5 rounded-2>
              <image src="@/static/icons/icon-AIWA.png" mode="scaleToFill" w-42rpx h-42rpx />
              <span text="3.5 foreground-muted" ml-1.5>AI文案</span>
            </view> -->

                        <view>
                            <span text="base primary" mr-2.5 @click="formData.content = ''"
                                >清空</span
                            >
                            <span text="3.5 foreground-placeholder">{{ contentLength }}/900</span>
                        </view>
                    </view>
                </view>
            </view>

            <!-- 合成模式 -->
            <view class="mx-[24rpx] mt-10">
                <view mb-24rpx ml-32rpx text="base foreground-placeholder"> 合成模式 </view>
                <uni-forms-item label="" name="video_mode">
                    <uni-data-select
                        v-model="formData.video_mode"
                        :localdata="modeList"
                        placeholder="请选择合成模式"
                        placement="top"
                        :clear="true"
                    />
                </uni-forms-item>
            </view>
        </uni-forms>

        <!-- 底部按钮 创建数字人 -->
        <view class="footer" position-fixed bottom-0 left-0 z-2 w-full bg-background p-3>
            <button type="primary" @click="handleConfirm">创建数字人</button>
            <view mt-24rpx text-center text-28rpx font-400>
                <span text-foreground-muted>我已知晓并同意</span>
                <span text-primary @click="toAgreement">《使用协议》</span>
            </view>
        </view>

        <!-- 选择形象弹窗 -->
        <select-profile-pop
            ref="selectProfilePopRef"
            v-model:profile-id="formData.video_id"
            v-model:profile-image="selectProfileImage"
        />
        <!-- 选择音色弹窗 -->
        <select-tone-pop
            ref="selectTonePopRef"
            v-model:sound-id="formData.voice_id"
            v-model:sound-name="formData.timbre"
        />

        <!-- 支付弹窗 -->
        <confirm-modal
            ref="payPopupRef"
            title="创建数字人"
            cancel-text="取消"
            confirm-text="继续"
            :confirm-callback="handlePay"
        >
            <!-- <view center>
                <view text-foreground-light> 本次合成需消耗: </view>
                <image
                    src="@/static/icons/icon-lightning.png"
                    class="h-[32rpx] w-[32rpx] flex-none"
                />
                <view text-foreground-light>
                    {{ Price }}
                </view>
            </view> -->
            <view text-foreground-light> 是否继续? </view>
        </confirm-modal>

        <!-- 协议弹窗 -->
        <confirm-modal
            ref="agreementPopupRef"
            title="使用协议"
            :show-cancel="false"
            :is-mask-click="false"
            confirm-text="我已知晓并同意"
        >
            <scroll-view :scroll-y="true" class="h-800rpx">
                <view text-left text-foreground-light>
                    <mp-html :content="agreementContent" />
                </view>
            </scroll-view>
        </confirm-modal>
    </view>
</template>

<style lang="scss" scoped>
.digital-people {
    .footer {
        padding-bottom: calc(env(safe-area-inset-bottom) + 24rpx);
    }

    padding-bottom: calc(env(safe-area-inset-bottom) + 260rpx);
}
</style>
