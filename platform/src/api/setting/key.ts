// import { getConfig } from '@/api/app'
import request from '@/utils/request'

/** 获取密钥配置 */
export function getKeyConfig() {
    return request.get({ url: '/setting.api.api_config/getConfig' })
}

/** 设置存储引擎信息 */
export function setKeyConfig(params: any) {
    return request.post({ url: '/setting.api.api_config/setConfig', params })
}
