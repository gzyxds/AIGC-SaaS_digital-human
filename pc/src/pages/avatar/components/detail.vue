<template>
    <div class="flex flex-col gap-4 md:flex-row">
        <div class="flex-1 bg-background-soft center">
            <video :src="data.fileUrl" controls class="max-h-[40vh] rounded-md sm:max-h-[50vh]" />
        </div>

        <div class="flex flex-1 flex-col gap-6">
            <div class="flex flex-col">
                <div class="grid grid-cols-2 gap-x-10 gap-y-4">
                    <div class="flex flex-col">
                        <div class="text-sm text-foreground/60">状态</div>
                        <div>
                            <UBadge
                                :color="
                                    { '0': 'gray', '1': 'primary', '2': 'red' }[
                                        data?.status as string
                                    ] as BadgeColor
                                "
                            >
                                {{
                                    {
                                        '0': '合成中',
                                        '1': '已完成',
                                        '2': '合成失败',
                                    }[data?.status as string]
                                }}
                            </UBadge>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div class="text-sm text-foreground/60">时长</div>
                        <div>{{ formatSecond(data?.duration || 0) || 0 }}</div>
                    </div>
                    <div class="flex flex-col">
                        <div class="text-sm text-foreground/60">模型id</div>
                        <div>{{ data?.image_id }}</div>
                    </div>
                </div>
            </div>
            <div
                v-if="data.fileUrl || isTaskSuccess(data.status)"
                class="gap-2 end md:mt-auto md:gap-4"
            >
                <UButton
                    v-if="data.fileUrl"
                    :ui="{ rounded: 'rounded-full' }"
                    size="md"
                    color="white"
                    icon="tabler:copy"
                    @click="useCopy(data.fileUrl)"
                >
                    复制链接
                </UButton>
                <UButton
                    v-if="data.fileUrl"
                    :ui="{ rounded: 'rounded-full' }"
                    size="md"
                    color="white"
                    icon="tabler:cloud-download"
                    @click="downloadFile({ src: data.fileUrl })"
                >
                    立即下载
                </UButton>
                <UButton
                    v-if="isTaskSuccess(data.status)"
                    icon="tabler:wand"
                    size="md"
                    :ui="{ rounded: 'rounded-full' }"
                    @click="emit('stepChange', 1)"
                >
                    立即使用
                </UButton>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type colors from '#ui-colors';

type BadgeColor = (typeof colors)[number];
withDefaults(
    defineProps<{
        data: AvatarVideoDetail;
    }>(),
    {}
);

const emit = defineEmits<{
    stepChange: [value?: number];
}>();
</script>
