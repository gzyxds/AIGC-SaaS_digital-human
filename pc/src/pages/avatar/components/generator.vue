<template>
    <div
        class="flex max-h-[calc(100vh-8rem-28px)] flex-col gap-4 sm:flex-row md:max-h-[calc(80vh-8rem-28px)]"
    >
        <div class="flex-1 rounded-xl bg-background-soft center">
            <video :src="data.fileUrl" controls class="max-h-52 rounded-md md:max-h-full"></video>
        </div>

        <div class="flex flex-1 flex-col gap-4 overflow-y-auto">
            <div class="flex flex-1 flex-col overflow-y-auto">
                <h1 class="flex items-end justify-between text-lg font-medium">
                    <span> 点击选择配音 </span>
                    <span class="text-xs text-foreground/50">
                        已选：{{ selectedVoice?.title || '未选择' }}
                    </span>
                </h1>
                <div class="my-2 flex flex-1 flex-col overflow-y-auto">
                    <ProList
                        ref="listRef"
                        v-slot="{ item }: { item: CloneVoiceListItem }"
                        :request-instance="() => apiGetCloneVoiceList({ status: '1' })"
                        :scroll="true"
                        load-mode="auto"
                        mode="load"
                        class="flex flex-col gap-2"
                    >
                        <div
                            class="group relative flex-1 cursor-pointer rounded-lg border border-transparent bg-background-soft shadow-none md:rounded-xl lg:block"
                            :class="{
                                '!border-primary bg-primary/20': item.id === selectedVoice?.id,
                            }"
                        >
                            <div class="relative flex gap-4 pr-0">
                                <div
                                    class="audio-container relative m-4 mr-0 h-14 w-14 flex-shrink-0"
                                >
                                    <img
                                        :src="item.timbre?.cover || squareDefaultImg"
                                        class="relative size-full rounded-full"
                                    />
                                    <div
                                        class="absolute left-0 top-0 flex size-full cursor-pointer items-center justify-center overflow-hidden rounded-full px-4 transition-opacity group-hover:opacity-100 group-active:opacity-100 md:bg-background/20 md:opacity-0 md:backdrop-blur"
                                        @click="
                                            AudioModalRef?.open({
                                                cover: item.cover,
                                                url: item.voice_url,
                                            })
                                        "
                                    >
                                        <UIcon
                                            name="tabler:player-play-filled"
                                            size="20"
                                            class="text-white"
                                        />
                                    </div>
                                </div>
                                <div class="flex-1 p-4 pl-0" @click="selectHandle(item)">
                                    <h2 class="font-medium">{{ item.title || '--' }}</h2>
                                    <p class="mt-1 line-clamp-2 text-xs text-foreground/60">
                                        {{ item.content || '--' }}
                                    </p>
                                    <p class="mt-1">
                                        <span
                                            class="inline-flex items-center gap-0.5 rounded bg-foreground/5 px-1 py-0.5 text-xs text-foreground/60"
                                        >
                                            <UIcon name="tabler:clock" />
                                            {{ formatSecond(item.duration || 0) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </ProList>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <div class="flex flex-col justify-end gap-1 px-1">
                    <label class="text-sm text-foreground/70">通道选择：</label>
                    <UInputMenu
                        v-model="formData.mode"
                        :ui-menu="{
                            width: 'max-w-[70%]',
                        }"
                        :options="powerConfig"
                        value-attribute="mode"
                        option-attribute="video_mode_title"
                        @change="calculateCost"
                    />
                </div>
                <div class="flex items-center justify-between gap-4">
                    <UButton
                        :ui="{ rounded: 'rounded-full' }"
                        size="md"
                        color="white"
                        icon="tabler:chevron-left"
                        @click="emit('stepChange', 0)"
                    >
                        返回
                    </UButton>
                    <UButton
                        :disabled="!formData.voice_id || !isConfirmAgreement"
                        icon="tabler:wand"
                        size="md"
                        :ui="{ rounded: 'rounded-full' }"
                        :loading="btnStatus"
                        @click="onSubmit"
                    >
                        开始合成
                        <span class="center">
                            消耗{{ costPower }}<UIcon name="heroicons:bolt-solid" />
                        </span>
                    </UButton>
                </div>
                <p class="end">
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
                </p>
            </div>
        </div>
        <AudioModal ref="AudioModalRef" />
    </div>
</template>

<script lang="ts" setup>
import { format } from 'date-fns';

import { apiGetCloneVoiceList } from '~/api/audio';
import { apiPostAiAvatarCreate } from '~/api/avatar';
import { apiGetAvatarPowerConfigAll } from '~/api/power';
import squareDefaultImg from '~/assets/images/1_1_default.png';
import AudioModal from '~/components/audio-modal.vue';

const props = withDefaults(
    defineProps<{
        data: AvatarVideoDetail;
    }>(),
    {}
);

const emit = defineEmits<{
    stepChange: [value: number];
    submit: [];
}>();

const AudioModalRef = getComponentExpose(AudioModal);

const formData = reactive<CreateAiAvatarRecord>({
    title: props.data.name,
    cover: props.data.cover,
    video_id: props.data.id,
    voice_id: '',
    mode: 1,
});

const selectedVoice = ref<CloneVoiceListItem | null>();
const isConfirmAgreement = ref<boolean>(false);
const powerConfig = ref<AvatarPowerConfigAll[]>([]);
const costPower = ref<number>(0);
const btnStatus = ref<boolean>(false);

const calculateCost = (): void => {
    const config = powerConfig.value.find((item) => item.mode === formData.mode);
    if (config) {
        const minutes = Math.ceil(Number(selectedVoice.value?.duration || 0) / 60);

        costPower.value = config.video_power * minutes;
    }
};

const selectHandle = (item: CloneVoiceListItem) => {
    if (selectedVoice.value?.id === item.id) {
        formData.voice_id = '';
        selectedVoice.value = null;
        costPower.value = 0;
    } else {
        selectedVoice.value = item;
        formData.voice_id = item.file_id;
        calculateCost();
    }
};

const onSubmit = async () => {
    btnStatus.value = true;
    formData.title = `${props.data.name}-${format(new Date(), 'yyyy-MM-dd HH:mm')}`;
    try {
        useRefreshUser(async () => {
            await apiPostAiAvatarCreate(formData);
            emit('submit');
            btnStatus.value = false;
        });
    } catch (error) {
        btnStatus.value = false;
    }
};

onMounted(async () => {
    powerConfig.value = await apiGetAvatarPowerConfigAll();
    if (powerConfig.value[0].mode) {
        formData.mode = powerConfig.value[0].mode;
    }
});
</script>
