import type {
  AxiosInstance,
  AxiosProgressEvent,
  AxiosRequestConfig,
  AxiosResponse,
  InternalAxiosRequestConfig,
} from 'axios'

import { RequestCodeEnum } from '@/enums/requestEnum'
import axios from 'axios'

interface ResponseType<T = any> {
  code: RequestCodeEnum
  data: T
  msg: string
  show: 1 | 0
}

export class AxiosService {
  private instance: AxiosInstance

  constructor() {
    this.instance = axios.create({
      timeout: 10 * 60 * 1000,
    })

    this.setupInterceptors()
  }

  // 设置拦截器
  private setupInterceptors() {
    // 请求拦截器
    this.instance.interceptors.request.use(
      (config: InternalAxiosRequestConfig) => {
        config.baseURL = `${import.meta.env.VITE_APP_API_BASE}${import.meta.env.VITE_APP_API_PREFIX}`
        const userStore = useUserStore()
        const headersToken = config.headers.token
        const token = headersToken || userStore.token

        if (token) {
          config.headers.set('Token', token)
        }
        return config
      },
      (error) => {
        return Promise.reject(error)
      },
    )

    // 响应拦截器
    this.instance.interceptors.response.use(
      (response: AxiosResponse<ResponseType | BlobPart>) => {
        if (response.config.responseType === 'blob') {
          const contentDisposition = response.headers['content-disposition']
          const urlFileSuffix = response.config.params.url?.split('.').pop()
          const paramFileName = response.config.params.name
          const paramSuffix = response.config.params.format
          let paramFileNames = null
          if (paramFileName && paramSuffix) {
            paramFileNames = `${paramFileName}.${paramSuffix}`
          }
          const filename = paramFileNames || (contentDisposition
            ? decodeURIComponent(
                contentDisposition.split('filename=')[1]?.replace(/"/g, ''),
              )
            : `${createFileName()}.${urlFileSuffix}`)

          // 创建 Blob 对象
          const blob = new Blob([response.data as BlobPart], {
            type: response.headers['content-type'],
          })

          // 创建下载链接并触发下载
          const link = document.createElement('a')
          link.href = URL.createObjectURL(blob)
          link.download = filename
          link.click()

          // 释放 URL 对象
          URL.revokeObjectURL(link.href)
          return link.href
        }
        const responseJson = response.data as ResponseType
        const showMessage = responseJson.show
        const message = responseJson.msg
        const userStore = useUserStore()
        const resultData = responseJson.data

        const codeMap = {
          [RequestCodeEnum.SUCCESS]: () => {
            if (showMessage === 1) {
              useToast().success(message)
            }
          },
          [RequestCodeEnum.FAIL]: () => {
            if (showMessage === 1) {
              useToast().error(message)
            }
            return Promise.reject(message)
          },
          [RequestCodeEnum.LOGIN_FAILURE]: () => {
            userStore.logout()
            return Promise.reject(message)
          },
          [RequestCodeEnum.NOT_INSTALL]: () => {
            useToast().error('系统未安装')
            return Promise.reject(new Error('系统未安装'))
          },
          [RequestCodeEnum.OPEN_NEW_PAGE]: () => {
            // #ifdef H5
            window.open(
              typeof resultData.data === 'string' ? resultData.data : (resultData.data as any).url,
            )
            // #endif
            // #ifndef H5
            useToast().error('无法跳转新页面')
            return Promise.reject(new Error('无法跳转新页面'))
            // #endif
          },
          [RequestCodeEnum.FORBIDDEN]: () => {
            useToast().error('无访问权限')
            return Promise.reject(new Error('无访问权限'))
          },
          [RequestCodeEnum.NOT_FOUND]: () => {
            useToast().error('租户不存在')
            return Promise.reject(new Error('租户不存在'))
          },
        }[responseJson.code]

        if (codeMap) {
          codeMap()
        }
        else {
          return Promise.reject(message)
        }
        userStore.refreshToken()
        return responseJson.data
      },
      (error) => {
        useToast().error(error.message)
        return Promise.reject(error)
      },
    )
  }

  // 通用 GET 请求
  public get<T = any, K = Record<string, any>>(
    url: string,
    params?: K,
    config?: AxiosRequestConfig,
  ): Promise<T> {
    return this.instance.get(url, { params, ...config })
  }

  // 通用 POST 请求
  public post<T = any, K = Record<string, any>>(
    url: string,
    data?: K,
    config?: AxiosRequestConfig,
  ): Promise<T> {
    return this.instance.post(url, data, { ...config })
  }

  // 通用 GET 请求
  public download<T = any, K = Record<string, any>>(
    url: string,
    params?: K,
    config?: AxiosRequestConfig,
  ): Promise<T> {
    return this.instance.get(url, {
      params,
      ...config,
      responseType: 'blob',
    })
  }

  // 上传请求
  public upload<T = any>(url: string, data?: any, config?: AxiosRequestConfig): Promise<T> {
    const formData = new FormData()
    if (data) {
      Object.keys(data).forEach((key) => {
        formData.append(key, data[key])
      })
    }
    return this.instance.post(url, formData, {
      ...config,
      headers: {
        'Content-Type': 'multipart/form-data',
        ...(config?.headers || {}),
      },
    })
  }
}
