import { useGetRequest, useListRequest, usePostRequest } from '@/composables/useRequest';
import type { QrLoginStatusEnum } from '@/enums/variableEnum';
import { client } from '@/utils/client';

export interface LoginResponse {
    nickname: string;
    sn: number;
    mobile: string;
    avatar: string;
    token: string;
}

export interface WechatLoginResponse {
    user_id: number;
    tenant_id: number;
    nickname: string;
    token: string;
    sn: number;
    mobile: string;
    avatar: string;
    terminal: number;
    expire_time: number;
}

export type CodeSence = 'YZMDL' | 'YZMZC' | 'BDSJHM' | 'ZHDLMM' | 'BGSJHM';

/** 账号密码登录 */
export function apiPostAccountLogin(data: {
    code?: string;
    account?: string;
    email?: string;
    password?: string;
    scene: 1 | 2 | 3;
}) {
    return usePostRequest<LoginResponse>('/login/account', {
        ...data,
        terminal: client,
    });
}

/** 根据微信小程序code获取对应账号信息 */
export function apiPostCodeBindAccount(data: { code: string }) {
    return usePostRequest<LoginResponse>('/login/codeBindAccount', data);
}

/** 小程序登录/注册 */
export function apiPostMnpLogin(data: { code: string; mobile?: string }) {
    return usePostRequest<LoginResponse>('/login/mnpLogin', data);
}

/** 小程序静默登录-弃用 */
export function apiPostSilentLogin(data: { code: string }) {
    return usePostRequest<LoginResponse>('/login/silentLogin', data);
}

/** 小程序获取手机号 */
export function apiGetMobileByMnp(data: { code: string }) {
    return usePostRequest('/user/getMobileByMnp', data);
}

/** 用户注册 */
export function apiPostAccountRegister(data: {
    account?: string;
    email?: string;
    password?: string;
    password_confirm?: string;
    /** 注册渠道: 1-微信小程序 2-微信公众号 3-手机H5 4-电脑PC 5-苹果APP 6-安卓APP */
    channel?: 1 | 2 | 3 | 4 | 5 | 6;
    /** 终端: 1-微信小程序 2-微信公众号 3-手机H5 4-电脑PC 5-苹果APP 6-安卓APP */
    terminal?: 1 | 2 | 3 | 4 | 5 | 6;
    code?: string;
    // 账号密码-3，手机验证码-2
    scene: 2 | 3;
}) {
    return usePostRequest<LoginResponse>('/login/register', {
        ...data,
        terminal: client,
        channel: client,
    });
}

/** 发送验证码 */
export function apiPostSendCode(data: { mobile?: string; scene: CodeSence }) {
    return usePostRequest<[]>('/sms/sendCode', data);
}

/** 重置密码 */
export function apiPostResetPassword(data: {
    mobile: string;
    code: string;
    password: string;
    password_confirm: string;
}) {
    return usePostRequest<[]>('/user/resetPassword', data);
}

/** 修改密码 */
export function apiPostChangePassword(data: {
    password: string;
    password_confirm: string;
    old_password: string;
}) {
    return usePostRequest<[]>('/user/changePassword', data);
}

/** 设置密码 */
export function apiPostSetPassword(data: { password: string; password_confirm: string }) {
    return usePostRequest<[]>('/user/setPassword', data);
}

/** 检查手机号是否注册 */
export function apiPostCheckRegister(data: { mobile: string }) {
    return usePostRequest<[]>('/login/checkRegister', data);
}

/** 设置用户信息 - 单个字段 */
export function apiPostSetUserInfoSingle(data: { field: 'nickname' | 'avatar'; value: string }) {
    return usePostRequest<[]>('/user/setInfo', data);
}

/** 绑定手机号 */
export function apiPostBindMobile(data: { mobile: string; code: string; type: 'bind' | 'change' }) {
    return usePostRequest<[]>('/user/bindMobile', data);
}

/** 获取用户信息 */
export function apiGetUser() {
    return useGetRequest<UserInfo>('/user/center');
}

/** 获取用户算力流水 */
export function apiGetPowerFlow(params: { page_no?: number; page_size?: number; action?: number }) {
    return useGetRequest<
        Paging<{
            change_type: number;
            change_amount: string;
            action: number;
            create_time: null;
            remark: string;
            type_desc: string;
            change_amount_desc: string;
        }>
    >('/accountLog/lists', params);
}

/** 获取用户算力流水 */
export function apiGetRechargeRecord() {
    return useListRequest<RechargeRecordItem>('/recharge/lists');
}

/** 获取微信登录二维码 */
export function apiGetLoginQrcode() {
    return useGetRequest<LoginQrcode>('/login/qrcode');
}

/** 扫码验证 */
export function apiPostLoginTicket(data: { key: string }) {
    /** status 1-未扫码 2-扫码未登录 4-登录成功 */
    return usePostRequest<{
        user: WechatLoginResponse;
        status: QrLoginStatusEnum;
    }>('/login/ticket', data);
}
