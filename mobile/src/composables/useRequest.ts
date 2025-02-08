import config from '@/config';
import { RequestCodeEnum } from '@/enums/requestEnum';
import { useUserStore } from '@/stores/user';
import { throttle } from 'lodash-es';

export interface ResponseType<T = any> {
    code: RequestCodeEnum;
    data: T;
    msg: string;
    show: 1 | 0;
}

export interface ListState {
    page: number;
    page_size: number;
    pedding: boolean;
    count: number;
    isCompleted: boolean;
}

export interface ListGetOptions {
    newParams?: Record<string, any>;
}

export interface ListRefreshOptions {
    newParams?: Record<string, any>;
}

export type RequestOptions = UniNamespace.RequestOptions;

export function useRequest<T = any>(opts?: RequestOptions): Promise<T> {
    if (opts && opts.url[0] !== '/') {
        opts.url = `/${opts.url}`;
    }

    const userStore = useUserStore();

    return new Promise((resolve, reject) => {
        uni.request({
            ...opts,
            header: {
                token: userStore.token,
                version: config.version,
            },
            // url: `${import.meta.env.VITE_APP_API_BASE}${import.meta.env.VITE_APP_API_PREFIX}${
            //     opts?.url
            // }`,
            url: `${config.baseUrl}${config.urlPrefix}${opts?.url}`,
            success: (res) => {
                const result = res.data as ResponseType<T>;

                const codeMap = {
                    [RequestCodeEnum.SUCCESS]: () => {
                        if (result.show === 1) {
                            useToast().success(result.msg);
                        }
                        resolve(result.data);
                    },
                    [RequestCodeEnum.FAIL]: () => {
                        if (result.show === 1) {
                            useToast(result.msg, { duration: 2000 });
                        }
                        reject(result.msg);
                    },
                    [RequestCodeEnum.LOGIN_FAILURE]: () => {
                        useToast(result.msg);
                        // 登录过期操作
                        loginExpire();
                        reject(result.msg);
                    },
                    [RequestCodeEnum.NOT_INSTALL]: () => {
                        useToast().error('系统未安装');
                        reject(new Error('系统未安装'));
                    },
                    [RequestCodeEnum.OPEN_NEW_PAGE]: () => {
                        // #ifdef H5
                        window.open(
                            typeof result.data === 'string' ? result.data : (result.data as any).url
                        );
                        // #endif
                        // #ifndef H5
                        useToast().error('无法跳转新页面');
                        reject(new Error('无法跳转新页面'));
                        // #endif
                    },
                    [RequestCodeEnum.FORBIDDEN]: () => {
                        useToast().error('无访问权限');
                        reject(new Error('无访问权限'));
                    },
                    [RequestCodeEnum.NOT_FOUND]: () => {
                        useToast().error('租户不存在');
                        reject(new Error('租户不存在'));
                    },
                }[result.code];

                if (codeMap) {
                    codeMap();
                } else {
                    reject(result.msg);
                }

                // 滑动刷新token过期时间
                userStore.refreshToken();

                resolve(result.data);
            },
            fail: (err) => {
                console.error(err);
                useToast(err.errMsg, { duration: 2000 });
                reject(err);
            },
            complete: () => {},
        });
    });
}

export function useGetRequest<T>(
    url: string,
    params?: Record<string, any>,
    opts?: RequestOptions
): Promise<T> {
    return useRequest<T>({
        url,
        ...opts,
        data: params,
        method: 'GET',
    });
}

/**
 * 列表请求
 * @param url 请求路径
 * @param params 请求参数
 * @param config 页码设置
 * @param opts 其他请求参数
 */
export function useListRequest<T>(
    url: string,
    params?: Record<string, any>,
    config?: { page?: number; page_size?: number },
    opts?: RequestOptions
) {
    let initialize = false;

    const paramsInit: Record<string, any> = Object.assign({}, toRaw(params));

    const list = ref<T[]>([]);

    const state = reactive<ListState>({
        page: config?.page || 1,
        page_size: config?.page_size || 20,
        pedding: true,
        count: 0,
        isCompleted: false,
    });

    /** 获取列表 */
    const getList = async (getOpts?: ListGetOptions): Promise<ListResponse<T>> => {
        state.pedding = true;

        if (getOpts) {
            params = { ...params, ...getOpts.newParams };
        }

        console.log(list.value.length < state.count);

        if (initialize) {
            if (list.value.length < state.count) {
                state.page += 1;
            } else {
                state.isCompleted = true;
                return Promise.resolve({
                    count: state.count,
                    extend: [],
                    lists: list.value as T[],
                    page_no: state.page,
                    page_size: state.page_size,
                });
            }
        }

        return new Promise((resolve, reject) => {
            useRequest<ListResponse<T>>({
                ...opts,
                url,
                data: {
                    ...{ page_no: state.page, page_size: state.page_size, ...params },
                    params,
                },
            })
                .then((res) => {
                    initialize = true;
                    state.count = res.count;

                    list.value = [...list.value, ...res.lists] as T[];

                    if (list.value.length >= state.count) {
                        state.isCompleted = true;
                    }

                    state.pedding = false;
                    resolve(res);
                })
                .catch((err) => {
                    state.page -= 1;
                    state.pedding = false;
                    reject(err);
                })
                .finally(() => {
                    state.pedding = false;
                });
        });
    };

    /**
     * 刷新列表
     * 此操作会重置所有参数
     */
    const refresh = async (refreshOpts?: ListRefreshOptions) => {
        state.page = 1;
        state.count = 0;
        list.value = [];
        return await getList({ newParams: refreshOpts?.newParams });
    };

    /** 重置参数 */
    const resetParams = (immediate: boolean = true) => {
        state.page = 1;
        state.count = 0;
        list.value = [];

        if (params) {
            params = paramsInit;
        }
        if (immediate) {
            getList();
        }
    };

    return {
        list,
        state,
        getList,
        refresh,
        resetParams,
    };
}

export function usePostRequest<T>(
    url: string,
    data?: Record<string, any>,
    opts?: RequestOptions
): Promise<T> {
    return useRequest<T>({
        url,
        ...opts,
        data,
        method: 'POST',
    });
}

/**
 * 文件上传（带上传进度）
 * @param opts 上传参数
 * @example 使用示例
 * const {onProgressUpdate} = useUpload({上传参数})
 * onProgressUpdate((res) => {
 *   console.log(res.progress) // 上传进度
 *   console.log(res.totalBytesExpectedToSend) // 已经上传的数据长度，单位 Bytes
 *   console.log(res.totalBytesSent) // 预期需要上传的数据总长度，单位 Bytes
 * })
 */
export const useUpload = <T = UploadResponse>(opts: {
    type: 'image' | 'video' | 'file';
    data: { file: File | string; [key: string]: any };
    success: (res: T) => void;
    fail: (errMsg: string) => void;
    complete?: () => void;
}): UniApp.UploadTask => {
    const { type, data, success, fail, complete } = opts;
    const { file, ...formData } = data;
    const url = { image: '/upload/image', video: '/upload/video', file: '/upload/file' }[type];

    let fileInfo;
    if (typeof file === 'string') {
        fileInfo = { filePath: file };
    } else {
        fileInfo = { file };
    }

    const userStore = useUserStore();
    return uni.uploadFile({
        header: {
            token: userStore.token,
            version: config.version,
        },
        // url: `${import.meta.env.VITE_APP_API_BASE}${import.meta.env.VITE_APP_API_PREFIX}${
        //     opts?.url
        // }`,
        url: `${config.baseUrl}${config.urlPrefix}${url}`,
        name: 'file',
        ...fileInfo,
        formData,
        success: (res) => {
            const result = JSON.parse(res.data) as ResponseType<T>;
            const codeMap = {
                [RequestCodeEnum.SUCCESS]: () => {
                    if (result.show === 1) {
                        useToast().success(result.msg);
                    }
                    success(result.data);
                },
                [RequestCodeEnum.FAIL]: () => {
                    if (result.show === 1) {
                        useToast(result.msg);
                    }
                    fail(result.msg);
                },
                [RequestCodeEnum.LOGIN_FAILURE]: () => {
                    useToast(result.msg);
                    loginExpire();
                    fail(result.msg);
                },
                [RequestCodeEnum.NOT_INSTALL]: () => {
                    useToast().error('系统未安装');
                    fail('系统未安装');
                },
                [RequestCodeEnum.OPEN_NEW_PAGE]: () => {
                    // #ifdef H5
                    window.open(
                        typeof result.data === 'string' ? result.data : (result.data as any).url
                    );
                    // #endif
                    // #ifndef H5
                    useToast().error('无法跳转新页面');
                    fail('无法跳转新页面');
                    // #endif
                },
                [RequestCodeEnum.FORBIDDEN]: () => {
                    useToast().error('无访问权限');
                    fail('无访问权限');
                },
                [RequestCodeEnum.NOT_FOUND]: () => {
                    useToast().error('租户不存在');
                    fail('租户不存在');
                },
            }[result.code];

            if (codeMap) {
                codeMap();
            } else {
                fail(result.msg);
            }

            // 滑动刷新token过期时间
            userStore.refreshToken();
        },
        fail: (err) => {
            console.error(err);
            useToast().error(err.errMsg);
            fail(err.errMsg);
        },
        complete: () => {
            complete?.();
        },
    });
};

/**
 * 视频、图片下载
 */
export const useDownloadFile = (opts: {
    type: 'image' | 'video';
    fileUrl: string;
    success: (res: any) => void;
    fail: (errMsg: string) => void;
    complete?: () => void;
}) => {
    const { type, fileUrl, success, fail, complete } = opts;

    if (fileUrl === '') {
        fail('视频路径为空');
        return useToast().error('视频路径为空');
    }

    return uni.downloadFile({
        url: fileUrl,
        success: (res) => {
            if (res.statusCode === 200) {
                const handler = {
                    image: () => {
                        uni.saveImageToPhotosAlbum({
                            filePath: res.tempFilePath,
                            success: (saveRes) => {
                                useToast().success('已保存到相册');
                                success(saveRes);
                            },
                            fail: (saveErr) => {
                                if (
                                    saveErr.errMsg ===
                                    'saveImageToPhotosAlbum:fail invalid file type'
                                ) {
                                    useToast().error('下载格式错误');
                                } else {
                                    useToast().error('保存照片失败');
                                }

                                fail(`保存照片失败${saveErr.errMsg}`);
                            },
                        });
                    },
                    video: () => {
                        uni.saveVideoToPhotosAlbum({
                            filePath: res.tempFilePath,
                            success: (saveRes) => {
                                useToast().success('已保存到相册');
                                success(saveRes);
                            },
                            fail: (saveErr) => {
                                if (
                                    saveErr.errMsg ===
                                    'saveImageToPhotosAlbum:fail invalid file type'
                                ) {
                                    useToast().error('下载格式错误');
                                } else {
                                    useToast().error('保存视频失败');
                                }
                                fail(`保存视频失败${saveErr.errMsg}`);
                            },
                        });
                    },
                }[type];
                handler();
            } else if (res.statusCode === 404) {
                useToast().error('下载资源不存在');
                fail('下载资源不存在');
            } else {
                useToast().error('下载失败');

                fail('下载失败');
            }
        },
        fail: (err) => {
            console.error(err);
            if (typeof err !== 'string') {
                err = JSON.stringify(err);
            }
            fail(err);
        },
        complete: () => {
            complete?.();
        },
    });
};

// 登录过期操作
const loginExpire = throttle(async () => {
    // const { code }: any = await uni.login({
    //     provider: 'weixin',
    // });
    // const codedData = await apiPostCodeBindAccount({
    //     code,
    // });
    // console.log('根据微信小程序code获取对应账号信息', codedData);

    const userStore = useUserStore();

    // if (codedData?.id) {
    //     // 有账号-登录
    //     // 重新获取code
    //     const { code }: any = await uni.login({
    //         provider: 'weixin',
    //     });
    //     const data = await apiPostMnpLogin({
    //         code,
    //     });
    //     console.log('获取账号数据', data);
    //     userStore.login(data.token);
    // } else {
    //     // 无账号-清空登录信息
    //     userStore.logout();
    // }
    userStore.logout();
}, 1000);
