import type { AxiosProgressEvent, AxiosRequestConfig } from 'axios';

import { AxiosService } from '@/composables/useAxiosRequest';

const useAxiosRequest = new AxiosService();

interface DownloadFileParams {
    url: string;
    name: string;
    format: string;
    onProgress?: (progressEvent: AxiosProgressEvent) => void;
}

/** 上传图片 */
export const uploadImage = (data: { file: File }) =>
    useUploadRequest<UploadResponse>('/upload/image', data);

/** 上传视频 */
export const uploadVideo = (data: { file: File }) =>
    useUploadRequest<UploadResponse>('/upload/video', data);

/** 上传文件 */
export const uploadAudio = (data: { file: File }) =>
    useUploadRequest<UploadResponse>('/upload/file', data);

/** axios通用上传方法 */
export const uploadFile = (
    type: 'image' | 'video' | 'file',
    data: { file: File },
    config?: AxiosRequestConfig
) => useAxiosRequest.upload<UploadResponse>(`/upload/${type}`, data, config);

/** 站点配置 */
export const apiGetSiteConfig = () => {
    return useGetRequest<SiteConfig>('/pc/config');
};

/** 获取全部协议 */
export const apiGetAllPolicy = () => {
    return useGetRequest<AgreementItem[]>('/index/allPolicy');
};

/** 获取单条协议 */
export const apiGetPolicy = (
    type: 'privacy' | 'service' | 'use' | 'recharge' | 'disclaimer' | 'currency'
) => {
    return useGetRequest<{ title: string; content: string }>('/index/policy', { type });
};

/** pc装修配置 */
export const apiGetDecorateConfig = () => {
    return useGetRequest<DecorateConfig>('/pc/getDecorate');
};

/** 文件下载 */
export const apiGetDownloadFile = (params: DownloadFileParams) => {
    return useAxiosRequest.download('/download/download', params, {
        onDownloadProgress: params.onProgress,
    });
};

/** pc客服配置 */
export const apiGetCustomerConfig = () => {
    return useGetRequest<CustomerConfig>('/index/customer');
};
