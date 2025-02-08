/** 是否开发环境 */
export const isDev = (callback?: (...args: any[]) => void) => {
    if (import.meta.dev) {
        return typeof callback === 'function' ? callback() : true;
    }
    return false;
};

/** 是否生产环境 */
export const isProd = (callback?: (...args: any[]) => void) => {
    if (!import.meta.dev) {
        return typeof callback === 'function' ? callback() : true;
    }
    return false;
};

/** 是否客户端环境 */
export const isClient = (callback?: (...args: any[]) => void) => {
    if (import.meta.client) {
        return typeof callback === 'function' ? callback() : true;
    }
    return false;
};

/** 是否服务端环境 */
export const isServer = (callback?: (...args: any[]) => void) => {
    if (import.meta.server) {
        return typeof callback === 'function' ? callback() : true;
    }
    return false;
};

/** 是否浏览器环境 */
export const isBrowser = (callback?: (...args: any[]) => void) => {
    if (import.meta.browser) {
        return typeof callback === 'function' ? callback() : true;
    }
    return false;
};

/** 是否Nitro阶段 */
export const isNitro = (callback?: (...args: any[]) => void) => {
    if (import.meta.nitro) {
        return typeof callback === 'function' ? callback() : true;
    }
    return false;
};

/** 是否预渲染阶段 */
export const isPrerender = (callback?: (...args: any[]) => void) => {
    if (import.meta.prerender) {
        return typeof callback === 'function' ? callback() : true;
    }
    return false;
};
