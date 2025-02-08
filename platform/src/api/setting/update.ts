import request from '@/utils/request'

export function getUpgradeLists(params: any) {
    return request.get({ url: '/upgrade.upgrade/lists', params })
}

// 下载更新包
export function upgradeDownloadPkg(params: any) {
    return request.post({ url: '/upgrade.upgrade/downloadPkg', params })
}

// 一键更新
export function upgrade(params: any) {
    return request.post({ url: '/upgrade.upgrade/upgrade', params, timeout: 120 * 1000 })
}
