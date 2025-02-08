import * as _ from 'radash';
import type { UnwrapRef } from 'vue';

import type { UseFetchOptions } from '#app';
import { PageEnum } from '~/enums/pageEnum';
import { RequestCodeEnum } from '~/enums/requestEnum';

interface ResponseType<T = any> {
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
    mode?: 'load' | 'page';
    resetAll?: boolean;
    newParams?: Record<string, any>;
    polling?: boolean;
    resetPage?: boolean;
}

export interface ListRefreshOptions {
    mode?: 'load' | 'page';
    newParams?: Record<string, any>;
}

interface RequestOptions {
    method?: 'GET' | 'HEAD' | 'PATCH' | 'POST' | 'PUT' | 'DELETE' | 'CONNECT' | 'OPTIONS' | 'TRACE';
    body?: RequestInit['body'] | Record<string, any>;
    ignoreResponseError?: boolean;
    params?: Record<string, any>;
    query?: Record<string, any>;
    parseResponse?: (responseText: string) => any;
    duplex?: 'half' | undefined;
    agent?: unknown;
    timeout?: number;
    retry?: number | false;
    retryDelay?: number;
    retryStatusCodes?: number[];
    headers?: Record<string, string> & HeadersInit;
}

interface PendingRequestOptions {
    cache?: boolean;
    immediate?: boolean;
}

interface RequestError<T = any> {
    request: Request | string;
    response: RequestResponse<T>;
    name: string;
    data?: T;
    status?: number;
    statusText?: string;
    statusCode?: number;
    statusMessage?: string;
}

interface RequestResponse<T> extends Response {
    _data?: T;
}

/**
 * $fetch封装
 * @param url 请求路径
 * @param opts 其他参数
 */
export const useRequest = <T = any>(url: string, opts?: RequestOptions): Promise<T> => {
    const runtimeConfig = useRuntimeConfig();
    const nuxtApp = useNuxtApp();
    const userStore = useUserStore(nuxtApp.$pinia);

    if (url[0] !== '/') {
        url = `/${url}`;
    }

    return new Promise((resolve, reject) => {
        $fetch<ResponseType<T>>(url, {
            ...opts,
            baseURL: `${runtimeConfig.public.apiBase}${runtimeConfig.public.apiPrefix}`,
            timeout: runtimeConfig.public.apiTimeout,
            method: opts?.method || 'GET',
            onRequest: (_ctx) => {
                const headersToken = opts?.headers?.token;
                const token = headersToken
                    ? headersToken
                    : (userStore.token ?? userStore.temporary_token);
                if (token) {
                    _ctx.options.headers.set('token', token);
                }
            },
        })
            .then((res) => {
                const codeMap = {
                    [RequestCodeEnum.SUCCESS]: () => {
                        isClient(() => {
                            if (res.show === 1) {
                                useMessage().success(res.msg);
                            }
                        });
                        resolve(res.data);
                    },
                    [RequestCodeEnum.FAIL]: () => {
                        isClient(() => {
                            if (res.show === 1) {
                                useMessage().error(res.msg);
                            }
                        });
                        reject(res.msg);
                    },
                    [RequestCodeEnum.LOGIN_FAILURE]: () => {
                        isClient(() => {
                            userStore.logout(true);
                        });
                        reject(res.msg);
                    },
                    [RequestCodeEnum.NOT_INSTALL]: () => {
                        isClient(() => {
                            window.location.replace(PageEnum.INSTALL);
                        });
                    },
                    [RequestCodeEnum.OPEN_NEW_PAGE]: () => {
                        isClient(() => {
                            window.open(
                                typeof res.data === 'string' ? res.data : (res.data as any).url
                            );
                        });
                    },
                    [RequestCodeEnum.FORBIDDEN]: () => {
                        throw createError({
                            statusCode: 403,
                            statusMessage: '未授权访问',
                            fatal: true,
                        });
                    },
                    [RequestCodeEnum.NOT_FOUND]: () => {
                        throw createError({
                            statusCode: 404,
                            statusMessage: '租户不存在',
                            fatal: true,
                        });
                    },
                }[res.code];

                if (codeMap) {
                    codeMap();
                } else {
                    reject(res.msg);
                }
            })
            .catch((err: RequestError<T>) => {
                isClient(() => {
                    useMessage().error(err.request as string, { title: err.name });
                });
                reject(err);
            });
    });
};

/**
 * useFetch封装
 * @param url 请求路径
 * @param opts 其他参数
 */
export const useAsyncRequest = async <DataT = any, ErrorT = RequestError>(
    url: string,
    opts?: UseFetchOptions<ResponseType<DataT>>
) => {
    const userStore = useUserStore();
    const runtimeConfig = useRuntimeConfig();

    if (url[0] !== '/') {
        url = `/${url}`;
    }

    const { data, clear, error, execute, refresh, status } = await useFetch<
        ResponseType<DataT>,
        ErrorT
    >(url, {
        baseURL: `${runtimeConfig.public.apiBase}${runtimeConfig.public.apiPrefix}`,
        timeout: runtimeConfig.public.apiTimeout,
        method: opts?.method || 'GET',
        onRequest: (_ctx) => {
            const userStore = useUserStore();
            const token = userStore.token;
            if (token) {
                _ctx.options.headers.set('token', token);
            }
        },
        onResponse: (_ctx) => {
            const result: ResponseType<DataT> = _ctx.response._data;

            const codeHandlerMap = {
                [RequestCodeEnum.SUCCESS]: () => {
                    isClient(() => {
                        if (result.show === 1) {
                            useMessage().success(result.msg);
                        }
                    });
                },
                [RequestCodeEnum.FAIL]: () => {
                    isClient(() => {
                        if (result.show === 1) {
                            useMessage().error(result.msg);
                        }
                    });
                    throw new Error(result.msg);
                },
                [RequestCodeEnum.LOGIN_FAILURE]: () => {
                    isClient(() => {
                        userStore.logout(true);
                    });
                },
                [RequestCodeEnum.NOT_INSTALL]: () => {
                    isClient(() => {
                        window.location.replace(PageEnum.INSTALL);
                    });
                },
                [RequestCodeEnum.OPEN_NEW_PAGE]: () => {
                    isClient(() => {
                        window.open(
                            typeof result.data === 'string' ? result.data : (result.data as any).url
                        );
                    });
                },
                [RequestCodeEnum.FORBIDDEN]: () => {
                    throw createError({
                        statusCode: 403,
                        statusMessage: '未授权访问',
                        fatal: true,
                    });
                },
                [RequestCodeEnum.NOT_FOUND]: () => {
                    throw createError({
                        statusCode: 404,
                        statusMessage: '租户不存在',
                        fatal: true,
                    });
                },
            }[result.code];
            if (codeHandlerMap) {
                codeHandlerMap();
            } else {
                throw new Error(result.msg);
            }
        },
        ...opts,
    });

    return {
        data: data.value?.data,
        clear,
        error,
        execute,
        refresh,
        status,
        pending: computed(() => status.value === 'pending' || status.value === 'idle'),
    };
};

/**
 * 为CSR请求添加状态
 * @param requestFn CSR请求对象
 * @param opts 其他参数
 * @example usePendingRequest(() => $fetch('/api/test')) 不会立即执行
 * @example usePendingRequest(() => $fetch('/api/test')).execute() 会立即执行
 */
export const usePendingRequest = <T = any>(
    requestFn: () => Promise<T>,
    opts?: PendingRequestOptions
) => {
    const data = ref<T | null>(null);
    const pending = ref<boolean>(true);
    const error = ref<RequestError | null>(null);
    /** 开启缓存后不会先清空 */
    const cache = opts?.cache || true;
    /** 是否立即执行 */
    const immediate = opts?.immediate || false;

    const execute = async (): Promise<void> => {
        pending.value = true;
        error.value = null;
        if (!cache) {
            data.value = null;
        }

        requestFn()
            .then((res) => {
                data.value = res;
            })
            .catch((err: RequestError) => {
                error.value = err;
            })
            .finally(() => {
                pending.value = false;
            });
    };

    if (immediate) {
        execute();
    }

    return {
        data,
        pending,
        error,
        refresh: execute,
        execute,
    };
};

/**
 * 列表请求
 * @param url 请求路径
 * @param params 请求参数
 * @param config 页码设置
 * @param opts 其他请求参数
 */
export const useListRequest = <T>(
    url: string,
    params?: Record<string, any>,
    config?: { page?: number; page_size?: number },
    opts?: RequestOptions
) => {
    let initialize = false;
    let getMode: 'load' | 'page' | null = null;

    const paramsInit: Record<string, any> = Object.assign({}, toRaw(params));

    const list = ref<T[]>([]);

    const state = reactive<ListState>({
        page: config?.page || 1,
        page_size: config?.page_size || 20,
        pedding: true,
        count: 0,
        isCompleted: false,
    });

    /**
     * 刷新列表
     * 此操作会重置所有参数
     */
    const refresh = async (refreshOpts?: ListRefreshOptions) => {
        const mode = refreshOpts?.mode || 'page';
        getMode = mode;

        return await getList({ mode, resetAll: true, newParams: refreshOpts?.newParams });
    };

    /** 获取列表 */
    const getList = async (getOpts?: ListGetOptions): Promise<ListResponse<T>> => {
        const mode = getOpts?.mode || 'page';

        /** 重置页码 */
        const resetHandle = () => {
            resetPage(false);
            initialize = false;

            if (mode === 'load') {
                list.value = [];
            }
        };

        /** 固定请求模式为列表还是下拉 */
        if (getMode === null) {
            getMode = mode;
        } else {
            if (getMode !== mode) {
                resetHandle();
            }
        }

        if (getOpts?.resetPage) {
            resetHandle();
        }

        /** 是否重置所有参数 */
        if (getOpts?.resetAll) {
            resetParams(false);
            resetHandle();
        }

        if (getOpts?.newParams) {
            params = { ...params, ...getOpts.newParams };
        }

        /** 如果是下拉加载则每次下拉页码增加 */
        if (mode === 'load' && initialize && !getOpts?.polling) {
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
            state.pedding = true;
            useRequest<ListResponse<T>>(url, {
                ...opts,
                params: getOpts?.polling
                    ? { page_no: 1, page_size: state.page * state.page_size, ...params }
                    : { page_no: state.page, page_size: state.page_size, ...params },
                method: 'GET',
            })
                .then((res) => {
                    initialize = true;

                    state.count = res.count;

                    if (mode === 'load') {
                        if (getOpts?.polling) {
                            list.value = res.lists;
                        } else {
                            list.value = [...list.value, ...res.lists] as T[];
                        }
                        if (list.value.length >= state.count) {
                            state.isCompleted = true;
                        }
                    } else {
                        if (getOpts?.polling) {
                            list.value.forEach((item, index) => {
                                if (!_.isEqual(list.value[index] as T, res.lists[index])) {
                                    (list.value[index] as T) = res.lists[index];
                                }
                            });
                        } else {
                            list.value = res.lists;
                        }
                    }
                    state.pedding = false;

                    resolve(res);
                })
                .catch((err) => {
                    if (mode === 'load' && initialize) state.page -= 1;
                    state.pedding = false;

                    reject(err);
                })
                .finally(() => {
                    state.pedding = false;
                });
        });
    };

    /** 重置页码 */
    const resetPage = (immediate: boolean = true) => {
        state.page = 1;
        if (immediate) {
            getList();
        }
    };

    /** 重置参数 */
    const resetParams = (immediate: boolean = true) => {
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
        resetPage,
        resetParams,
    };
};

/**
 * GET请求
 * @param url 请求路径
 * @param params 请求参数
 * @param opts 其他请求参数
 */
export const useGetRequest = <T>(
    url: string,
    params?: Record<string, any>,
    opts?: RequestOptions
) => {
    return useRequest<T>(url, {
        ...opts,
        params,
        method: 'GET',
    });
};

/**
 * POST请求
 * @param url 请求路径
 * @param data 请求体参数
 * @param opts 其他请求参数
 */
export const usePostRequest = <T>(
    url: string,
    data?: Record<string, any>,
    opts?: RequestOptions
) => {
    return useRequest<T>(url, {
        ...opts,
        body: data,
        method: 'POST',
    });
};

/**
 * 文件上传
 * @param url 请求路径
 * @param data 上传的文件和其他参数
 * @param opts 其他请求选项
 */
export const useUploadRequest = <T = any>(
    url: string,
    data?: Record<string, any>,
    opts?: RequestOptions
): Promise<T> => {
    // 创建 FormData 对象，将 data 的键值对追加到 FormData 中
    const formData = new FormData();
    if (data) {
        Object.keys(data).forEach((key) => {
            formData.append(key, data[key]);
        });
    }

    // 调用 useRequest 发送请求
    return useRequest<T>(url, {
        ...opts,
        body: formData,
        method: 'POST',
    });
};

/**
 * 静态请求 - 不会被立即激活
 * @param url 请求路径
 * @param opts 其他请求参数
 */
export const useStaticRequest = async <DataT = any, ErrorT = RequestError>(
    url: string,
    opts?: UseFetchOptions<ResponseType<DataT>>
) => {
    return useAsyncRequest<DataT, ErrorT>(url, {
        ...opts,
        immediate: false,
    });
};
