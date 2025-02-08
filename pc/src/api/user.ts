import type { QrLoginStatusEnum } from '~/enums/variableEnum';

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
export const apiPostAccountLogin = (data: {
    code?: string;
    account?: string;
    email?: string;
    password?: string;
    scene: 1 | 2 | 3 | 4;
}) => {
    return usePostRequest<LoginResponse>('/login/account', {
        ...data,
        terminal: '4',
    });
};

/** 用户注册 */
export const apiPostAccountRegister = (data: {
    account?: string;
    email?: string;
    password?: string;
    password_confirm?: string;
    /** 注册渠道: 1-微信小程序 2-微信公众号 3-手机H5 4-电脑PC 5-苹果APP 6-安卓APP */
    channel: 1 | 2 | 3 | 4 | 5 | 6;
    code: string;
    scene: 2 | 3;
}) => {
    return usePostRequest<LoginResponse>('/login/register', {
        ...data,
        terminal: '4',
    });
};

/** 发送验证码 */
export const apiPostSendCode = (data: { mobile?: string; scene: CodeSence }) => {
    return usePostRequest<[]>('/sms/sendCode', data);
};

/** 重置密码 */
export const apiPostResetPassword = (data: {
    mobile: string;
    code: string;
    password: string;
    password_confirm: string;
}) => {
    return usePostRequest<[]>('/user/resetPassword', data);
};

/** 修改密码 */
export const apiPostChangePassword = (data: {
    old_password: string;
    password: string;
    password_confirm: string;
}) => {
    return usePostRequest<[]>('/user/changePassword', data);
};

/** 无密码时设置密码 */
export const apiPostSetPassword = (data: { password: string; password_confirm: string }) => {
    return usePostRequest<[]>('/user/setPassword', data);
};

/** 发送验证码 */
export const apiPostCheckRegister = (data: { mobile: string }) => {
    return usePostRequest<[]>('/login/checkRegister', data);
};

/** 设置用户信息 - 单个字段 */
export const apiPostSetUserInfoSingle = (data: { field: string; value: string }) => {
    return usePostRequest<[]>('/user/setInfo', data);
};

/** 绑定手机号 */
export const apiPostBindMobile = (data: { mobile: string; code: string; type?: 'bind' | '' }) => {
    return usePostRequest<[]>('/user/bindMobile', data);
};

/** 获取用户信息 */
export const apiGetUser = () => {
    return useGetRequest<UserInfo>('/user/center');
};

/** 获取用户算力流水 */
export const apiGetPowerFlow = () => {
    return useListRequest<{
        change_type: number;
        change_amount: string;
        action: number;
        create_time: null;
        remark: string;
        type_desc: string;
        change_amount_desc: string;
    }>('/accountLog/lists');
};

/** 获取用户算力流水 */
export const apiGetRechargeRecord = () => {
    return useListRequest<RechargeRecordItem>('/recharge/lists');
};

/** 获取微信登录二维码 */
export const apiGetLoginQrcode = (channel?: 'bind') => {
    const params: { channel?: string } = {};
    if (channel) {
        params.channel = channel;
    }
    return useGetRequest<LoginQrcode>('/login/qrcode', params);
};

/** 扫码验证 */
export const apiPostLoginTicket = (data: { key: string; channel?: 'bind' }) => {
    /** status 1-未扫码 2-扫码未登录 4-登录成功 */
    return usePostRequest<{
        user: WechatLoginResponse;
        status: QrLoginStatusEnum;
    }>('/login/ticket', data);
};
