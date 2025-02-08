import type colors from '#ui-colors';

export type NotificationColor = 'gray' | (typeof colors)[number];

export interface NotificationAction {
    click?: (...args: any[]) => void;
}

export interface MessageType {
    id?: string;
    title?: string;
    description?: string;
    icon?: string;
    timeout?: number;
    actions?: NotificationAction[];
    click?: (...args: any[]) => void;
    callback?: (...args: any[]) => void;
    color?: NotificationColor;
    ui?: any;
    hook?: (...args: any[]) => void;
}

/** 消息提示 */
export const useMessage = (message?: string) => {
    isServer(() => {
        console.error('useMessage is not supported on server env');
    });

    const toast = useToast();

    if (message) {
        toast.add({
            icon: 'tabler:info-circle',
            title: message,
        });
    }

    const success = (msg: string | number | boolean, opts?: MessageType) => {
        if (typeof msg === 'number' || typeof msg === 'boolean') {
            msg = String(msg);
        }
        toast.add({
            icon: 'tabler:circle-check',
            title: '成功',
            description: msg,
            color: 'green',
            ...opts,
        });

        if (opts?.hook) {
            opts.hook();
        }
    };

    const warn = (msg: string | number | boolean, opts?: MessageType) => {
        if (typeof msg === 'number' || typeof msg === 'boolean') {
            msg = String(msg);
        }
        toast.add({
            icon: 'tabler:alert-circle',
            title: '警告',
            description: msg,
            color: 'orange',
            ...opts,
        });

        if (opts?.hook) {
            opts.hook();
        }
    };

    const error = (msg: string | number | boolean, opts?: MessageType) => {
        if (typeof msg === 'number' || typeof msg === 'boolean') {
            msg = String(msg);
        }
        toast.add({
            icon: 'tabler:alert-triangle',
            title: '错误',
            description: msg,
            color: 'red',
            ...opts,
        });

        if (opts?.hook) {
            opts.hook();
        }
    };

    const info = (msg: string | number | boolean, opts?: MessageType) => {
        if (typeof msg === 'number' || typeof msg === 'boolean') {
            msg = String(msg);
        }
        toast.add({
            icon: 'tabler:info-circle',
            title: '提示',
            description: msg,
            color: 'primary',
            ...opts,
        });

        if (opts?.hook) {
            opts.hook();
        }
    };

    return {
        success,
        warn,
        error,
        info,
    };
};
