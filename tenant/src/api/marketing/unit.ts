import request from '@/utils/request'

export function getUnitConfig() {
    return request.get({ url: '/marketing.marketingSetting/getMarketinConfig' })
}
export function setUnitConfig(params: any) {
    return request.post({ url: '/marketing.marketingSetting/setMarketingCinfig', params })
}
