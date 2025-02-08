import request from '@/utils/request'

export function getGuide() {
    return request.get({ url: '/setting.web.guideSetting/getGuide' })
}
export function setGuide(params: any) {
    return request.post({ url: '/setting.web.guideSetting/setGuide', params })
}
