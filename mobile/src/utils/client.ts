import { ClientEnum } from '@/enums/appEnums';

/**
 * @description 判断是否为微信小程序
 * @return {boolean}
 */
export const isMPWeixinClient = () => {
    // #ifdef MP-WEIXIN
    return true;
    // #endif

    // #ifndef MP-WEIXIN
    return false;
    // #endif
};

/**
 * @description 判断是否为微信环境
 * @return {boolean}
 */
export const isWeixinClient = () => {
    // #ifdef H5
    return /MicroMessenger/i.test(navigator.userAgent);
    // #endif
};

/**
 * @description 判断是否为安卓环境
 * @return {boolean}
 */
export function isAndroid() {
    // #ifdef H5
    const u = navigator.userAgent;
    return u.includes('Android') || u.includes('Adr');
    // #endif
}

/**
 * @description 获取当前是什么端
 * @return {object}
 */

export const getClient = () => {
    // @ts-ignore
    return handleClientEvent({
        // 微信小程序
        MP_WEIXIN: () => ClientEnum.MP_WEIXIN,
        // 微信公众号
        OA_WEIXIN: () => ClientEnum.OA_WEIXIN,
        // H5
        H5: () => ClientEnum.H5,
        // APP
        IOS: () => ClientEnum.IOS,
        ANDROID: () => ClientEnum.ANDROID,
        // 其它
        OTHER: () => null,
    });
};

/**
 * @description 获取当前是什么端
 * @return {string}
 */

export const getClientString = () => {
    // @ts-ignore
    return handleClientEvent({
        // 微信小程序
        MP_WEIXIN: () => '',
        // 微信公众号
        OA_WEIXIN: () => 'wechat',
        // H5
        H5: () => 'jump',
        // APP
        IOS: () => '',
        ANDROID: () => '',
        // 其它
        OTHER: () => null,
    });
};

// 根据端处理事件
// @ts-ignore
export const handleClientEvent = ({ MP_WEIXIN, OA_WEIXIN, H5, IOS, ANDROID, OTHER }: any) => {
    // #ifdef MP-WEIXIN
    return MP_WEIXIN();
    // #endif

    // #ifdef H5
    return isWeixinClient() ? OA_WEIXIN() : H5();
    // #endif

    // #ifdef APP-PLUS
    const system = uni.getSystemInfoSync();
    if (system.platform == 'ios') {
        return IOS();
    } else {
        return ANDROID();
    }
    // #endif
    return OTHER();
};

export const client = getClient();
