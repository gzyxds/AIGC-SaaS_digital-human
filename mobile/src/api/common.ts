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

/** 获取通用配置 */
export const apiGetConfig = () => {
    return useGetRequest<SiteConfig>('/index/config');
};

// /** 上传图片 */
// export const uploadImage = (data: { file: File }) =>
//     useUploadRequest<UploadResponse>('/upload/image', data);

// /** 上传视频 */
// export const uploadVideo = (data: { file: File }) =>
//     useUploadRequest<UploadResponse>('/upload/video', data);

// /** 上传文件 */
// export const uploadAudio = (data: { file: File }) =>
//     useUploadRequest<UploadResponse>('/upload/file', data);

/** pc客服配置 */
export const apiGetCustomerConfig = () => {
    return useGetRequest<CustomerConfig>('/index/customer');
};

/**
 * 装修配置
 * type: 1-首页装修
 */
export const apiGetIndexDecorateConfig = (params: { type: number }) => {
    return useGetRequest<DecorateConfig>('/index/decorate', params);
};
