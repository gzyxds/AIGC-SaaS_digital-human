export enum RequestCodeEnum {
    /** 未安装 */
    NOT_INSTALL = -2,
    /** 登陆过期 */
    LOGIN_FAILURE = -1,
    /** 请求错误 */
    FAIL = 0,
    /** 请求成功 */
    SUCCESS = 1,
    /** 打开页面 */
    OPEN_NEW_PAGE = 2,
    /** 没有权限 */
    FORBIDDEN = 3,
    /** 租户不存在 */
    NOT_FOUND = 4,
}

export enum HttpCodeEnum {
    /** 请求成功 */
    OK = 200,
    /** 已创建 */
    CREATED = 201,
    /** 已接受 */
    ACCEPTED = 202,
    /** 无内容 */
    NO_CONTENT = 204,

    /** 重定向 */
    MOVED_PERMANENTLY = 301,
    /** 资源已找到 */
    FOUND = 302,
    /** 未修改 */
    NOT_MODIFIED = 304,

    /** 错误的请求 */
    BAD_REQUEST = 400,
    /** 未授权 */
    UNAUTHORIZED = 401,
    /** 禁止访问 */
    FORBIDDEN = 403,
    /** 资源未找到 */
    NOT_FOUND = 404,
    /** 方法不允许 */
    METHOD_NOT_ALLOWED = 405,
    /** 请求超时 */
    REQUEST_TIMEOUT = 408,
    /** 冲突 */
    CONFLICT = 409,
    /** 请求过多 */
    TOO_MANY_REQUESTS = 429,

    /** 服务器内部错误 */
    INTERNAL_SERVER_ERROR = 500,
    /** 未实现 */
    NOT_IMPLEMENTED = 501,
    /** 错误的网关 */
    BAD_GATEWAY = 502,
    /** 服务不可用 */
    SERVICE_UNAVAILABLE = 503,
    /** 网关超时 */
    GATEWAY_TIMEOUT = 504,
    /** HTTP版本不支持 */
    HTTP_VERSION_NOT_SUPPORTED = 505,
}
