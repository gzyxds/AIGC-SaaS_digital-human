//菜单主题类型
export enum ThemeEnum {
    LIGHT = 'light',
    DARK = 'dark'
}

// 客户端
export enum ClientEnum {
    MP_WEIXIN = 1, // 微信-小程序
    OA_WEIXIN = 2, // 微信-公众号
    H5 = 3, // H5
    IOS = 5, //苹果
    ANDROID = 6 //安卓
}
export const serviceEnum = {
    '1': 'mnp',
    '2': 'oa',
    '3': 'h5'
}

export enum SMSEnum {
    LOGIN = 'YZMDL', // 手机验证码登录
    BIND_MOBILE = 'BDSJHM', // 绑定手机码
    CHANGE_MOBILE = 'BGSJHM', // 变更手机号
    FIND_PASSWORD = 'CSDLMM' // 重设登录密码
}

export enum SearchTypeEnum {
    HISTORY = 'history'
}

// 用户资料
export enum FieldType {
    NONE = '',
    AVATAR = 'avatar',
    USERNAME = 'account',
    NICKNAME = 'nickname',
    SEX = 'sex'
}

// 支付页面
export const PayEnum = {
    '1': 'orderBuy', // 确认下单支付
    '2': 'orderList', // 订单列表支付
    '3': 'orderDetail' // 订单详情支付
}

// 支付结果
export enum PayStatusEnum {
    SUCCESS = 'success',
    FAIL = 'fail',
    PENDING = 'pending'
}

// 支付来源类型
export enum PayFromType {
    ORDER = 'order',
    USERRECHARGE = 'recharge'
}

// 页面状态
export enum PageStatusEnum {
    LOADING = 'loading', // 加载中
    NORMAL = 'normal', // 正常
    ERROR = 'error', // 异常
    EMPTY = 'empty' // 为空
}
