interface PayWayItem {
    id: number;
    name: string;
    pay_way: number;
    icon: string;
    sort: number;
    remark: string;
    is_default: number;
    scene: number;
    extra: string;
}

interface PowerPackage {
    id: number;
    title: string;
    cost: string;
    power: string;
    original_cost: string;
    recommend: number;
    gift: number;
    gift_power: string;
    sort: number;
    expire_time: null;
    note: string;
    status: number;
    create_time: null;
}

interface RechargeRecordItem {
    order_amount: string;
    create_time: string;
    tips: string;
}

interface RechargeRes {
    order_id: number;
    from: 'recharge' | string;
}

interface PrePayRes {
    config: string;
    pay_way: 1 | 2 | 3;
}

interface PayStatusRes {
    pay_status: number;
    pay_way: number;
    order: {
        order_id: number;
        order_sn: string;
        order_amount: string;
        pay_way: string;
        pay_status: string;
        pay_time: string;
    };
}

interface PayInfoCache {
    pay_way: number | string;
    pay_code: string;
    order_id: number | string;
    amount: number;
    plan_id: number | string;
    create_time: number;
}

interface VoiceClonePowerConfig {
    clone_power: string;
}

interface AvatarPowerConfigAll {
    video_mode_title: string;
    video_mode_status: number;
    video_power: number;
    video_time: number;
    mode: number;
}

interface VoiceSynthesisPowerConfig {
    voice_power: number;
    voice_words: number;
}

interface WXPayJsConfig {
    appId: string;
    nonceStr: string;
    package: string;
    paySign: string;
    signType: string;
    timeStamp: string;
}
