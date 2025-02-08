import request from '@/utils/request'

// 充值套餐列表（算力套餐）
export function getpowerPackage(params: any) {
    return request.get({ url: '/power.powerPackage/lists', params })
}

// 充值套餐详情（算力套餐）
export function getpowerPackageDetail(params: any) {
    return request.get({ url: '/power.powerPackage/detail', params })
}

// 充值套餐删除（算力套餐）
export function delpowerPackage(params: any) {
    return request.post({ url: '/power.powerPackage/delete', params })
}

// 充值套餐新增（算力套餐）
export function addpowerPackage(params: any) {
    return request.post({ url: '/power.powerPackage/add', params })
}

// 充值套餐编辑（算力套餐）
export function editpowerPackage(params: any) {
    return request.post({ url: '/power.powerPackage/edit', params })
}
