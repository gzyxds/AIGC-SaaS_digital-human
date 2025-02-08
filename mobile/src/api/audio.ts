// ----- 声音克隆 ----- //

/** 音色列表 */
// export function apiGetVoiceList(params?: { status?: taskStatusType }) {
//   return useListRequest<VoiceItem>('/voice.voice/lists', params)
// }
export function apiGetVoiceList(params?: { status?: taskStatusType, page_no?: number, page_size?: number }) {
  return useGetRequest<Paging<VoiceItem>>('/voice.voice/lists', params)
}

/** 音色详情 */
export function apiGetVoiceDetail(params: { id: number }) {
  return useGetRequest<VoiceItem>('/voice.voice/detail', params)
}

/** 音色编辑 */
export function apiPostVoiceEdit(params: { page?: number }) {
  return usePostRequest<VoiceItem>('/voice.voice/edit', params)
}

/** 音色创建 */
export function apiPostVoiceCreate(data: CreateVoice) {
  return usePostRequest<VoiceItem>('/voice.voice/add', data)
}

/** 音色删除 */
export function apiPostVoiceDelete(params: { id: number }) {
  return usePostRequest<VoiceItem>('/voice.voice/delete', params)
}

// ----- 声音合成 ----- //

/** 合成记录列表 */
export function apiGetCloneVoiceList(params?: { status?: '0' | '1' | '2', page_no?: number, page_size?: number }) {
  return useGetRequest<Paging<CloneVoiceListItem>>('/voice.record/lists', params)
}

/** 合成记录详情 */
export function apiGetCloneVoiceDetail(params: { id: number }) {
  return useGetRequest<VoiceItem>('/voice.record/detail', params)
}

/** 合成记录编辑 */
export function apiPostCloneVoiceEdit(data: { page?: number }) {
  return usePostRequest<VoiceItem>('/voice.record/edit', data)
}

/** 合成记录创建 */
export function apiPostCloneVoiceCreate(data: CreateCloneVoice) {
  return usePostRequest<VoiceItem>('/voice.record/createCloneVoice', data)
}

/** 合成记录删除 */
export function apiPostCloneVoiceDelete(params: { id: number }) {
  return usePostRequest<VoiceItem>('/voice.record/delete', params)
}

/** 声音克隆示例 */
export function apiGetCloneExampleList() {
  return useListRequest<CloneVoiceListItem>('/voice.sample/lists')
}

/** 系统声音列表 */
export function apiGetSystemVoiceList() {
  return usePostRequest<SystemVoiceItem[]>('/voice.sample/presetTimbre')
}

/** 声音克隆配置 */
export function apiGetVoiveCloneConfig() {
  return usePostRequest<{ voice_copy: string }>('/voice.voice/getVoiceCloneConfig')
}
