import request from '@/utils/request'

export function getGiftConfig() {
    return request.get({ url: '/marketing.marketingSetting/getGiftConfig' })
}
export function setGiftConfig(params: any) {
    return request.post({ url: '/marketing.marketingSetting/setGiftConfig', params })
}
