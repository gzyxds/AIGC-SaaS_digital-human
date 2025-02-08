<!-- 登录弹窗 -->
<script lang="ts" setup>
import {
    apiPostAccountLogin,
    apiPostCheckRegister,
    apiPostCodeBindAccount,
    apiPostMnpLogin,
    apiPostSendCode,
} from '@/api/user';
import ConfirmModal from '@/components/ConfirmModal.vue';
import { CodeTypeEnum, LoginSenceEnum, LoginWayEnum } from '@/enums/variableEnum';
import { useAppStore } from '@/stores/app';
import { useUserStore } from '@/stores/user';
import { ref } from 'vue';
import BindMobile from './BindMobile.vue';
import ForgetMobile from './ForgetMobile.vue';
import Register from './Register.vue';

const appStore = useAppStore();
const userStore = useUserStore();

// 是否同意协议
const isAgreement = ref(false);
// 协议弹窗
const agreementPopupRef = getComponentExpose(ConfirmModal);

// 验证码倒计时开关
const countdownSwitch = ref(false);

enum otherWayEnum {
    /* 忘记密码 */
    FORGET = '4',
    /* 绑定手机号 */
    BIND = '5',
    /* 账号注册 */
    ACCOUNT_REGISTER = '6',
    /* 手机号注册 */
    MOBILE_REGISTER = '7',
}

// 是否显示微信登录-获取手机号时不显示
const isShowWXlogin = ref(true);

// 登录方法
const loginWay = ref<LoginWayEnum | otherWayEnum>(LoginWayEnum.ACCOUNT);

// 登录拿到的数据
const loginData = ref();

// 弹窗Ref
const popupRef = ref();

// 表单Ref
const formRef = ref();

// 表单数据
const formData = ref({
    mobile: '',
    password: '',
    code: '',
});

// 表单校验规则
const rules = {
    mobile: {
        rules: [
            {
                required: true,
                errorMessage: '请输入手机号',
            },
        ],
    },
    password: {
        rules: [
            {
                required: true,
                errorMessage: '请输入密码',
            },
        ],
    },
    code: {
        rules: [
            {
                required: true,
                errorMessage: '请输入验证码',
            },
        ],
    },
};

// 判断登录方式
function isWay(way: LoginWayEnum | otherWayEnum) {
    return Number(loginWay.value) === Number(way);
}
// 判断登录方式是否开启
function checkWayStatus(way: LoginWayEnum | otherWayEnum) {
    return (
        appStore.siteConfig?.login.login_way.includes(way) ||
        appStore.siteConfig?.login.login_way.includes(String(way))
    );
}

// 切换登录方式或其他操作
function changeWay(way: LoginWayEnum | otherWayEnum) {
    // 关闭当前页面倒计时
    countdownSwitch.value = false;
    loginWay.value = way;
}

// 获取验证码
function handleCode() {
    if (!formData.value.mobile) {
        uni.showToast({
            title: '请输入手机号',
            icon: 'none',
        });
        return;
    }
    countdownSwitch.value = true;
    apiPostSendCode({
        mobile: formData.value.mobile,
        scene: CodeTypeEnum.LOGIN,
    });
    console.log('发送获取验证码请求');
}

// 协议弹窗
const handleAgreement = () => {
    isAgreement.value = true;
};

// 确定登录
const handleConfirm = async () => {
    // 是否同意协议
    if (!isAgreement.value) {
        agreementPopupRef.value?.open();
        return;
    }

    await formRef.value.validate();
    if (isWay(LoginWayEnum.MOBILE)) {
        mobileLogin();
    } else if (isWay(LoginWayEnum.ACCOUNT)) {
        accountLogin();
    }
};

// 账号密码登录
async function accountLogin() {
    const res = await apiPostAccountLogin({
        account: formData.value.mobile,
        password: formData.value.password,
        scene: LoginSenceEnum.ACCOUNT,
    });
    loginData.value = res;
    LoginAfterHandle();
}

// 手机号登录
async function mobileLogin() {
    const res = await apiPostAccountLogin({
        account: formData.value.mobile,
        code: formData.value.code,
        scene: LoginSenceEnum.MOBILE,
    });
    loginData.value = res;
    LoginAfterHandle();
}

// 微信登录
async function wxLogin() {
    // 是否同意协议

    if (!isAgreement.value) {
        agreementPopupRef.value?.open();
        return;
    }

    // #ifdef MP-WEIXIN
    await mnpLogin();
    // #endif
}

// 获取code
const getCode = async () => {
    const { code }: any = await uni.login({
        provider: 'weixin',
    });
    return code;
};

// 小程序登录
async function mnpLogin() {
    let code = await getCode();
    // 根据微信小程序code获取对应账号信息-有账号就登录，没有就注册且绑定手机号
    const codedData = await apiPostCodeBindAccount({
        code,
    });
    console.log('根据微信小程序code获取对应账号信息', codedData);

    if (codedData?.id) {
        // 有账号-直接登录
        // 重新获取code
        code = await getCode();
        const data = await apiPostMnpLogin({
            code,
        });
        console.log('获取登录账号数据', data);
        loginData.value = data;
        LoginAfterHandle();
    } else {
        // 无账号-登录注册（先获取手机号，有账号提示账号登录，没有注册后绑定手机号）
        // 登录注册
        isShowWXlogin.value = false;
    }
}

// 验证手机号是否已注册
const checkRegister = () => {
    apiPostCheckRegister({ mobile: formData.value.mobile })
        .then((res) => {
            console.log('手机号已注册, ', res);
            useToast('该微信手机号已注册，请使用手机号登录', { duration: 3000 });
        })
        .catch(async (err) => {
            console.log('手机号未注册', err);

            const code = await getCode();
            // 注册并登录
            const data = await apiPostMnpLogin({
                code,
                mobile: formData.value.mobile,
            });
            console.log('获取注册账号数据', data);
            loginData.value = data;
            LoginAfterHandle();
        });
};

// // 微信小程序-解密手机号
// const decryptPhoneNumber = (e: any) => {
//     console.log(e, '解密手机号');
// };

// 获取登录数据
const LoginAfterHandle = async () => {
    await userStore.login(loginData.value.token);

    // 需要强制绑定手机号
    console.log(
        appStore.siteConfig?.login?.coerce_mobile,
        'appStore.siteConfig?.login?.coerce_mobile'
    );
    console.log(loginData.value.mobile, 'loginData.value.mobile');

    if (appStore.siteConfig?.login?.coerce_mobile && !loginData.value.mobile) {
        loginWay.value = otherWayEnum.BIND;
        return;
    }
    // 关闭弹窗
    popupRef.value.close();
};

// 去协议页面
function toAgreement(type: string) {
    uni.navigateTo({
        url: `/bundle/pages/agreement/index?type=${type}`,
    });
}

onMounted(async () => {
    await appStore.getConfig();
    console.log('onLoad执行');
    console.log(
        'appStore.siteConfig?.login?.default_login_way',
        appStore.siteConfig?.login?.default_login_way
    );
    loginWay.value =
        (String(appStore.siteConfig?.login?.default_login_way) as LoginWayEnum) ||
        LoginWayEnum.ACCOUNT;

    // 已登录-开启强制绑定手机号-且为绑定手机号
    if (
        userStore.isLogin &&
        appStore.siteConfig?.login?.coerce_mobile &&
        !userStore.userInfo?.mobile
    ) {
        loginWay.value = otherWayEnum.BIND;
    }
});

defineExpose({
    // 打开弹窗
    open: () => {
        nextTick(() => {
            popupRef.value.open();
        });
    },
    close: () => {
        popupRef.value.close();
    },
});
</script>

<template>
    <uni-popup
        ref="popupRef"
        border-radius="24rpx"
        type="bottom"
        :is-mask-click="false"
        style="z-index: 99"
        :safe-area="false"
    >
        <view class="rounded-t-[var(--ui-radius)]" w-full center flex-col bg-background-bold px-6>
            <!-- 登录方式 -->
            <view flex="~ items-center justify-around" my-8 w-full text="lg primary">
                <view>
                    {{
                        [
                            '用户登录',
                            '微信登录',
                            '手机号登录',
                            '账号密码登录',
                            '找回密码',
                            '绑定手机号',
                            '账号注册',
                            '手机号注册',
                        ][Number(loginWay)]
                    }}
                </view>
            </view>

            <!-- 账号密码、手机号登录 -->
            <view v-if="isWay(LoginWayEnum.MOBILE) || isWay(LoginWayEnum.ACCOUNT)" w-full>
                <uni-forms ref="formRef" :model-value="formData" :rules="rules">
                    <uni-forms-item label="" name="mobile">
                        <uni-easyinput
                            v-model="formData.mobile"
                            prefix-icon="person"
                            :placeholder="
                                isWay(LoginWayEnum.MOBILE) ? '请输入手机号' : '请输入账号/手机号'
                            "
                        />
                    </uni-forms-item>

                    <!-- 密码 -->
                    <uni-forms-item v-if="isWay(LoginWayEnum.ACCOUNT)" label="" name="password">
                        <uni-easyinput
                            v-model="formData.password"
                            type="password"
                            prefix-icon="locked"
                            placeholder="请输入密码"
                        >
                            <template #right>
                                <view text="primary" pl-2 @tap="changeWay(otherWayEnum.FORGET)">
                                    忘记密码
                                </view>
                            </template>
                        </uni-easyinput>
                    </uni-forms-item>

                    <!-- 验证码 -->
                    <uni-forms-item v-if="isWay(LoginWayEnum.MOBILE)" label="" name="code">
                        <uni-easyinput
                            v-model="formData.code"
                            prefix-icon="locked"
                            placeholder="请输入验证码"
                        >
                            <template #right>
                                <VerificationCode
                                    v-model:countdown-switch="countdownSwitch"
                                    :second="59"
                                    @handle-code="handleCode"
                                />
                            </template>
                        </uni-easyinput>
                    </uni-forms-item>
                </uni-forms>
            </view>

            <!-- 忘记密码 -->
            <view w-full>
                <forget-mobile
                    v-if="isWay(otherWayEnum.FORGET)"
                    @forget="changeWay(LoginWayEnum.ACCOUNT)"
                />
            </view>

            <!-- 绑定手机号 -->
            <view w-full>
                <bind-mobile v-if="isWay(otherWayEnum.BIND)" @bind="LoginAfterHandle" />
            </view>

            <!-- 账号注册 -->
            <view w-full>
                <register
                    v-if="isWay(otherWayEnum.ACCOUNT_REGISTER)"
                    :is-agreement="isAgreement"
                    :agreement-popup-ref="agreementPopupRef"
                    :type="otherWayEnum.ACCOUNT_REGISTER"
                    @register="changeWay(LoginWayEnum.ACCOUNT)"
                />
            </view>

            <!-- 手机号注册 -->
            <view w-full>
                <register
                    v-if="isWay(otherWayEnum.MOBILE_REGISTER)"
                    :is-agreement="isAgreement"
                    :agreement-popup-ref="agreementPopupRef"
                    :type="otherWayEnum.MOBILE_REGISTER"
                    @register="changeWay(LoginWayEnum.MOBILE)"
                />
            </view>

            <!-- 底部按钮 -->
            <view
                v-if="
                    isWay(LoginWayEnum.MOBILE) ||
                    isWay(LoginWayEnum.ACCOUNT) ||
                    isWay(LoginWayEnum.WECHAT)
                "
                mt-4
                w-full
                between
                gap="4"
            >
                <!-- #ifdef MP-WEIXIN -->
                <button
                    v-if="isWay(LoginWayEnum.WECHAT) && isShowWXlogin"
                    type="primary"
                    @click="wxLogin"
                >
                    微信登录/注册
                </button>
                <!-- <button
                    v-if="isWay(LoginWayEnum.WECHAT) && !isShowWXlogin"
                    type="primary"
                    open-type="getPhoneNumber"
                    @getphonenumber="decryptPhoneNumber"
                >
                    获取手机号
                </button> -->
                <!-- 微信登录验证手机号 -->
                <view v-if="isWay(LoginWayEnum.WECHAT) && !isShowWXlogin" w-full center flex-col>
                    <view mb-4 w-full>
                        <uni-easyinput
                            v-model="formData.mobile"
                            prefix-icon="person"
                            placeholder="请输入微信手机号"
                        />
                    </view>
                    <button type="primary" @click="checkRegister">确定</button>
                </view>

                <!-- #endif -->

                <button
                    v-if="!isWay(LoginWayEnum.WECHAT) && isWay(LoginWayEnum.ACCOUNT)"
                    type="default"
                    @click="changeWay(otherWayEnum.ACCOUNT_REGISTER)"
                >
                    账号注册
                </button>
                <button
                    v-if="!isWay(LoginWayEnum.WECHAT) && isWay(LoginWayEnum.MOBILE)"
                    type="default"
                    @click="changeWay(otherWayEnum.MOBILE_REGISTER)"
                >
                    手机号注册
                </button>
                <button v-if="!isWay(LoginWayEnum.WECHAT)" type="primary" @click="handleConfirm">
                    登录
                </button>
            </view>

            <view text="foreground/40 xs" mt-4> 其他方式登录 </view>

            <view class="flex gap-3 pb-6 pt-4">
                <!-- #ifdef MP-WEIXIN -->
                <view
                    v-if="!isWay(LoginWayEnum.WECHAT) && checkWayStatus(LoginWayEnum.WECHAT)"
                    class="h-8 w-8 center rounded-full bg-foreground/10"
                    @click="changeWay(LoginWayEnum.WECHAT)"
                >
                    <view i-tabler:brand-wechat />
                </view>
                <!-- #endif -->
                <view
                    v-if="!isWay(LoginWayEnum.ACCOUNT) && checkWayStatus(LoginWayEnum.ACCOUNT)"
                    class="h-8 w-8 center rounded-full bg-foreground/10"
                    @click="changeWay(LoginWayEnum.ACCOUNT)"
                >
                    <view i-tabler:lock />
                </view>
                <view
                    v-if="!isWay(LoginWayEnum.MOBILE) && checkWayStatus(LoginWayEnum.MOBILE)"
                    class="h-8 w-8 center rounded-full bg-foreground/10"
                    @click="changeWay(LoginWayEnum.MOBILE)"
                >
                    <view i-tabler:phone />
                </view>
            </view>

            <!-- 协议 -->
            <view
                v-if="appStore.siteConfig?.login?.login_agreement"
                center
                pb-6
                text-center
                text-sm
            >
                <view
                    mr-2
                    h-5
                    w-5
                    center
                    rounded-full
                    :class="isAgreement ? 'bg-primary' : ''"
                    @click="isAgreement = !isAgreement"
                >
                    <view
                        v-if="isAgreement"
                        i-tabler:check
                        h-5
                        w-5
                        rounded-full
                        text="base foreground"
                        border="1 solid primary"
                    />
                    <view v-else h-5 w-5 rounded-full border="1 solid foreground" />
                </view>
                <span text-foreground-muted>已阅读并同意</span>
                <span text-primary @click="toAgreement('service')">《服务协议》</span>
                <span text-foreground-muted>和</span>
                <span text-primary @click="toAgreement('privacy')">《隐私协议》</span>
            </view>
        </view>
    </uni-popup>

    <confirm-modal
        ref="agreementPopupRef"
        title="服务协议及隐私协议"
        cancel-text="取消"
        confirm-text="确定"
        :confirm-callback="handleAgreement"
    >
        <view text-foreground-muted> 请您阅读并同意 </view>
        <view text-primary> 《服务协议》和《隐私协议》 </view>
    </confirm-modal>
</template>

<style lang="scss" scoped>
.footer {
    // padding-bottom: calc(env(safe-area-inset-bottom) + 24rpx);
    // /* #ifdef H5 */
    // padding-bottom: calc(env(safe-area-inset-bottom) + 124rpx);
    // /* #endif */
}
</style>
