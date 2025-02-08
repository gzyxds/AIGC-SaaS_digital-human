import { useGetRequest, usePostRequest } from '@/composables/useRequest';

interface UserInfo {
    avatar: string;
    nickname: string;
}

// 获取微信公众号登录链接
export const apiGetLoginCodeUrl = (params: any) => {
    return useGetRequest('/login/codeUrl', params);
};

// 微信公众号登录
export const apiGetOALogin = (data: any) => {
    return usePostRequest<UserInfo>('/login/oaLogin', data);
};

// 微信公众号授权绑定
export const apiGetOAAuthBind = (data: any) => {
    return usePostRequest<UserInfo>('/login/oaAuthBind', data);
};
