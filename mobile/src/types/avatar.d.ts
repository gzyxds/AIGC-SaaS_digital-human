type taskStatusType = '0' | '1' | '2';

interface AiAvatarItem {
    id: number;
    tenant_id: number;
    uid: number;
    title: string;
    task_id: string;
    voice_id: string;
    video_id: string;
    cost_power: string;
    status: taskStatusType;
    completion_time: string;
    cost_time: string;
    file_id: string;
    duration: string;
    size: string;
    cover: string;
    create_time: string;
    user: {
        id: number;
        tenant_id: number;
        sn: number;
        avatar: string;
        real_name: string;
        nickname: string;
        account: string;
        password: string;
        mobile: string;
        sex: string;
        channel: number;
        is_disable: number;
        login_ip: string;
        login_time: string;
        is_new_user: number;
        user_money: string;
        total_recharge_amount: string;
        create_time: string;
        update_time: string;
        delete_time: null;
    };
    voice: {
        id: number;
        tenant_id: number;
        uid: number;
        task_id: string;
        title: string;
        cover: string;
        voice_id: string;
        content: string;
        cost_power: string;
        status: string;
        completion_time: string;
        cost_time: string;
        file_id: string;
        duration: string;
        size: string;
        remark: string;
        create_time: string;
        update_time: string;
        delete_time: null;
        timbre_name: string;
    };
    video: {
        id: number;
        tenant_id: number;
        uid: number;
        name: string;
        cover: string;
        record: string;
        file_id: string;
        create_time: string;
        update_time: string;
        delete_time: null;
        duration: string;
    };
    resultFile: string;
}

interface AvatarVideoItem {
    id: number;
    tenant_id: number;
    uid: number;
    name: string;
    record: string;
    file_id: string;
    cover: string;
    create_time: string;
    duration: string;
    video_url: string;
}

interface AvatarVideoDetail {
    id: number;
    tenant_id: number;
    uid: number;
    name: string;
    cover: string;
    record: string;
    file_id: string;
    create_time: string;
    update_time: string;
    delete_time: null;
    duration: string;
    fileUrl: string;
    userName: string;
    userAvatar: string;
}

interface CreateAiAvatarRecord {
    voice_id: number | string;
    video_id: number | string;
    title?: string;
    cover?: string;
    remark?: string;
    /** 模式 1-极速 2-高清 */
    mode: number;
}

interface CreateAvatarVideo {
    name: string;
    file_id: number | string;
    duration: number;
    record?: string;
    cover?: string;
}

// 创建数字人-全流程
interface CreateCompleteFlow {
    /*  声音模型-现在默认为 1 */
    voice_mode: number;
    /*  声音id */
    voice_id: number | string;
    /** 声音合成内容 */
    content: string;
    /** 音色名称-可选 */
    timbre?: string;
    /** 形象 id */
    video_id: number | string;
    /** 合成模式 */
    video_mode: number | null;
    /** 数字人名称 */
    video_name: string;
}
