<template>
    <PageContainer
        breadcrumb="我的音色"
        :scroll="true"
        :mobile-back="false"
        :padding="true"
        :background="true"
    >
        <template #breadcrumb-right>
            <div class="flex items-center justify-between gap-2">
                <UButton
                    class="hidden md:order-1 md:flex"
                    icon="tabler:question-circle"
                    variant="soft"
                    @click="tutorial = true"
                >
                    <span class="hidden md:block">教程</span>
                </UButton>
                <UButton
                    class="md:order-1 md:hidden"
                    icon="tabler:question-circle"
                    variant="soft"
                    size="md"
                    @click="tutorial = true"
                />

                <UButton
                    class="md:order-1 md:hidden"
                    icon="tabler:plus"
                    size="md"
                    to="/audio/clone/create"
                />

                <PageSearch v-model="keyword" @search="search" />
            </div>
        </template>
        <div class="mb-4">
            <UTabs
                class="overflow-hidden sm:w-fit"
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
                        label: '全部',
                    },
                    {
                        label: '完成',
                    },
                    {
                        label: '进行中',
                    },
                    {
                        label: '失败',
                    },
                ]"
                @change="tabChange"
            />
        </div>
        <ProList
            ref="listRef"
            v-slot="{ item }: { item: VoiceItem }"
            :request-instance="apiGetVoiceList"
            :scroll="true"
            :polling="(list) => list.filter((item) => isTaskPendding(item.status)).length === 0"
            class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5"
        >
            <div class="group relative flex cursor-pointer gap-4 rounded-lg bg-background-soft p-4">
                <div
                    class="absolute bottom-0 left-0 z-10 h-0 w-full cursor-pointer overflow-hidden rounded-lg bg-foreground/20 backdrop-blur-lg transition-[height] group-hover:h-10"
                >
                    <div class="flex w-full items-center justify-between px-2 py-2">
                        <UButton
                            icon="tabler:trash"
                            color="red"
                            :ui="{ rounded: 'rounded-full' }"
                            size="2xs"
                            @click="
                                confirmModalRef?.open({
                                    message: `确认要删除 《${item.name}》 吗？`,
                                    title: '删除音色',
                                    confirm: async () => {
                                        await apiPostVoiceDelete({ id: item.id });
                                        listRef?.getList();
                                    },
                                })
                            "
                        />

                        <UButtonGroup
                            v-if="isTaskSuccess(item.status)"
                            size="2xs"
                            orientation="horizontal"
                            :ui="{ rounded: 'rounded-full' }"
                        >
                            <UButton
                                icon="heroicons:play"
                                size="2xs"
                                @click="
                                    AudioModalRef?.open({
                                        cover: item.cover,
                                        url: item.voice_url,
                                    })
                                "
                            >
                                试听
                            </UButton>
                            <UButton
                                size="2xs"
                                trailing-icon="tabler:chevron-right"
                                @click="toUse(item)"
                            >
                                使用
                            </UButton>
                        </UButtonGroup>
                    </div>
                </div>
                <div class="relative h-14 w-14 flex-shrink-0">
                    <img
                        :src="item.cover || squareDefaultImg"
                        class="relative size-full rounded-full"
                    />
                    <div
                        v-if="isTaskPendding(item.status)"
                        class="absolute inset-0 size-full rounded-full bg-foreground/10 backdrop-blur-sm center dark:bg-background/10"
                    >
                        <UIcon name="tabler:loader-2" class="animate-spin text-white" />
                    </div>
                    <div
                        v-if="isTaskFail(item.status)"
                        class="absolute inset-0 size-full rounded-full bg-foreground/10 backdrop-blur-sm center dark:bg-background/10"
                    >
                        <span class="text-xs text-foreground/70">失败</span>
                    </div>
                </div>
                <div class="">
                    <h2 class="line-clamp-1 font-medium">{{ item.name || '--' }}</h2>
                    <p class="mt-1 line-clamp-2 text-sm text-foreground/60">
                        {{ item.record || '--' }}
                    </p>

                    <p class="mt-1 line-clamp-2 text-xs text-foreground/60">
                        {{ item.create_time || '--' }}
                    </p>
                </div>
            </div>
        </ProList>
        <AudioModal ref="AudioModalRef" />
        <ProModal v-model="tutorial" width="sm:max-w-lg" title="合成教程" @close="closeTutorial">
            <div class="flex max-h-[80vh] flex-col gap-4 overflow-y-auto">
                <div class="py-2 text-sm leading-7">
                    <div class="flex items-start gap-2">
                        <div class="flex h-7 items-center">
                            <UIcon
                                name="tabler:hexagon-number-1-filled"
                                class="flex-shrink-0 text-primary"
                                size="18"
                            />
                        </div>
                        点击创建音色生成录制链接二维码
                    </div>
                    <div class="flex items-start gap-2">
                        <div class="flex h-7 items-center">
                            <UIcon
                                name="tabler:hexagon-number-2-filled"
                                class="flex-shrink-0 text-primary"
                                size="18"
                            />
                        </div>
                        使用手机扫描二维码，根据页面提示内容完成录制，并按需求填写音色信息，如音色名、头像等
                    </div>
                    <div class="flex items-start gap-2">
                        <div class="flex h-7 items-center">
                            <UIcon
                                name="tabler:hexagon-number-3-filled"
                                class="flex-shrink-0 text-primary"
                                size="18"
                            />
                        </div>
                        提交保存，点击【我已完成录制，点击关闭】按钮关闭弹窗并刷新列表
                    </div>
                </div>

                <div class="mt-4 center" @click="closeTutorial">
                    <UButton>我知道了</UButton>
                </div>
            </div>
        </ProModal>
        <ConfirmModal ref="confirmModalRef" />
    </PageContainer>
</template>

<script lang="ts" setup>
import { apiGetVoiceList, apiPostVoiceDelete } from '~/api/audio';
import squareDefaultImg from '~/assets/images/1_1_default.png';
import AudioModal from '~/components/audio-modal.vue';
import ConfirmModal from '~/components/confirm-modal.vue';
import ProList from '~/components/pro-list.vue';
import { siteConfig } from '~/config/system';
import { TaskStatusEnum } from '~/enums/variableEnum';

useHead({
    title: '我的音色',
});

definePageMeta({
    layout: 'audio-clone',
});

const AudioModalRef = getComponentExpose(AudioModal);
const listRef = getComponentExpose(ProList);
const confirmModalRef = getComponentExpose(ConfirmModal);

const tutorial = ref<boolean>(false);
const keyword = ref<string>('');

const search = (e: string) => {
    listRef.value?.getList({ newParams: { keyword: e }, resetPage: true });
};

const toUse = async (voice: VoiceItem) => {
    const item: VoiceSelectItem = {
        id: voice.id,
        title: voice.name,
        cover: voice.cover,
        name: '',
        type: 'user',
    };
    const secretVoiceName = await CryptoUtil.encrypt(JSON.stringify(item), siteConfig.appSrcret);
    navigateTo({
        path: '/audio/synthesis',
        query: {
            voice_data: secretVoiceName,
        },
    });
};

const closeTutorial = () => {
    tutorial.value = false;
};

const tabChange = (e: number) => {
    console.log(e);
    const status = ['', '1', '0', '2'];
    listRef.value?.getList({ newParams: { status: status[e] }, resetPage: true });
};

onMounted(() => {
    const CloneLayoutInject = inject('CloneLayout') as {
        needRefresh: globalThis.Ref<boolean, boolean>;
        updateNeedRefresh: () => void;
    };

    watch(
        () => CloneLayoutInject?.needRefresh.value,
        async (val) => {
            if (val === true) {
                useRefreshUser(async () => {
                    await listRef.value?.refresh();
                    CloneLayoutInject.updateNeedRefresh();
                });
            }
        }
    );
});
</script>

<style lang="scss" scoped></style>
