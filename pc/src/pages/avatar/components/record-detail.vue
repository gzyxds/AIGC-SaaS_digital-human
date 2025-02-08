<template>
    <ProModal v-model="isOpen" :title="detail?.title || '暂无标题'" width="sm:max-w-4xl">
        <div class="flex items-center gap-2">
            <UBadge
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
            <p class="flex items-center gap-1 text-sm text-foreground/70">
                <UIcon name="tabler:clock-hour-9" />
                <span>{{ detail?.create_time }}</span>
            </p>
        </div>
        <div class="mt-4 flex flex-col gap-4 md:flex-row md:gap-6">
            <div class="flex-1 overflow-hidden rounded-lg bg-background-soft center">
                <video
                    v-if="detail?.resultFile"
                    class="max-h-[50vh]"
                    :src="detail?.resultFile"
                    controls
                />
                <USkeleton v-else class="size-full" />
            </div>
            <div class="flex flex-1 flex-col gap-4">
                <div class="grid grid-cols-2 gap-x-10 gap-y-4">
                    <div class="flex flex-col">
                        <div class="text-sm text-foreground/60">任务ID</div>
                        <div class="break-all">{{ detail?.task_id }}</div>
                    </div>

                    <div class="flex flex-col">
                        <div class="text-sm text-foreground/60">完成时间</div>
                        <div>{{ detail?.completion_time || '--' }}</div>
                    </div>
                    <div class="flex flex-col">
                        <div class="text-sm text-foreground/60">合成通道</div>
                        <div>
                            {{
                                detail?.mode
                                    ? powerConfig.find((item) => item.mode === detail?.mode)
                                          ?.video_mode_title || '--'
                                    : '--'
                            }}
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div class="text-sm text-foreground/60">时长</div>
                        <div>{{ formatSecond(detail?.duration || 0) || 0 }}</div>
                    </div>

                    <div class="flex flex-col">
                        <div class="text-sm text-foreground/60">
                            消耗{{ useAppStore().siteConfig?.unit.power }}
                        </div>
                        <div class="flex items-center gap-1">
                            <span
                                :class="{
                                    'text-foreground/60 line-through': detail?.status === '2',
                                }"
                                >{{ detail?.cost_power }}</span
                            >
                            <span v-if="detail?.status === '2'" class="text-xs text-primary"
                                >已退回</span
                            >
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div class="text-sm text-foreground/60">耗时</div>
                        <div>{{ formatSecond(detail?.cost_time || 0) }}</div>
                    </div>

                    <div class="flex flex-col">
                        <div class="text-sm text-foreground/60">文件大小</div>
                        <div>{{ formatFileSize(detail?.size || 0) }}</div>
                    </div>

                    <div class="flex flex-col">
                        <div class="text-sm text-foreground/60">数字形象</div>
                        <div v-if="detail?.video" class="flex items-center gap-1">
                            <UAvatar
                                size="2xs"
                                :src="detail?.video?.cover || squareDefaultImg"
                                :alt="detail?.video?.name || '--'"
                            />

                            <span class="truncate text-sm">{{ detail?.video?.name }}</span>
                        </div>
                        <div v-else class="flex items-center gap-1">
                            <UAvatar size="2xs" :src="squareDefaultImg" :alt="'--'" />

                            <span class="text-sm text-foreground/50">形象已删除</span>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div class="text-sm text-foreground/60">音频信息</div>
                        <div v-if="detail?.voice" class="flex items-center gap-1">
                            <UAvatar
                                size="2xs"
                                :src="detail?.voice?.cover || squareDefaultImg"
                                :alt="detail?.voice.title || '--'"
                            />

                            <span class="truncate text-sm">{{ detail?.voice.title }}</span>
                        </div>
                        <div v-else class="flex items-center gap-1">
                            <UAvatar size="2xs" :src="squareDefaultImg" :alt="'--'" />

                            <span class="text-sm text-foreground/50">音频已删除</span>
                        </div>
                    </div>

                    <div v-if="!detail?.voice?.task_id" class="flex flex-col">
                        <div class="text-sm text-foreground/60">音频来源</div>
                        <div>
                            <UBadge variant="soft">本地上传</UBadge>
                        </div>
                    </div>

                    <div class="col-span-2 flex flex-col">
                        <div class="text-sm text-foreground/60">音频内容</div>
                        <div v-if="detail?.voice" class="max-h-52 overflow-y-auto">
                            {{ detail?.voice.content || '--' }}
                        </div>
                        <div v-else class="text-sm text-foreground/50">音频已删除</div>
                    </div>
                </div>

                <div v-if="detail?.resultFile" class="mt-auto flex items-center gap-4 end">
                    <UButton
                        variant="outline"
                        icon="tabler:copy"
                        @click="useCopy(detail?.resultFile)"
                        >复制链接</UButton
                    >
                    <UButton
                        icon="tabler:cloud-download"
                        @click="downloadFile({ src: detail?.resultFile })"
                        >下载结果视频</UButton
                    >
                </div>
            </div>
        </div>
    </ProModal>
</template>

<script lang="ts" setup>
import type colors from '#ui-colors';
import { apiGetAvatarPowerConfigAll } from '~/api/power';
import squareDefaultImg from '~/assets/images/1_1_default.png';

type BadgeColor = (typeof colors)[number];

const isOpen = ref<boolean>(false);
const powerConfig = ref<AvatarPowerConfigAll[]>([]);

const detail = ref<AiAvatarItem>();

const open = async (info: AiAvatarItem) => {
    isOpen.value = true;
    detail.value = info;
    // const config = powerConfig.value.find((item) => item.mode === formData.mode);
    powerConfig.value = await apiGetAvatarPowerConfigAll();
};

defineExpose({ open });
</script>
