<template>
    <ProModal v-model="isOpen" title="选择音色" width="sm:max-w-5xl">
        <div class="flex h-[80vh] flex-col">
            <div class="flex items-center justify-between">
                <div class="mb-2 flex flex-1 md:mb-4">
                    <UTabs
                        class="w-fit"
                        :ui="{
                            container: 'hidden',
                            list: {
                                tab: {
                                    padding: 'px-4 sm:px-6',
                                    active: 'text-primary dark:text-white',
                                },
                            },
                        }"
                        :items="[
                            {
                                label: '我的音色',
                            },

                            {
                                label: '系统音色',
                            },
                        ]"
                        @change="onTabsChange"
                    />
                </div>
                <PageSearch
                    v-model="keyword"
                    :fixed="false"
                    placeholder="搜索音色"
                    @search="searchHandle"
                />
            </div>
            <div class="flex-1 overflow-hidden">
                <Transition name="component" mode="out-in">
                    <div v-if="tabIndex === 0 && isOpen" class="flex h-full flex-col">
                        <ProList
                            ref="listRef"
                            v-slot="{ item }: { item: VoiceItem }"
                            :request-instance="
                                () => apiGetVoiceList({ status: TaskStatusEnum.SUCCESS })
                            "
                            :scroll="true"
                            class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                        >
                            <div
                                class="group relative flex cursor-pointer gap-4 rounded-lg bg-background-soft p-4"
                            >
                                <div
                                    class="absolute bottom-0 left-0 z-10 h-0 w-full cursor-pointer overflow-hidden rounded-lg bg-foreground/20 backdrop-blur-lg transition-[height] group-hover:h-10"
                                >
                                    <div class="flex w-full items-center justify-end px-2 py-2">
                                        <UButtonGroup
                                            size="2xs"
                                            orientation="horizontal"
                                            :ui="{ rounded: 'rounded-full' }"
                                        >
                                            <UButton
                                                icon="heroicons:play"
                                                size="2xs"
                                                @click="
                                                    AudioModalRef?.open({
                                                        cover: squareDefaultImg,
                                                        url: item.voice_url,
                                                    })
                                                "
                                            >
                                                试听
                                            </UButton>
                                            <UButton
                                                size="2xs"
                                                trailing-icon="tabler:chevron-right"
                                                @click="selectHandle('user', item)"
                                            >
                                                使用
                                            </UButton>
                                        </UButtonGroup>
                                    </div>
                                </div>
                                <div class="audio-container relative h-14 w-14 flex-shrink-0">
                                    <img
                                        :src="item.cover || squareDefaultImg"
                                        class="relative size-full rounded-full"
                                    />
                                </div>
                                <div class="">
                                    <h2 class="font-medium">{{ item.name || '--' }}</h2>
                                    <p class="mt-1 line-clamp-2 text-sm text-foreground/60">
                                        {{ item.record || '--' }}
                                    </p>

                                    <p class="mt-1 line-clamp-2 text-xs text-foreground/60">
                                        {{ item.create_time || '--' }}
                                    </p>
                                </div>
                            </div>
                        </ProList>
                    </div>
                    <ScrollArea v-else>
                        <div
                            class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3"
                        >
                            <div
                                v-for="item in systemVoices"
                                :key="item.id"
                                class="group relative flex cursor-pointer flex-col gap-4 rounded-lg bg-background-soft p-4"
                            >
                                <div
                                    class="absolute bottom-0 left-0 z-10 h-0 w-full cursor-pointer overflow-hidden rounded-lg bg-foreground/20 backdrop-blur-lg transition-[height] group-hover:h-10"
                                >
                                    <div class="flex w-full items-center justify-end px-2 py-2">
                                        <UButtonGroup
                                            size="2xs"
                                            orientation="horizontal"
                                            :ui="{ rounded: 'rounded-full' }"
                                        >
                                            <UButton
                                                icon="heroicons:play"
                                                size="2xs"
                                                @click="
                                                    AudioModalRef?.open({
                                                        cover: squareDefaultImg,
                                                        url: item.voiceUrl,
                                                    })
                                                "
                                            >
                                                试听
                                            </UButton>
                                            <UButton
                                                size="2xs"
                                                trailing-icon="tabler:chevron-right"
                                                @click="selectHandle('local', item)"
                                            >
                                                使用
                                            </UButton>
                                        </UButtonGroup>
                                    </div>
                                </div>

                                <div
                                    class="audio-container relative flex flex-shrink-0 items-center gap-4"
                                >
                                    <img
                                        :src="squareDefaultImg"
                                        class="relative h-12 w-12 rounded-full"
                                    />
                                    <h2 class="flex flex-col">
                                        <span class="font-medium">{{ item.name || '--' }}</span>
                                        <span class="text-xs text-foreground/70">
                                            {{ item.type }}
                                        </span>
                                    </h2>
                                </div>
                                <div class="flex flex-1 flex-col gap-1">
                                    <p class="line-clamp-2 text-sm text-foreground/60">
                                        语言：{{ item.language }}
                                    </p>

                                    <p class="mt-1 flex flex-wrap gap-1 text-sm text-foreground/60">
                                        <UBadge
                                            v-for="scene in item.scene"
                                            :key="scene"
                                            size="xs"
                                            variant="soft"
                                        >
                                            {{ scene }}
                                        </UBadge>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </ScrollArea>
                </Transition>
            </div>
        </div>
        <AudioModal ref="AudioModalRef" />
    </ProModal>
</template>

<script lang="ts" setup>
import { apiGetSystemVoiceList, apiGetVoiceList } from '~/api/audio';
import squareDefaultImg from '~/assets/images/1_1_default.png';
import { TaskStatusEnum } from '~/enums/variableEnum';

import AudioModal from './audio-modal.vue';
import ProList from './pro-list.vue';

const AudioModalRef = getComponentExpose(AudioModal);
const listRef = getComponentExpose(ProList);
const isOpen = ref<boolean>(false);
const tabIndex = ref<number>(0);
const keyword = ref<string>('');
const systemVoices = ref<SystemVoiceItem[]>([]);
const systemVoicesList = ref<SystemVoiceItem[]>([]);

const emit = defineEmits<{
    select: [value: VoiceSelectItem];
}>();

const open = async () => {
    tabIndex.value = 0;
    await nextTick();
    isOpen.value = true;
    systemVoices.value = await apiGetSystemVoiceList();
};

const searchHandle = (e: string) => {
    if (tabIndex.value === 0) {
        listRef.value?.getList({ newParams: { keyword: e } });
    } else {
        const keyword = e.toLowerCase();
        systemVoicesList.value = systemVoices.value.filter((item) =>
            item.name.toLowerCase().includes(keyword)
        );
    }
};

const onTabsChange = (e: number) => {
    tabIndex.value = e;
    keyword.value = '';
    searchHandle('');
};

const selectHandle = (type: VoiceType, item: VoiceItem | CommonVoiceItem) => {
    const newItem: VoiceSelectItem = {
        type,
        title: item.name,
        name: '',
        id: '',
        cover: item.cover || '',
    };
    if (type === 'local') {
        newItem.name = (item as CommonVoiceItem).voice_name;
        newItem.id = '';
    } else {
        newItem.id = item.id;
        newItem.name = '';
    }
    emit('select', newItem);
    isOpen.value = false;
};

defineExpose({ open });
</script>

<style lang="scss" scoped></style>
