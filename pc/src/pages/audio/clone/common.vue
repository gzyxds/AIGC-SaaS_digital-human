<template>
    <PageContainer :scroll="true" breadcrumb="系统音色" :padding="true" :background="true">
        <div class="mb-2 flex gap-4">
            <UInputMenu
                v-if="scenes.length > 0"
                v-model="selectedScene"
                placeholder="场景"
                :options="scenes"
                @change="handleSceneChange"
            />
            <UInputMenu
                v-if="types.length > 0"
                v-model="selectedType"
                placeholder="类型"
                :options="types"
                @change="handleTypeChange"
            />
        </div>
        <ScrollArea>
            <div
                class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5"
            >
                <div
                    v-for="item in voicesList"
                    :key="item.id"
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
                                            url: item.voiceUrl,
                                        })
                                    "
                                >
                                    试听
                                </UButton>
                                <UButton
                                    size="2xs"
                                    trailing-icon="tabler:chevron-right"
                                    @click="toUse(item.id, item.voice_name, item.name)"
                                >
                                    使用
                                </UButton>
                            </UButtonGroup>
                        </div>
                    </div>
                    <div class="audio-container relative h-12 w-12 flex-shrink-0">
                        <img :src="squareDefaultImg" class="relative size-full rounded-full" />
                    </div>
                    <div class="flex flex-1 flex-col gap-1">
                        <h2 class="">
                            <span class="font-medium">{{ item.name || '--' }}</span>
                        </h2>
                        <p class="line-clamp-2 text-sm text-foreground/60">
                            语言：{{ item.language }}
                        </p>
                        <p class="line-clamp-2 text-sm text-foreground/60">类型：{{ item.type }}</p>
                        <p class="mt-1 flex flex-wrap gap-1 text-sm text-foreground/60">
                            <UBadge v-for="scene in item.scene" :key="scene" variant="soft">
                                {{ scene }}
                            </UBadge>
                        </p>
                    </div>
                </div>
            </div>
        </ScrollArea>
        <AudioModal ref="AudioModalRef" />
    </PageContainer>
</template>

<script lang="ts" setup>
import { apiGetSystemVoiceList } from '~/api/audio';
import squareDefaultImg from '~/assets/images/1_1_default.png';
import AudioModal from '~/components/audio-modal.vue';
import { siteConfig } from '~/config/system';

useHead({
    title: '系统音色',
});

definePageMeta({
    layout: 'audio-clone',
});

const AudioModalRef = getComponentExpose(AudioModal);

const types = ref<string[]>([]);
const scenes = ref<string[]>([]);

const selectedScene = ref<string>();
const selectedType = ref<string>();
const systemVoices = ref<SystemVoiceItem[]>([]);
const voicesList = ref<SystemVoiceItem[]>([]);

const toUse = async (voice_id: number, voice_name: string, title: string) => {
    const item: VoiceSelectItem = {
        id: voice_id,
        title,
        cover: '',
        name: voice_name,
        type: 'local',
    };
    const secretVoiceName = await CryptoUtil.encrypt(JSON.stringify(item), siteConfig.appSrcret);
    navigateTo({
        path: '/audio/synthesis',
        query: {
            voice_data: secretVoiceName,
        },
    });
};

onMounted(async () => {
    systemVoices.value = await apiGetSystemVoiceList();
    voicesList.value = useCloned(systemVoices.value).cloned.value;
    const scenesArr = systemVoices.value.map((item, index) => {
        return item.scene;
    });
    const typesArr = systemVoices.value.map((item, index) => {
        return item.type;
    });

    scenes.value = ['全部', ...new Set(scenesArr.flat())];
    types.value = ['全部', ...new Set(typesArr)];
});

const filterVoicesList = (scene: string, type: string) => {
    // 如果场景和类型都为 "全部"，直接返回所有数据
    if (scene === '全部' && type === '全部') {
        return systemVoices.value;
    }

    // 根据场景和类型进行过滤
    return systemVoices.value.filter((item) => {
        const matchesScene = scene === '全部' || item.scene.includes(scene);
        const matchesType = type === '全部' || item.type === type;
        return matchesScene && matchesType;
    });
};

const handleSceneChange = (e: string) => {
    // 根据新的场景值和当前选中的类型值进行过滤
    voicesList.value = filterVoicesList(e, selectedType.value || '全部');
};

const handleTypeChange = (e: string) => {
    // 根据新的类型值和当前选中的场景值进行过滤
    voicesList.value = filterVoicesList(selectedScene.value || '全部', e);
};
</script>

<style lang="scss"></style>
