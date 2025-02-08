import request from '@/utils/request'
//数字人视频

// 获取应用配置
export function getAvatarAllConfig() {
    return request.get({ url: '/setting.power.powerConfig/getAllAvatarConfig' })
}
// 获取应用配置
export function getAvatarConfig(params: any) {
    return request.get({ url: '/setting.power.powerConfig/getAvatarConfig', params })
}
// 保存应用配置
export function setAvatarConfig(params: any) {
    return request.post({ url: '/setting.power.powerConfig/setAvatarConfig', params })
}
// 数字分身列表
export function getAvatarRecord(params: any) {
    return request.get({ url: '/avatar.aiAvatarRecord/lists', params })
}

// 数字分身详情
export function getAvatarDetail(params: any) {
    return request.get({ url: '/avatar.aiAvatarRecord/detail', params })
}

// 数字分身删除
export function delAvatar(params: any) {
    return request.post({ url: '/avatar.aiAvatarRecord/delete', params })
}

//声音克隆
//列表
export function getAvatarvoiceRecord(params: any) {
    return request.get({ url: '/voice.avatarVoice/lists', params })
}
//详情
export function getAvatarvoiceDetail(params: any) {
    return request.get({ url: '/voice.avatarVoice/detail', params })
}
//删除
export function delAvatarvoic(params: any) {
    return request.post({ url: '/voice.avatarVoice/delete', params })
}
// 获取应用配置
export function getVoiceCloneConfig() {
    return request.get({ url: '/setting.power.powerConfig/getVoiceCloneConfig' })
}
// 保存应用配置
export function setVoiceCloneConfig(params: any) {
    return request.post({ url: '/setting.power.powerConfig/setVoiceCloneConfig', params })
}

//声音合成
// 获取应用配置
export function getVoiceConfig() {
    return request.get({ url: '/setting.power.powerConfig/getVoiceConfig' })
}
// 保存应用配置
export function setVoiceConfig(params: any) {
    return request.post({ url: '/setting.power.powerConfig/setVoiceConfig', params })
}
export function getvoiceRecord(params: any) {
    return request.get({ url: '/voicerecord.voiceRecord/lists', params })
}
//详情
export function getvoiceDetail(params: any) {
    return request.get({ url: '/voicerecord.voiceRecord/detail', params })
}
//删除
export function delvoiceRecord(params: any) {
    return request.post({ url: '/voicerecord.voiceRecord/delete', params })
}

//视频素材
//列表
export function getVideoRecord(params: any) {
    return request.get({ url: '/video.video/lists', params })
}
//详情
export function getVideoDetail(params: any) {
    return request.get({ url: '/video.video/detail', params })
}
//删除
export function delVideoRecord(params: any) {
    return request.post({ url: '/video.video/delete', params })
}
