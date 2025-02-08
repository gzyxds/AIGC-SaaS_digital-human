// wx.d.ts
declare global {
    interface Window {
        wx: WxSdk; // 在window对象下添加wx类型
    }
}

interface WxSdk {
    config: (config: WxConfig) => void;
    ready: (callback: () => void) => void;
    error: (callback: (err: WxError) => void) => void;
    getLocalImgData: (e: any) => void;
    getRecorderManager: () => void;
    createInnerAudioContext: () => void;
    updateAppMessageShareData?: (data: WxShareData, callback?: () => void) => void;
    updateTimelineShareData?: (data: WxShareData, callback?: () => void) => void;
    chooseImage?: (config: WxChooseImageConfig) => void;
    // 更多接口可以根据实际需求添加
}

interface WxConfig {
    debug?: boolean;
    appId: string;
    timestamp: number;
    nonceStr: string;
    signature: string;
    jsApiList: string[];
}

interface WxError {
    errMsg: string;
}

interface WxShareData {
    title: string;
    desc?: string;
    link: string;
    imgUrl: string;
}

interface WxChooseImageConfig {
    count?: number;
    sizeType?: string[];
    sourceType?: string[];
    success: (res: { localIds: string[] }) => void;
    fail?: (err: WxError) => void;
    complete?: () => void;
}

// 将模块导出为空，使其可以作为模块引入
export {};
