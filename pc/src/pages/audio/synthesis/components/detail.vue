<template>
    <ProModal v-model="isOpen" :title="detail?.title" width="sm:max-w-xl">
        <div class="order-1 flex items-center gap-2">
            <p class="gap-1 text-xs text-foreground/60 start">
                <UIcon name="tabler:clock" />
                <span>{{ detail?.create_time }}</span>
            </p>
        </div>

        <div class="order-2 mt-2 flex-1">
            <div class="grid grid-cols-2 gap-x-10 gap-y-4">
                <div class="flex flex-col">
                    <div class="text-sm text-foreground/60">
                        {{ detail?.task_id ? '合成状态' : '来源' }}
                    </div>
                    <div>
                        <UBadge
                            v-if="detail?.task_id"
                            :color="
                                { '0': 'gray', '1': 'primary', '2': 'red' }[
                                    detail?.status as string
                                ] as BadgeColor
                            "
                        >
                            {{
                                {
                                    '0': '合成中',
                                    '1': '已完成',
                                    '2': '合成失败',
                                }[detail?.status as string]
                            }}
                        </UBadge>
                        <UBadge v-else variant="soft"> 来自本地上传 </UBadge>
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="text-sm text-foreground/60">任务号</div>
                    <div>{{ detail?.task_id || '--' }}</div>
                </div>

                <div class="flex flex-col">
                    <div class="text-sm text-foreground/60">
                        消耗{{ useAppStore().siteConfig?.unit.power }}
                    </div>
                    <div class="flex items-center gap-1">
                        <span
                            :class="{
                                'text-foreground/60 line-through': isTaskFail(detail?.status),
                            }"
                            >{{ detail?.cost_power || 0 }}</span
                        >
                        <span v-if="isTaskFail(detail?.status)" class="text-xs text-primary"
                            >已退回</span
                        >
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="text-sm text-foreground/60">消耗时间</div>
                    <div>{{ formatSecond(detail?.cost_time || 0) || '--' }}</div>
                </div>
                <div class="flex flex-col">
                    <div class="text-sm text-foreground/60">时长</div>
                    <div>{{ formatSecond(detail?.duration || 0) || 0 }}</div>
                </div>
                <div class="flex flex-col">
                    <div class="text-sm text-foreground/60">大小</div>
                    <div>{{ formatFileSize(Number(detail?.size)) }}</div>
                </div>
                <div class="flex flex-col">
                    <div class="text-sm text-foreground/60">音色信息</div>
                    <div>
                        <div
                            class="mt-2 flex w-fit max-w-fit items-center gap-2 rounded-full bg-background-soft p-0.5 pr-2 text-xs text-foreground/70"
                        >
                            <UAvatar
                                :src="detail.timbre?.cover || squareDefaultImg"
                                icon="tabler:volume"
                                size="xs"
                            />
                            <span v-if="detail?.timbre && detail.timbre.name" class="truncate">
                                {{ detail.timbre.name }}
                            </span>
                            <span v-else class="truncate">
                                {{ getCommonTimbre(detail?.timbre_name)?.name || '系统音色' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col">
                    <div class="text-sm text-foreground/60">备注</div>
                    <div>{{ detail?.remark || '--' }}</div>
                </div>
                <div class="col-span-2 flex flex-col">
                    <div class="text-sm text-foreground/60">合成内容</div>
                    <div class="max-h-52 overflow-y-auto">{{ detail?.content || '--' }}</div>
                </div>
            </div>
        </div>
        <div v-if="detail?.voice_url" class="order-1 mt-4 w-full sm:order-last">
            <div
                class="flex w-full flex-col items-center gap-4 rounded-lg bg-background-soft p-4 sm:flex-row"
            >
                <img
                    class="max-h-16 rounded-full sm:max-h-20 sm:rounded-md"
                    :src="detail?.cover || squareDefaultImg"
                    alt=""
                />
                <AudioPlayer size="sm" :src="detail?.voice_url" />
            </div>
        </div>
    </ProModal>
</template>

<script lang="ts" setup>
import type colors from '#ui-colors';
import { apiGetCloneVoiceDetail } from '~/api/audio';
import commonVoices from '~/assets/commonVoices.json';
import squareDefaultImg from '~/assets/images/1_1_default.png';

type BadgeColor = (typeof colors)[number];

const isOpen = ref<boolean>(false);
const detail = ref<CloneVoiceListItem>();

const getCommonTimbre = (timbreName: string | undefined) => {
    return commonVoices.find((item) => item.voice_name === timbreName);
};
const show = (data: CloneVoiceListItem) => {
    isOpen.value = true;
    detail.value = data;
    apiGetCloneVoiceDetail({ id: data.id });
};

defineExpose({ show: show });
</script>

<style lang="scss" scoped></style>
