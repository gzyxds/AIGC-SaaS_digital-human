// ----- 声音克隆 ----- //

/** 音色列表 */
export const apiGetVoiceList = (params?: { status?: taskStatusType }) => {
    return useListRequest<VoiceItem>('/voice.voice/lists', params);
};

/** 音色详情 */
export const apiGetVoiceDetail = (params: { id: number }) => {
    return useGetRequest<VoiceItem>('/voice.voice/detail', params);
};

/** 音色编辑 */
export const apiPostVoiceEdit = (params: { page?: number }) => {
    return usePostRequest<VoiceItem>('/voice.voice/edit', params);
};

/** 音色创建 */
export const apiPostVoiceCreate = (data: CreateVoice) => {
    return usePostRequest<VoiceItem>('/voice.voice/add', data);
};

/** 音色删除 */
export const apiPostVoiceDelete = (params: { id: number }) => {
    return usePostRequest<VoiceItem>('/voice.voice/delete', params);
};

// ----- 声音合成 ----- //

/** 合成记录列表 */

export const apiGetCloneVoiceList = (params?: { status: '0' | '1' | '2' }) => {
    return useListRequest<CloneVoiceListItem>('/voice.record/lists', params);
};

/** 合成记录详情 */
export const apiGetCloneVoiceDetail = (params: { id: number }) => {
    return useGetRequest<VoiceItem>('/voice.record/detail', params);
};

/** 合成记录编辑 */
export const apiPostCloneVoiceEdit = (data: { page?: number }) => {
    return usePostRequest<VoiceItem>('/voice.record/edit', data);
};

/** 合成记录创建 */
export const apiPostCloneVoiceCreate = (data: CreateCloneVoice) => {
    return usePostRequest<VoiceItem>('/voice.record/createCloneVoice', data);
};

/** 上传本地音频记录 */
export const apiPostUploadLocalVoice = (data: UploadLocalVoice) => {
    return usePostRequest<VoiceItem>('/voice.record/uploadLocalVoice', data);
};

/** 合成记录删除 */
export const apiPostCloneVoiceDelete = (params: { id: number }) => {
    return usePostRequest<VoiceItem>('/voice.record/delete', params);
};

/** 声音克隆示例 */
export const apiGetCloneExampleList = () => {
    return useListRequest<CloneVoiceListItem>('/voice.sample/lists');
};

/** 系统声音列表 */
export const apiGetSystemVoiceList = () => {
    return usePostRequest<SystemVoiceItem[]>('/voice.sample/presetTimbre');
};

/** 声音克隆配置 */
export const apiGetVoiveCloneConfig = () => {
    return usePostRequest<{ voice_copy: string }>('/voice.voice/getVoiceCloneConfig');
};
