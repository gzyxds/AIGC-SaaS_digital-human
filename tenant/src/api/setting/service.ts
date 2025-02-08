import request from '@/utils/request'

export function getCustomer() {
    return request.get({ url: '/setting.system.customer/detail' })
}
export function setCustomer(data: any) {
    return request.post({ url: '/setting.system.customer/save', data })
}
