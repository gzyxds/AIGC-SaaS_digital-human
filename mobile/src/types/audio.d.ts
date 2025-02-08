type taskStatusType = '0' | '1' | '2';

interface CreateCloneVoice {
    voice_id?: number | string;
    cover: string;
    title: string;
    content?: string;
    speed?: number;
    remark?: string;
    timbre_name?: string;
    model?: 'V1' | 'V2';
}

interface CreateVoice {
    name: string;
    record?: string;
    file_id: number | string;
    cover: string;
    duration: number;
    expected_content: string;
}

interface CloneVoiceListItem {
    id: number;
    tenant_id: number;
    uid: number;
    task_id: string;
    title: string;
    voice_id: string;
    content: string;
    cost_power: string;
    status: taskStatusType;
    completion_time: string;
    cost_time: string;
    file_id: string;
    duration: string;
    size: string;
    remark: string;
    cover: string;
    create_time: string;
    timbre: { id: number; name: string; voice_url: string; cover: string } | null;
    timbre_name: string;
    voice_url: string;
}

interface SystemVoiceItem {
    id: number;
    name: string;
    voice_name: string;
    type: string;
    scene: string[];
    language: string;
    samplingRate: string;
    timeStamp: string;
    voiceUrl: string;
}

interface VoiceItem {
    id: number;
    tenant_id: number;
    uid: number;
    name: string;
    record: string;
    file_id: string;
    cover: string;
    create_time: string;
    duration: string;
    voice_url: string;
    status: taskStatusType;
}

interface CommonVoiceItem {
    id: number;
    name: string;
    voice_name: string;
    type: string;
    scene: string[];
    language: string;
    samplingRate: string;
    timeStamp: string;
    cover?: string;
}

type VoiceType = 'local' | 'user';

interface VoiceSelectItem {
    type: VoiceType;
    name: string;
    title: string;
    id: string | number;
    cover: string;
}
