/** 算力套餐列表 */
export const apiGetPowerPlanList = () => {
    return useListRequest<PowerPackage>('/power.powerPackage/lists');
};

/** 算力套餐详情 */
export const apiGetPowerDetail = () => {
    return useGetRequest<VoiceItem>('/power.powerPackage/detail');
};

/** 声音克隆算力配置 */
export const apiGetVoiceClonePowerConfig = () => {
    return useGetRequest<VoiceClonePowerConfig>('/power.powerConfig/getVoiceCloneConfig');
};

/** 声音合成算力配置 */
export const apiGetVoicePowerConfig = () => {
    return useGetRequest<VoiceSynthesisPowerConfig>('/power.powerConfig/getVoiceConfig');
};

/** 数字人克隆算力配置 */
export const apiGetAvatarPowerConfig = () => {
    return useGetRequest<VoiceItem>('/power.powerConfig/getAvatarConfig');
};

/** 数字人克隆算力配置 */
export const apiGetAvatarPowerConfigAll = () => {
    return useGetRequest<AvatarPowerConfigAll[]>('/power.powerConfig/getAllAvatarConfig');
};
