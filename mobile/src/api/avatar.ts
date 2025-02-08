// ----- 数字人形象 ----- //

/** 形象列表 */
// export const apiGetAvatarVideoList = (params?: { page?: number }) => {
//     return useListRequest<AvatarVideoItem>('/video.avatarVideo/lists', params);
// };
export const apiGetAvatarVideoList = (params?: {
    status?: number;
    page_no?: number;
    page_size?: number;
}) => {
    return useGetRequest<Paging<AvatarVideoItem>>('/video.avatarVideo/lists', params);
};

/** 形象详情 */
export const apiGetAvatarVideoDetail = (params: { id: number }) => {
    return useGetRequest<AvatarVideoDetail>('/video.avatarVideo/detail', params);
};

/** 形象编辑 */
export const apiPostAvatarVideoEdit = (params?: { page?: number }) => {
    return usePostRequest<VoiceItem>('/video.avatarVideo/edit', params);
};

/** 形象创建 */
export const apiPostAvatarVideoCreate = (data: CreateAvatarVideo) => {
    return usePostRequest<VoiceItem>('/video.avatarVideo/add', data);
};

/** 形象删除 */
export const apiPostAvatarVideoDelete = (params: { id: number }) => {
    return usePostRequest<VoiceItem>('/video.avatarVideo/delete', params);
};

// ----- 数字人合成 ----- //

/** 数字人列表 */
// export const apiGetAiAvatarList = (params?: { page?: number }) => {
//     return useListRequest<AiAvatarItem>('/avatar.aiAvatarRecord/lists', params);
// };
export const apiGetAiAvatarList = (params?: {
    status?: number | string;
    page_no?: number;
    page_size?: number;
}) => {
    return useGetRequest<Paging<AiAvatarItem>>('/avatar.aiAvatarRecord/lists', params);
};

/** 数字人详情 */
export const apiGetAiAvatarDetail = (params?: { page?: number }) => {
    return useGetRequest<VoiceItem>('/avatar.aiAvatarRecord/detail', params);
};

/** 数字人编辑 */
export const apiPostAiAvatarEdit = (params?: { page?: number }) => {
    return usePostRequest<VoiceItem>('/avatar.aiAvatarRecord/edit', params);
};

/** 数字人创建 */
export const apiPostAiAvatarCreate = (data: CreateAiAvatarRecord) => {
    return usePostRequest<VoiceItem>('/avatar.aiAvatarRecord/createAiAvatar', data);
};

/** 数字人删除 */
export const apiPostAiAvatarDelete = (params?: { id: number }) => {
    return usePostRequest<VoiceItem>('/avatar.aiAvatarRecord/delete', params);
};

/** 数字人创建-全流程 */
export const apiPostCreateCompleteFlow = (data: CreateCompleteFlow) => {
    return usePostRequest<VoiceItem>('/complete.flow/createCompleteFlow', data);
};
