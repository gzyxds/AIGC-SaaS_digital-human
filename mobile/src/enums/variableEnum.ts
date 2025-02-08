export enum VariableEnum {
    /** 用户Token */
    USER_TOKEN = '__user_token__',
    /** 用户临时Token */
    USER_TEMPORARY_TOKEN = '__user_temporary_token__',
    /** 登录时间戳 */
    LOGIN_TIME_STAMP = '__login_time_stamp__',
    /** 主题 */
    THEME = '__theme__',
    /** 是否首次进入数字人页面 */
    CREATE_DIGITAL_FIRST_ENTRY = '__create_digital_first_entry__',
}

/** 响应式宽度值 */
export enum ResponsiveEnum {
    SM = 640,
    MD = 768,
    LG = 1024,
    XL = 1280,
    XXL = 1536,
}

/** 录音器状态 */
export enum RecorderStatusEnum {
    /** 初始状态 */
    INIT = 0,
    /** 录音中 */
    RECORDING = 1,
    /** 暂停 */
    PAUSE = 2,
    /** 结束 */
    END = 3,
}

export enum PayStatusEnum {
    /** 未支付 */
    UNPAID = 0,
    /** 已支付 */
    PAID = 1,
}

export enum UploadFileTypeEnum {
    /** 图片 */
    IMAGE = '.jpg,.png,.gif,.jpeg,.webp,.ico',
    /** 文档 */
    FILE = '.zip,.rar,.txt,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv,.txt,.ftr,.7z,.gz,.mp3,.wav',
    /** 音频 */
    AUDIO = '.mp3,.wav',
    /** 视频 */
    VIDEO = '.wmv,.avi,.mpg,.mpeg,.3gp,.mov,.mp4,.flv,.f4v,.rmvb,.mkv',
}

export enum LoginWayEnum {
    /** 微信登录 */
    WECHAT = '1',
    /** 手机号登录 */
    MOBILE = '2',
    /** 账号登录 */
    ACCOUNT = '3',
}

export enum RegisterWayEnum {
    /** 手机注册 */
    MOBILE = '1',
    /** 邮箱注册 */
    EMAIL = '2',
}

export enum QrLoginStatusEnum {
    /** 微信登录 */
    NOSCAN = 1,
    /** 手机号登录 */
    SCANNED = 2,
    /** 邮箱登录 */
    SUCCESS = 4,
}

export enum StatusEnum {
    YES = '1',
    NO = '0',
}

export enum TaskStatusEnum {
    /** 生成中 */
    PENDDING = '0',
    /** 成功 */
    SUCCESS = '1',
    /** 失败 */
    FAIL = '2',
}

// 协议类型
export enum AgreementTypeEnum {
    /** 隐私政策 */
    PRIVACY = 'privacy',
    /** 服务协议 */
    SERVICE = 'service',
    /** 使用协议 */
    USE = 'use',
    /** 充值协议 */
    RECHARGE = 'recharge',
    /** 免责声明 */
    DISCLAIMER = 'disclaimer',
    /** 货币协议 */
    CURRENCY = 'currency',
}

// 验证码类型
export enum CodeTypeEnum {
    /** 手机验证码登录 */
    LOGIN = 'YZMDL',
    /** 手机验证码注册 */
    MOBILE_REGISTER = 'YZMZC',
    /** 绑定手机码 */
    BIND_MOBILE = 'BDSJHM',
    /** 变更手机号 */
    CHANGE_MOBILE = 'BGSJHM',
    /** 重设登录密码 */
    FIND_PASSWORD = 'ZHDLMM',
}

// 登录sence类型
export enum LoginSenceEnum {
    /** 账号密码-sass系统为3 */
    ACCOUNT = 3,
    /** 手机验证码登录 */
    MOBILE = 2,
}

// 形象tab类型
export enum ProfileTabEnum {
    /** 我的形象 */
    MyProfile = 0,
    /** 我的声音 */
    MySound = 1,
    /** 声音合成 */
    SoundSynthesis = 2,
}
