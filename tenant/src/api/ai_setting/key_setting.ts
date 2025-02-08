import request from '@/utils/request'

// key池
export function getkeyLists(params: any) {
    return request.get({ url: '/key.keyPool/lists', params })
}
export function getdetail(params: any) {
    return request.get({ url: '/key.keyPool/detail', params })
}
export function addKey(params: any) {
    return request.post({ url: '/key.keyPool/add', params })
}
export function editKey(params: any) {
    return request.post({ url: '/key.keyPool/edit', params })
}
export function delKey(params: any) {
    return request.post({ url: '/key.keyPool/del', params })
}
export function changeKey(params: any) {
    return request.post({ url: '/key.keyPool/status', params })
}
export function getmoduleList(params: any) {
    return request.get({ url: '/key.keyPool/moduleList', params })
}
