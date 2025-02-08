import config from '@/config';
import { useAppStore } from '@/stores/app';
import { useUserStore } from '@/stores/user';
import { parseQuery } from 'uniapp-router-next';

/**
 * @description 图片路径拼接
 * @param {string} url 路径
 * @return {string} 图片路径
 */
export function joinUrl(url: string) {
    const appStore = useAppStore();
    // #ifdef H5
    return url.startsWith('http') ? url : `${appStore?.siteConfig?.domain}${url}`;
    // #endif

    // #ifndef H5
    return url.startsWith('http') ? url : `${config.baseUrl}${url}`;
    // #endif
}

/**
 * @description 防抖
 * @param { Function } func
 * @param {number} time
 * @return { Function }
 */
export function debounce(func: (_p: any) => any, time = 1000) {
    let timer: any = null;
    return (...args: any[]) => {
        if (timer) {
            clearTimeout(timer);
        }
        timer = setTimeout(() => {
            timer = null;
            func(args);
        }, time);
    };
}

/**
 * @description 获取元素节点信息（在组件中的元素必须要传ctx）
 * @param  {string} selector 选择器 '.app' | '#app'
 * @param  {boolean} all 是否多选
 * @param  { ctx } context 当前组件实例
 */
export function getRect(selector: string, all = false, context?: any) {
    return new Promise((resolve, reject) => {
        let qurey = uni.createSelectorQuery();
        if (context) {
            qurey = uni.createSelectorQuery().in(context);
        }
        qurey[all ? 'selectAll' : 'select'](selector)
            .boundingClientRect((rect) => {
                if (all && Array.isArray(rect) && rect.length) {
                    return resolve(rect);
                }
                if (!all && rect) {
                    return resolve(rect);
                }
                reject('找不到元素');
            })
            .exec();
    });
}

/**
 * @description 获取当前页面实例
 */
export function currentPage() {
    const pages = getCurrentPages();
    const currentPage = pages[pages.length - 1];
    return currentPage || {};
}

/**
 * @description 后台选择链接专用跳转
 */
export interface Link {
    path: string;
    name?: string;
    type: string;
    canTab: boolean;
    query?: Record<string, any>;
}

export enum LinkTypeEnum {
    SHOP_PAGES = 'shop',
    CUSTOM_LINK = 'custom',
    MINI_PROGRAM = 'mini_program',
}

export function navigateTo(
    link: Link,
    navigateType: 'navigateTo' | 'switchTab' | 'reLaunch' = 'navigateTo'
) {
    // 如果是小程序跳转
    if (link.type === LinkTypeEnum.MINI_PROGRAM) {
        navigateToMiniProgram(link);
        return;
    }

    const url = link?.query ? `${link.path}?${objectToQuery(link?.query)}` : link.path;
    (navigateType == 'switchTab' || link.canTab) && uni.switchTab({ url });
    navigateType == 'navigateTo' && uni.navigateTo({ url });
    navigateType == 'reLaunch' && uni.reLaunch({ url });
}

/**
 * @description 小程序跳转
 * @param link 跳转信息，由装修数据进行输入
 */
export function navigateToMiniProgram(link: Link) {
    const query = link.query;
    // #ifdef H5
    window.open(
        `weixin://dl/business/?appid=${query?.appId}&path=${query?.path}&env_version=${
            query?.env_version
        }&query=${encodeURIComponent(query?.query)}`
    );
    // #endif
    // #ifdef MP
    uni.navigateToMiniProgram({
        appId: query?.appId,
        path: query?.path,
        extraData: parseQuery(query?.query),
        envVersion: query?.env_version,
    });
    // #endif
}

/**
 * @description 组合异步任务
 * @param  { string } task 异步任务
 */

export function series(...task: Array<(_arg: any) => any>) {
    return function (_arg?: any): Promise<any> {
        return new Promise((resolve, reject) => {
            const iteratorTask = task.values();
            const next = (res?: any) => {
                const nextTask = iteratorTask.next();
                if (nextTask.done) {
                    resolve(res);
                } else {
                    Promise.resolve(nextTask.value(res)).then(next).catch(reject);
                }
            };
            next(_arg);
        });
    };
}

/**
 * @description 是否为空
 * @param {unknown} value
 * @return {boolean}
 */
export function isEmpty(value: unknown) {
    return value == null && typeof value == 'undefined';
}

/**
 * @description 显示消息提示框。
 * @param  {string} title 弹出内容
 * @param  {number} duration 延时多少毫秒
 */
export function toast(title: string | undefined, duration = 2000) {
    uni.showToast({
        title,
        duration,
        icon: 'none',
    });
}

/**
 * @description 对象格式化为Query语法
 * @param {object} params
 * @return {string} Query语法
 */
export function objectToQuery(params: Record<string, any>): string {
    // let query = ''
    // for (const props of Object.keys(params)) {
    //     const value = params[props]
    //     const part = encodeURIComponent(props) + '='
    //     if (!isEmpty(value)) {
    //         console.log(encodeURIComponent(props), isObject(value))
    //         if (isObject(value)) {
    //             for (const key of Object.keys(value)) {
    //                 if (!isEmpty(value[key])) {
    //                     const params = props + '[' + key + ']'
    //                     const subPart = encodeURIComponent(params) + '='
    //                     query += subPart + encodeURIComponent(value[key]) + '&'
    //                 }
    //             }
    //         } else {
    //             query += part + encodeURIComponent(value) + '&'
    //         }
    //     }
    // }
    // return query.slice(0, -1)

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
 * @description 将一个数组分成几个同等长度的数组
 * @param  { Array } array[分割的原数组]
 * @param  {number} size[每个子数组的长度]
 */
export function sliceArray(array: any[], size: number) {
    const result = [];
    for (let x = 0; x < Math.ceil(array.length / size); x++) {
        const start = x * size;
        const end = start + size;
        result.push(array.slice(start, end));
    }
    return result;
}

/**
 * @description 格式化输出价格
 * @param  { string } price 价格
 * @param  { string } take 小数点操作
 * @param  { string } prec 小数位补
 */
export function formatPrice({ price, take = 'all', prec = undefined }: any) {
    let [integer, decimals = ''] = `${price}`.split('.');

    // 小数位补
    if (prec !== undefined) {
        const LEN = decimals.length;
        for (let i = prec - LEN; i > 0; --i) decimals += '0';
        decimals = decimals.substr(0, prec);
    }

    switch (take) {
        case 'int':
            return integer;
        case 'dec':
            return decimals;
        case 'all':
            return `${integer}.${decimals}`;
    }
}

/**
 * @description 上传图片
 * @param  {string} path 选择的本地地址
 */
export function uploadFile(path: any) {
    return new Promise((resolve, reject) => {
        const userStore = useUserStore();
        uni.uploadFile({
            url: `${import.meta.env.VITE_APP_BASE_URL || ''}/api/Upload/image`,
            filePath: path,
            name: 'file',
            header: {
                token: userStore.token,
                version: '1.0.0',
            },
            fileType: 'image',
            success: (res) => {
                console.log('uploadFile res ==> ', res);
                const data = JSON.parse(res.data);
                if (data.code == 1) {
                    resolve(data.data);
                } else {
                    reject();
                }
            },
            fail: (err) => {
                console.log('。。。。', err);
                reject();
            },
        });
    });
}

/**
 * @description jsonp请求跨域地址
 * @param  {object} url 请求地址
 * @param  {object} data 请求参数
 */
export function jsonp(url, data) {
    // #ifdef H5
    return new Promise((resolve, reject) => {
        // 1.初始化url
        const dataString = !url.includes('?') ? '?' : '&';
        const callbackName = `jsonpCB_${Date.now()}`;
        url += `${dataString}callback=${callbackName}`;
        if (data) {
            // 2.有请求参数，依次添加到url
            for (const k in data) {
                url += `&${k}=${data[k]}`;
            }
        }
        const scriptNode = document.createElement('script');
        scriptNode.src = url;
        // 3. callback
        window[callbackName] = (result) => {
            result ? resolve(result) : reject('没有返回数据');
            delete window[callbackName];
            document.body.removeChild(scriptNode);
        };
        // 4. 异常情况
        scriptNode.addEventListener(
            'error',
            () => {
                reject('接口返回数据失败');
                delete window[callbackName];
                document.body.removeChild(scriptNode);
            },
            false
        );
        // 5. 开始请求
        document.body.appendChild(scriptNode);
    });
    // #endif
}

// 清除本地缓存
export function removeStorageData(key: string) {
    uni.removeStorage({
        key,
        success: (res) => {
            console.log('清除成功', res);
        },
    });
}

/**
 * @description 添加单位
 * @param {string | number} value 值 100
 * @param {string} unit 单位 px em rem
 */
export function addUnit(value: string | number, unit = 'rpx') {
    return !Object.is(Number(value), Number.NaN) ? `${value}${unit}` : value;
}

// 匹配6-20位纯数字和字母组合的正则表达式
export function validateInput(inputStr) {
    const regex = /^(?=.*\d)(?=.*[a-z])[0-9a-z]{6,20}$/i;

    // 检查输入是否符合规则
    if (regex.test(inputStr)) {
        return true;
    } else {
        return false;
    }
}
