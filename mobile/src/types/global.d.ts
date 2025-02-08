/** 用户信息 */
interface UserInfo {
    id: number;
    sn: number;
    sex: string;
    account: string;
    nickname: string;
    real_name: string;
    avatar: string;
    mobile: string;
    create_time: string;
    is_new_user: number;
    is_auth: number;
    user_money: string;
    has_password: boolean;
    static: { label: string; value: number }[];
}

type themeType = 'dark' | 'light' | undefined;

interface LoginQrcode {
    expire_seconds: number;
    url: string;
    key: string;
    ticket: string;
}

/** 导航菜单项 */
interface MenuItem {
    icon: string;
    title: string;
    path: string;
    show?: boolean;
    target?: (string & {}) | '_blank' | '_parent' | '_self' | '_top' | null | undefined;
    childrens?: MenuItem[];
    click?: (...args: any[]) => void;
    [key: string]: any;
}

interface RequestPedding<T> {
    data: [T | null] extends [globalThis.Ref<any, any>]
        ? T // 若 T 是 Ref 类型，则直接返回 T
        : globalThis.Ref<T | null>; // 请求返回的数据
    pending: globalThis.Ref<boolean>; // 请求是否在处理中
    error: globalThis.Ref<RequestError | null>; // 请求错误
    refresh: () => Promise<void>; // 刷新方法
    execute: () => Promise<void>; // 执行方法
}

interface UploadResponse {
    id: number;
    cid: number;
    type: number;
    name: string;
    uri: string;
    url: string;
}

interface UploaderTempFile {
    file: File | null;
    url: string;
    duration?: number;
}

interface ListResponse<T> {
    count: number;
    extend: any[];
    lists: T[];
    page_no: number;
    page_size: number;
}

type SizeUnit = 'b' | 'kb' | 'Mb' | 'Gb' | 'Tb' | 'Pb' | 'Eb' | 'Zb' | 'Yb';

interface FormatFileSizeOptions {
    /** 小数位数 */
    decimals?: number;
    /** 单位基数，默认为 1024 */
    base?: 1000 | 1024;
    /** 自定义单位 */
    units?: SizeUnit[];
}

interface FormatTimeOptions {
    /** 是否显示小时，默认为自动隐藏小时（小于1小时） */
    showHours?: boolean;
    /** 是否对分钟和秒数补齐为两位数 */
    padZeros?: boolean;
}

interface PollingOptions {
    /** 轮询间隔时间（毫秒） */
    interval?: number;
    /** 最大尝试次数，默认为无限 */
    maxAttempts?: number;
    /** 最大等待时间（毫秒），默认为无限 */
    maxWaitTime?: number;
    /** 停止条件，可以是布尔值或函数 */
    stopCondition?: (() => boolean) | boolean;
    /** 任务结束时回调 */
    onEnded?: () => void;
}

interface SiteConfig {
    domain: string;
    login: {
        login_way: string[];
        register_way: string[];
        coerce_mobile: number;
        login_agreement: number;
        third_auth: number;
        wechat_auth: number;
        qq_auth: number;
        default_login_way: '1' | '2' | '3';
    };
    website: {
        shop_name: string;
        shop_logo: string;
        h5_favicon: string;
    };
    siteStatistics: { clarity_code: null };
    version: string;
    copyright: { key: string; value: string }[];
    admin_url: string;
    unit: { power: string };
    qrcode: { oa: string; mnp: string };
    share_image: string;
}

interface DecorateConfig {
    config: {
        id: number;
        tenant_id: number;
        type: number;
        name: string;
        data: string;
        meta: string;
        create_time: string;
        update_time: string;
    };
}

interface CustomerConfig {
    manual_kf: {
        status: number;
        qr_code: string;
        title: { value: string; status: number };
        phone: { value: string; status: number };
        service_time: { value: string; status: number };
    };
}

type PcDecorate = [
    {
        id: string;
        title: string;
        name: string;
        isShow: boolean;
        prop: { title: string; subtitle: string };
    },
    {
        id: string;
        title: string;
        name: string;
        isShow: boolean;
        prop: { data: { image: string }[] };
    },
];
