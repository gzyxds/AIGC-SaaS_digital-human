interface ShowToastOptions {
    /**
     * 提示的内容
     */
    title?: string;
    /**
     * 图标
     * - success: 显示成功图标
     * - loading: 显示加载图标
     * - error: 显示错误图标
     * - none: 不显示图标
     * - fail: 显示错误图标，此时 title 文本无长度显示，仅支付宝小程序、字节小程序
     * - exception: 显示异常图标。此时 title 文本无长度显示，仅支付宝小程序
     */
    icon?: 'success' | 'loading' | 'error' | 'none' | 'fail' | 'exception';
    /**
     * 自定义图标的本地路径，image 的优先级高于 icon
     */
    image?: string;
    /**
     * 提示的延迟时间，单位毫秒，默认：1500
     */
    duration?: number;
    /**
     * 纯文本轻提示显示位置，填写有效值后只有 title 属性生效
     * - top: 居上显示
     * - center: 居中显示
     * - bottom: 居底显示
     */
    position?: 'top' | 'center' | 'bottom';
    /**
     * 是否显示透明蒙层，防止触摸穿透，默认：false
     */
    mask?: boolean;
    /**
     * 接口调用成功的回调函数
     */
    success?: (result: any) => void;
    /**
     * 接口调用失败的回调函数
     */
    fail?: (result: any) => void;
    /**
     * 接口调用结束的回调函数（调用成功、失败都会执行）
     */
    complete?: (result: any) => void;
}

export function useToast(message?: string, options?: Omit<ShowToastOptions, 'title'>) {
    if (typeof message !== 'string') {
        message = String(message);
    }

    const _toast = (
        msg: string,
        icon: 'success' | 'loading' | 'error' | 'fail' | 'none' | 'exception',
        opts?: Omit<ShowToastOptions, 'title' | 'icon'>
    ) => {
        uni.showToast({
            title: msg,
            duration: opts?.duration || 1500,
            icon,
            image: opts?.image || '',
            position: opts?.position || 'center',
            mask: opts?.mask || false,
        });
    };

    if (message) {
        _toast(message, options?.icon || 'none', options);
    }

    const success = (msg: string, opts?: Omit<ShowToastOptions, 'title' | 'icon'>) => {
        _toast(msg, 'success', opts);
    };
    const error = (msg: string, opts?: Omit<ShowToastOptions, 'title' | 'icon'>) => {
        _toast(msg, 'error', opts);
    };
    const exception = (msg: string, opts?: Omit<ShowToastOptions, 'title' | 'icon'>) => {
        _toast(msg, 'exception', opts);
    };

    const loading = (msg: string, opts?: Omit<ShowToastOptions, 'title' | 'icon'>) => {
        _toast(msg, 'loading', opts);
    };

    return {
        success,
        error,
        exception,
        loading,
    };
}
