import request from '@/utils/request'

export function getsensitiveLists(params: any) {
    return request.get({ url: '/sensitive.words/lists', params })
}
export function getsensitiveDetail(params: any) {
    return request.get({ url: '/sensitive.words/detail', params })
}

export function addSensitive(params: any) {
    return request.post({ url: '/sensitive.words/add', params })
}
export function delSensitive(params: any) {
    return request.post({ url: '/sensitive.words/delete', params })
}
export function editSensitive(params: any) {
    return request.post({ url: '/sensitive.words/edit', params })
}
export function getSensitiveConfig(params?: any) {
    return request.get({ url: '/sensitive.words/getSensitiveSetting', params })
}
export function setSensitiveConfig(params: any) {
    return request.post({ url: '/sensitive.words/setSensitiveSetting', params })
}
