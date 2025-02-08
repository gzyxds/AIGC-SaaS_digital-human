<template>
    <proModal
        v-model="controlsStore.loginModal"
        :title="
            ['用户登录', '微信登录', '手机号登录', '账号密码登录', '找回密码', '绑定手机号'][
                Number(loginWay)
            ]
        "
        @close="closeModal"
    >
        <div>
            <Transition mode="out-in" name="login">
                <MobileLogin
                    v-if="isWay(LoginWayEnum.MOBILE) && checkWayStatus(LoginWayEnum.MOBILE)"
                    :is-confirm-agreement="isConfirmAgreement"
                    @login="loginHandle"
                />

                <AccountLogin
                    v-else-if="isWay(LoginWayEnum.ACCOUNT) && checkWayStatus(LoginWayEnum.ACCOUNT)"
                    :is-confirm-agreement="isConfirmAgreement"
                    @forget="toggleForget"
                    @login="loginHandle"
                />
                <WechatLogin
                    v-else-if="isWay(LoginWayEnum.WECHAT) && checkWayStatus(LoginWayEnum.WECHAT)"
                    :is-confirm-agreement="isConfirmAgreement"
                    @login="loginHandle"
                />
                <ForgetPassword v-else-if="isWay(otherWayEnum.FORGET)" @forget="toggleForget" />
                <BindMobile v-else-if="isWay(otherWayEnum.BIND)" @bind="bindSuccess" />
            </Transition>
            <div
                v-if="
                    appStore.siteConfig &&
                    appStore.siteConfig?.login.login_way.length > 1 &&
                    !isWay(otherWayEnum.BIND)
                "
                class="mt-6"
            >
                <UDivider
                    size="2xs"
                    label="其他登录方式"
                    :ui="{ label: 'text-xs text-foreground/60' }"
                />
                <div class="mt-4 flex items-center justify-center gap-3">
                    <UTooltip
                        v-if="!isWay(LoginWayEnum.MOBILE) && checkWayStatus(LoginWayEnum.MOBILE)"
                        text="手机号登录"
                    >
                        <div
                            class="size-8 cursor-pointer rounded-full bg-foreground/5 p-1 text-foreground/60 center hover:text-primary"
                            @click="changeWay(LoginWayEnum.MOBILE)"
                        >
                            <UIcon name="tabler:phone" class="text-xl" />
                        </div>
                    </UTooltip>
                    <UTooltip
                        v-if="!isWay(LoginWayEnum.ACCOUNT) && checkWayStatus(LoginWayEnum.ACCOUNT)"
                        text="账号密码登录"
                    >
                        <div
                            class="size-8 cursor-pointer rounded-full bg-foreground/5 p-1 text-foreground/60 center hover:text-primary"
                            @click="changeWay(LoginWayEnum.ACCOUNT)"
                        >
                            <UIcon name="tabler:lock" class="text-xl" />
                        </div>
                    </UTooltip>
                    <UTooltip
                        v-if="!isWay(LoginWayEnum.WECHAT) && checkWayStatus(LoginWayEnum.WECHAT)"
                        text="微信登录"
                    >
                        <div
                            class="size-8 cursor-pointer rounded-full bg-foreground/5 p-1 text-foreground/60 center hover:text-[#08c352]"
                            @click="changeWay(LoginWayEnum.WECHAT)"
                        >
                            <UIcon name="tabler:brand-wechat" class="text-xl" />
                        </div>
                    </UTooltip>
                </div>
            </div>
            <div
                v-if="appStore.siteConfig?.login.login_agreement === 1 && !isWay('5')"
                class="mt-6 flex justify-center"
            >
                <UCheckbox
                    v-model="isConfirmAgreement"
                    :ui="{ inner: 'ms-2', base: 'size-3.5', wrapper: 'items-center' }"
                    name="notifications"
                    label="Notifications"
                >
                    <template #label>
                        <p class="text-xs text-foreground/50">
                            <span>已阅读并同意</span>
                            <NuxtLink
                                class="mx-[1px] font-medium text-primary"
                                to="/agreement?type=agreement&item=service"
                                target="_blank"
                                >《用户协议》</NuxtLink
                            >
                            <span>和</span>
                            <NuxtLink
                                class="mx-[1px] font-medium text-primary"
                                to="/agreement?type=agreement&item=privacy"
                                target="_blank"
                                >《隐私政策》</NuxtLink
                            >
                        </p>
                    </template>
                </UCheckbox>
            </div>
        </div>
    </proModal>
</template>

<script setup lang="ts">
import type { LoginResponse } from '~/api/user';
import { LoginWayEnum } from '~/enums/variableEnum';

import proModal from '../pro-modal.vue';
import AccountLogin from './way/account.vue';
import BindMobile from './way/bind.vue';
import ForgetPassword from './way/forget.vue';
import MobileLogin from './way/mobile.vue';
import WechatLogin from './way/wechat.vue';

enum otherWayEnum {
    FORGET = '4',
    BIND = '5',
}

const controlsStore = useControlsStore();
const appStore = useAppStore();
const userStore = useUserStore();

const loginWay = ref<LoginWayEnum | otherWayEnum>(
    (appStore.siteConfig?.login.default_login_way as LoginWayEnum) || '3'
);
const needBindMobile = ref<boolean>(false);

const isWay = (way: LoginWayEnum | '4' | '5') => {
    return Number(loginWay.value) === Number(way);
};

const checkWayStatus = (way: LoginWayEnum) => {
    return (
        appStore.siteConfig?.login.login_way.includes(way) ||
        appStore.siteConfig?.login.login_way.includes(String(way))
    );
};

const changeWay = (way: LoginWayEnum | otherWayEnum) => {
    loginWay.value = way;
};

const toggleForget = () => {
    if (isWay(LoginWayEnum.ACCOUNT)) {
        changeWay(otherWayEnum.FORGET);
    } else {
        changeWay(LoginWayEnum.ACCOUNT);
    }
};

const loginHandle = (userInfo: LoginResponse) => {
    needBindMobile.value = true;
    userStore.tempLogin(userInfo.token);
    console.log('yelaile');
    changeWay(otherWayEnum.BIND);
};

const bindSuccess = async () => {
    needBindMobile.value = false;
    await nextTick();
    controlsStore.setLoginModal(false);
};

const closeModal = () => {
    if (needBindMobile.value) {
        userStore.token = null;
    }
    changeWay(appStore.siteConfig?.login.default_login_way as LoginWayEnum);
};

/** 协议确认 */
const isConfirmAgreement = ref<boolean>(
    appStore.siteConfig?.login.login_agreement === 1 ? false : true
);
</script>
