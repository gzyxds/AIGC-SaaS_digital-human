<template>
    <UModal
        v-model="isOpen"
        :ui="{ width: 'sm:max-w-3xl', container: 'items-center', base: 'overflow-hidden' }"
        @close="emit('close')"
    >
        <div class="p-4 md:p-6">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-xl font-medium">批量合成</h1>
                <UButton
                    icon="tabler:x"
                    :ui="{
                        icon: { size: { sm: 'h-5 w-5' } },
                        square: { sm: 'p-1' },
                    }"
                    size="sm"
                    variant="ghost"
                    @click="closeModal()"
                />
            </div>

            <div
                v-load="isPendding"
                class="flex max-h-[calc(90vh-10rem)] flex-col gap-4 md:max-h-[calc(80vh-8rem-28px)] md:flex-row"
            >
                <div
                    class="flex min-h-60 flex-col gap-2 overflow-y-auto md:max-h-full md:flex-1 md:pr-2"
                >
                    <div
                        v-for="(item, index) in videoList"
                        :id="`video-item-${index}`"
                        :key="item.id"
                        class="rounded-lg bg-background-soft transition-colors hover:bg-foreground/5 md:rounded-xl"
                        @click="selectVideoHandle(item, index, $event)"
                    >
                        <div class="group relative cursor-pointer">
                            <div
                                class="absolute inset-0 z-50 size-full cursor-pointer rounded-lg border-2 border-transparent p-2 transition-[border] md:rounded-xl"
                                :class="{
                                    '!border-primary bg-foreground/20':
                                        videoList[activeIndex].id === item.id,
                                }"
                            >
                                <UIcon
                                    v-if="videoList[activeIndex].id === item.id"
                                    name="tabler:square-rounded-check-filled"
                                    class="text-primary"
                                    size="28"
                                />
                            </div>
                            <AspectRatio class="rounded-lg md:rounded-xl" :src="item.cover" />
                            <div
                                class="absolute inset-0 z-10 flex size-full flex-col justify-end overflow-hidden rounded-lg md:rounded-xl"
                            >
                                <div
                                    class="flex justify-between overflow-hidden rounded-b-lg bg-foreground/30 p-2 text-background backdrop-blur md:rounded-b-xl"
                                >
                                    <h1 class="text-sm">{{ item.name || '暂无标题' }}</h1>
                                    <span
                                        style="text-shadow: 0 0 1px rgba(0, 0, 0, 0.1)"
                                        class="rounded bg-white/30 p-[1px_4px] text-[10px] backdrop-blur center"
                                    >
                                        {{ formatSecond(item.duration) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-1 flex-col overflow-y-auto">
                    <LoadList
                        class="flex h-full flex-1 flex-col gap-2 overflow-y-auto"
                        :state="state"
                        :load-more="() => getList({ mode: 'load' })"
                    >
                        <div
                            v-for="item in voiceList"
                            :key="item.id"
                            class="flex cursor-pointer rounded-lg border border-transparent bg-background-soft transition-[border]"
                            :class="{
                                '!border-primary bg-primary/20':
                                    item.id === batchFormData[activeIndex]?.voice?.id,
                            }"
                        >
                            <div
                                class="flex flex-1 items-center gap-2 p-2"
                                @click="selectVoiceHandle(item)"
                            >
                                <img
                                    :src="item.cover || defaultImg"
                                    class="relative h-14 w-14 rounded-xl"
                                />
                                <div class="flex flex-col gap-2">
                                    <h2 class="font-medium">{{ item.title || '--' }}</h2>
                                    <p class="line-clamp-2 text-sm text-foreground/60">
                                        {{ item.content }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="ml-auto flex flex-col items-center justify-center p-2"
                                @click="
                                    AudioModalRef?.open({ cover: item.cover, url: item.voice_url })
                                "
                            >
                                <span>{{ formatSecond(item.duration) }}</span>
                                <UIcon name="tabler:player-play-filled" />
                            </div>
                        </div>
                    </LoadList>
                    <p class="px-4 py-2 text-center text-xs text-foreground/60 md:text-end">
                        Tips：选择音频之后会自动跳到下一个视频
                    </p>
                </div>
            </div>

            <div class="flex flex-col items-center justify-between gap-4 pt-4">
                <div class="flex w-full flex-col items-center justify-between gap-4 md:flex-row">
                    <UInputMenu
                        v-model="mode"
                        :options="powerConfig"
                        placeholder="通道选择"
                        value-attribute="mode"
                        option-attribute="video_mode_title"
                        @change="calculateCost"
                    />
                    <div class="flex items-center gap-4">
                        <UButton
                            :ui="{ rounded: 'rounded-full', padding: { md: 'px-6' } }"
                            size="md"
                            color="white"
                            :disabled="isPendding"
                            @click="closeModal"
                            >取消合成</UButton
                        >
                        <UButton
                            :ui="{ rounded: 'rounded-full', padding: { md: 'px-6' } }"
                            size="md"
                            :disabled="!isConfirmAgreement"
                            :loading="isPendding"
                            @click="onSubmit"
                        >
                            <span>开始合成</span>
                            <span class="center">
                                消耗{{ costPower }}<UIcon name="heroicons:bolt-solid" />
                            </span>
                        </UButton>
                    </div>
                </div>
                <div class="w-full center md:end">
                    <UCheckbox
                        v-model="isConfirmAgreement"
                        name="notifications"
                        label="Notifications"
                        :ui="{ inner: 'ms-2', base: 'size-3.5', wrapper: 'items-center' }"
                    >
                        <template #label>
                            <p class="text-xs text-foreground/50">
                                <span>请先阅读并同意</span>
                                <NuxtLink
                                    target="_blank"
                                    class="mx-[1px] font-medium text-primary"
                                    to="/agreement?type=agreement&item=use"
                                >
                                    《使用协议》
                                </NuxtLink>
                            </p>
                        </template>
                    </UCheckbox>
                </div>
            </div>
        </div>
        <AudioModal ref="AudioModalRef" />
    </UModal>
</template>

<script lang="ts" setup>
import { format } from 'date-fns';

import { apiGetCloneVoiceList } from '~/api/audio';
import { apiPostAiAvatarCreate } from '~/api/avatar';
import { apiGetAvatarPowerConfigAll } from '~/api/power';
import defaultImg from '~/assets/images/16_9_default.png';
import AudioModal from '~/components/audio-modal.vue';
import LoadList from '~/components/load-list.vue';

const isOpen = ref<boolean>(false);
const isPendding = ref<boolean>(false);
const videoList = ref<AvatarVideoItem[]>([]);
const AudioModalRef = getComponentExpose(AudioModal);
const mode = ref<number>(1);

const activeIndex = ref<number>(0);
const isConfirmAgreement = ref<boolean>(false);
const powerConfig = ref<AvatarPowerConfigAll[]>([]);
const costPower = ref<number>(0);

const emit = defineEmits<{
    stepChange: [value: number];
    close: [];
}>();

const batchFormData = ref<{ video: AvatarVideoItem | null; voice: CloneVoiceListItem | null }[]>(
    []
);
const calculateCost = (): void => {
    const config = powerConfig.value.find((item) => item.mode === mode.value);
    if (config) {
        let countPower = 0;
        batchFormData.value.forEach((item) => {
            const minutes = Math.ceil(Number(item.voice?.duration || 0) / 60);

            countPower += config.video_power * minutes;
        });

        costPower.value = countPower;
    }
};
const selectVideoHandle = (item: AvatarVideoItem, index: number, e: MouseEvent) => {
    activeIndex.value = index;
    batchFormData.value[activeIndex.value].video = item;

    const targetElement = e.currentTarget as HTMLElement;
    targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
};
const selectVoiceHandle = (item: CloneVoiceListItem) => {
    batchFormData.value[activeIndex.value].voice = item;
    if (activeIndex.value < videoList.value.length - 1) {
        setTimeout(() => {
            activeIndex.value += 1;
            document
                .getElementById(`video-item-${activeIndex.value}`)
                ?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 300);
    }
    calculateCost();
};

const { getList, refresh, state, list: voiceList } = apiGetCloneVoiceList();

const open = async (data: AvatarVideoItem[]) => {
    isOpen.value = true;
    powerConfig.value = await apiGetAvatarPowerConfigAll();

    mode.value = powerConfig.value[0].mode;
    activeIndex.value = 0;
    isConfirmAgreement.value = false;
    costPower.value = 0;
    refresh({ mode: 'load' });
    videoList.value = data;
    batchFormData.value = Array.from({ length: data.length }, () => ({
        video: null,
        voice: null,
    }));

    batchFormData.value.forEach((item, index) => {
        item.video = data[index];
    });
};

const onSubmit = async () => {
    const missingVoiceIndex = batchFormData.value.findIndex((item) => !item.voice);

    if (missingVoiceIndex !== -1) {
        useMessage().error(`第 ${missingVoiceIndex + 1} 个视频未配置声音`);
        return;
    }

    isPendding.value = true;

    try {
        for (const [index, item] of batchFormData.value.entries()) {
            const formData: CreateAiAvatarRecord = {
                cover: item.video?.cover,
                title: `${item.video?.name || item.video?.id}-${item.voice?.title || item.voice?.id}-${format(new Date(), 'yyyy-MM-dd HH:mm')}`,
                video_id: item.video?.id as number,
                voice_id: item.voice?.file_id as string,
                mode: mode.value,
            };

            await apiPostAiAvatarCreate(formData);
        }

        await useRefreshUser(async () => {
            isPendding.value = false;
            closeModal();
            navigateTo('/avatar/works');
        });
    } catch (error) {
        useRefreshUser(async () => {
            useMessage().error('部分合成失败，请前往【我的作品】查看合成结果');
            isPendding.value = false;
        });
    }
};

const closeModal = () => {
    activeIndex.value = 0;
    videoList.value = [];
    voiceList.value = [];
    isOpen.value = false;
    emit('close');
};

defineExpose({ open: open });
</script>

<style lang="scss" scoped></style>
