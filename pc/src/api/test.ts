/** 数字人列表 */
export const apiGetAiAvatarListDemo = (params?: { page?: number }) => {
    return useListRequest<AiAvatarItem>('/avatar.aiAvatarRecord/lists', params, { page_size: 10 });
};
