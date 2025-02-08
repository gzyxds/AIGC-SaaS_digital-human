import { StatusEnum, TaskStatusEnum } from '@/enums/variableEnum';

export const checkVideoUrl = (url: string) => {
    // 定义允许的视频文件后缀
    const videoExtensions = /\.(wmv|avi|mpg|mpeg|3gp|mov|mp4|flv|f4v|rmvb|mkv)$/i;

    try {
        // 使用 URL 构造函数验证链接格式
        const parsedUrl = new URL(url);

        // 检查链接是否以允许的视频扩展名结尾
        return videoExtensions.test(parsedUrl.pathname);
    } catch (e) {
        // 如果 URL 构造抛出异常，说明不是合法的 URL
        return false;
    }
};

export const fileTypes = {
    'image/jpeg': '.jpg',
    'image/png': '.png',
    'image/gif': '.gif',
    'image/webp': '.webp',
    'image/x-icon': '.ico',
    'application/zip': '.zip',
    'application/vnd.rar': '.rar',
    'text/plain': '.txt',
    'application/pdf': '.pdf',
    'application/msword': '.doc',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document': '.docx',
    'application/vnd.ms-excel': '.xls',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': '.xlsx',
    'application/vnd.ms-powerpoint': '.ppt',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation': '.pptx',
    'text/csv': '.csv',
    'application/octet-stream': '.ftr',
    'application/x-7z-compressed': '.7z',
    'application/gzip': '.gz',
    'audio/mpeg': '.mp3',
    'audio/wav': '.wav',
    'video/x-ms-wmv': '.wmv',
    'video/x-msvideo': '.avi',
    'video/mpeg': '.mpg',
    'video/3gpp': '.3gp',
    'video/quicktime': '.mov',
    'video/mp4': '.mp4',
    'video/x-flv': '.flv',
    'video/x-f4v': '.f4v',
    'application/vnd.rn-realmedia-vbr': '.rmvb',
    'video/x-matroska': '.mkv',
};

export const getInputFileType = (suffix: keyof typeof fileTypes) => {
    return fileTypes[suffix];
};

export const isEnable = (status: number | string | undefined) => {
    if (status === undefined) return false;
    let state;
    if (typeof status === 'number') {
        state = String(status);
    } else {
        state = status;
    }
    return state === StatusEnum.YES;
};

/**
 * 判断当前环境是否为 HTTPS 协议。
 *
 * 该函数考虑了以下几种情况：
 * 1. 检查 `window.location.protocol` 是否为 'https:'
 * 2. 如果运行在本地开发环境（如 localhost 或 127.0.0.1），也可以视为安全环境
 * 3. 考虑浏览器 Web Workers 环境中没有 `window` 对象的情况
 *
 * @returns {boolean} - 如果当前环境为 HTTPS 或本地开发环境返回 true，否则返回 false
 */
export const isHttps = (): boolean => {
    if (typeof window === 'undefined') {
        // 在 Web Workers 或非浏览器环境中，无法访问 window 对象，默认返回 false
        return false;
    }

    // 获取当前页面的协议
    const protocol = window.location.protocol;

    // 判断是否为 HTTPS 协议
    if (protocol === 'https:') {
        return true;
    }

    // 如果是在本地开发环境（localhost 或 127.0.0.1），视为安全环境
    const hostname = window.location.hostname;
    if (hostname === 'localhost' || hostname === '127.0.0.1') {
        return true;
    }

    // 其他情况视为非 HTTPS
    return false;
};

/**
 * 验证任务是否成功
 * @param status 任务状态
 * @returns 是否成功
 */
export const isTaskSuccess = (status: TaskStatusEnum | taskStatusType) => {
    return status === TaskStatusEnum.SUCCESS || String(status) === TaskStatusEnum.SUCCESS;
};

/**
 * 验证任务是否进行中
 * @param status 任务状态
 * @returns 是否进行中
 */
export const isTaskPendding = (status: TaskStatusEnum | taskStatusType) => {
    return status === TaskStatusEnum.PENDDING || String(status) === TaskStatusEnum.PENDDING;
};

/**
 * 验证任务是否失败
 * @param status 任务状态
 * @returns 是否失败
 */
export const isTaskFail = (status: TaskStatusEnum | taskStatusType) => {
    return status === TaskStatusEnum.FAIL || String(status) === TaskStatusEnum.FAIL;
};
