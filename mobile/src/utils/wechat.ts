import { apiGetLoginCodeUrl } from '@/api/wechat';

export enum UrlScene {
    LOGIN = 'login',
    PC_LOGIN = 'pcLogin',
    BIND_WX = 'bindWx',
    BASE = 'base',
}

/**
 * @description 是否为空
 * @param {unknown} value
 * @return {boolean}
 */
export const isEmpty = (value: unknown) => {
    return value == null || typeof value == 'undefined';
};

/**
 * @description 对象格式化为Query语法
 * @param {object} params
 * @return {string} Query语法
 */
export function objectToQuery(params: Record<string, any>): string {
    let query = '';
    for (const props of Object.keys(params)) {
        const value = params[props];
        if (!isEmpty(value)) {
            query += `${props}=${value}&`;
        }
    }
    return query.slice(0, -1);
}

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

const wechatOa = {
    _authData: {
        code: '',
        scene: '',
    },
    setAuthData(data: any = {}) {
        this._authData = data;
    },
    getAuthData() {
        return this._authData;
    },

    getUrl(scene: UrlScene, scope = 'snsapi_userinfo', extra = {}): Promise<void> {
        const currentUrl = `${location.href}${location.search ? '&' : '?'}scene=${
            scene || ''
        }&${objectToQuery(extra)}`;
        return new Promise((resolve, reject) => {
            apiGetLoginCodeUrl({
                url: currentUrl,
                scope,
            })
                .then((res: any) => {
                    location.href = res.url;
                    resolve(res.url);
                })
                .catch((err) => {
                    uni.showToast({
                        title: `获取url失败：${err.message || JSON.stringify(err)}`,
                        icon: 'error',
                    });
                    reject('获取url失败');
                });
        });
    },
};

export default wechatOa;
