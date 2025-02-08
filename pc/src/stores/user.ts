import type { RouteLocationNormalizedGeneric } from 'vue-router';

import { apiGetUser, type LoginResponse, type WechatLoginResponse } from '~/api/user';
import { PageEnum } from '~/enums/pageEnum';
import { VariableEnum } from '~/enums/variableEnum';

export const useUserStore = defineStore('user', () => {
    /** 过期时间 */
    const _expireTime = 60 * 60 * 12;

    const TOKEN = useCookie(VariableEnum.USER_TOKEN).value || null;
    const TEMPORARY_TOKEN = useCookie(VariableEnum.USER_TEMPORARY_TOKEN).value || null;
    const LOGIN_TIME_STAMP = Number(useCookie(VariableEnum.LOGIN_TIME_STAMP).value || 0);

    /** 临时token */
    const temporary_token = ref<string | null>(TEMPORARY_TOKEN);
    /** token */
    const token = ref<string | null>(TOKEN);
    /** 用户信息 */
    const userInfo = ref<UserInfo | null>(null);
    /** 登录过期提示 */
    const onExpireNotice = ref<boolean>(false);
    /** 支付信息缓存 */
    const payInfoCache = ref<PayInfoCache[]>([]);
    /** 登录时间戳 */
    const loginTimeStamp = ref<number>(LOGIN_TIME_STAMP);

    /** 是否登录 */
    const isLogin = computed(() => {
        return token.value !== null && token.value !== undefined;
    });

    /** 登录 */
    const login = async (newToken: string) => {
        const route = useRoute();
        if (!newToken) return useMessage().error('登录出错，请重试');
        setToken(newToken);
        useMessage().success('登录成功');
        setLoginTimeStamp();
        await nextTick();
        getUser();
        if (route.path === route.params.redirect || route.path === PageEnum.HOME) {
            return reloadNuxtApp({ path: (route.params.redirect as string) || route.path });
        } else {
            navigateTo({ path: (route.params.redirect as string) || PageEnum.HOME });
        }
    };

    /** 登录 */
    const tempLogin = async (newToken: string) => {
        token.value = newToken;
    };

    /** 退出登录 */
    const logout = async (expire?: boolean, route?: RouteLocationNormalizedGeneric) => {
        if (expire) {
            isClient(() => {
                if (!onExpireNotice.value) {
                    onExpireNotice.value = true;
                    useMessage().warn('登录过期，请重新登录', {
                        callback: () => (onExpireNotice.value = false),
                    });
                    useControlsStore().setLoginModal(true);
                }
            });
        }
        clearToken();
        userInfo.value = null;
        if (route?.meta.auth !== false) {
            return navigateTo({
                path: `${PageEnum.HOME}?redirect=${route?.path}`,
                replace: true,
            });
        }
    };

    /** 获取用户信息 */
    const getUser = async () => {
        userInfo.value = await apiGetUser();
    };

    /** 设置token到cookie */
    const setToken = (newToken: string | null) => {
        token.value = newToken;
        setLoginTimeStamp();
        useCookie(VariableEnum.USER_TOKEN, { maxAge: _expireTime }).value = newToken;
    };

    /** 设置临时token */
    const setTemporaryToken = (newToken: string | null) => {
        temporary_token.value = newToken;
        useCookie(VariableEnum.USER_TEMPORARY_TOKEN).value = newToken;
    };

    /** 清空token */
    const clearToken = () => {
        token.value = null;
        loginTimeStamp.value = 0;
        temporary_token.value = null;
        useCookie(VariableEnum.USER_TOKEN).value = null;
        useCookie(VariableEnum.USER_TEMPORARY_TOKEN).value = null;
        useCookie(VariableEnum.LOGIN_TIME_STAMP).value = null;
    };

    /** 滑动刷新token */
    const refreshToken = () => {
        if (Date.now() - loginTimeStamp.value >= 3600 * 7) {
            useCookie(VariableEnum.USER_TOKEN, { maxAge: _expireTime }).value = token.value;
            setLoginTimeStamp();
        }
    };

    /** 设置登录时间 */
    const setLoginTimeStamp = () => {
        useCookie(VariableEnum.LOGIN_TIME_STAMP, { maxAge: _expireTime }).value = String(
            Date.now()
        );
    };

    const getPayInfoCache = (plan_id: number | string) => {
        const findRes = payInfoCache.value.find((item) => item.plan_id === plan_id);
        if (findRes) {
            if (Date.now() - findRes?.create_time > 300 * 1000) {
                return findRes;
            }
        }
        return null;
    };

    const setPayInfoCache = (pry_info: PayInfoCache) => {
        const findRes = payInfoCache.value.find((item) => item.plan_id === pry_info.plan_id);

        if (!findRes) {
            payInfoCache.value.push(pry_info);
        }
    };

    return {
        token,
        isLogin,
        setToken,
        clearToken,
        userInfo,
        login,
        getUser,
        logout,
        temporary_token,
        setTemporaryToken,
        payInfoCache,
        getPayInfoCache,
        setPayInfoCache,
        refreshToken,
        tempLogin,
    };
});
