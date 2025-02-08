<template>
    <div class="flex size-full flex-col overflow-hidden p-4 pt-2 md:p-6">
        <div class="hidden flex-wrap items-center justify-between gap-2 md:relative md:flex">
            <h1 class="text-lg font-medium leading-none md:text-2xl">合成记录</h1>
            <PageSearch v-model="keyword" @search="searchHandle" />
        </div>
        <div class="flex items-center gap-2 pb-2 md:py-4 md:pb-4">
            <UTabs
                class="w-full overflow-hidden sm:w-fit"
                :ui="{
                    container: 'hidden',
                    list: {
                        tab: {
                            padding: 'px-0 sm:px-6',
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
                        label: '合成中',
                    },
                    {
                        label: '失败',
                    },
                ]"
                @change="tabChange"
            />
            <PageSearch v-model="keyword" class="md:hidden" @search="searchHandle" />
            <UButton
                class="ml-auto h-full rounded-lg md:h-auto md:rounded-full"
                :ui="{ rounded: 'rounded-full', padding: { sm: 'px-4' } }"
                icon="i-tabler:upload"
                @click="localUploadRef?.show()"
            >
                <span class="md:hidden">本地</span>
                <span class="hidden md:block">本地上传</span>
            </UButton>
        </div>
        <ProList
            ref="listRef"
            v-slot="{ item }: { item: CloneVoiceListItem }"
            :request-instance="apiGetCloneVoiceList"
            :scroll="true"
            :polling="(list) => list.filter((item) => isTaskPendding(item.status)).length === 0"
            class="grid grid-cols-1 gap-4 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4"
        >
            <div
                class="group relative flex-1 cursor-pointer rounded-lg bg-background-soft p-4 shadow-none md:rounded-xl lg:block"
                :class="{
                    '!cursor-not-allowed': isTaskPendding(item.status),
                    'bg-red-50 opacity-50 dark:bg-background-soft': isTaskFail(item.status),
                }"
                padding="p-4"
            >
                <div
                    class="absolute bottom-2 right-2 z-10 opacity-0 transition-opacity group-hover:opacity-100"
                    @click="
                        confirmModalRef?.open({
                            message: `确认要删除 《${item.title}》 吗？`,
                            title: '删除音色',
                            confirm: async () => {
                                await apiPostCloneVoiceDelete({ id: item.id });
                                listRef?.getList();
                            },
                        })
                    "
                >
                    <UButton
                        icon="tabler:trash"
                        color="red"
                        :ui="{ rounded: 'rounded-full' }"
                        size="2xs"
                    />
                </div>
                <div class="relative flex gap-4">
                    <div v-if="isTaskPendding(item.status)" class="absolute -right-2 -top-2">
                        <UIcon name="tabler:loader-2" class="animate-spin text-primary" />
                    </div>

                    <div
                        class="audio-container relative h-14 w-14 flex-shrink-0"
                        :class="{ 'opacity-80': isTaskPendding(item.status) }"
                    >
                        <img
                            :src="item.timbre?.cover || item.cover || squareDefaultImg"
                            class="relative size-full rounded-full"
                        />
                        <div
                            v-if="isTaskSuccess(item.status)"
                            class="absolute left-0 top-0 flex size-full cursor-pointer items-center justify-center overflow-hidden rounded-full bg-foreground/10 px-4 transition-opacity group-hover:opacity-100 md:bg-background/20 md:opacity-0 md:backdrop-blur-lg"
                            @click="detailRef?.show(item)"
                        >
                            <UIcon name="tabler:player-play-filled" size="20" class="text-white" />
                        </div>
                        <div
                            v-if="isTaskFail(item.status)"
                            class="absolute left-0 top-0 flex size-full cursor-pointer overflow-hidden rounded-full bg-foreground/10 px-4 center md:bg-background/20 md:backdrop-blur"
                            @click="detailRef?.show(item)"
                        >
                            <span class="text-xs text-foreground/50">失败</span>
                        </div>
                    </div>
                    <div class="">
                        <h2 class="line-clamp-1 font-medium">{{ item.title || '--' }}</h2>
                        <p v-if="item.task_id" class="mt-1 line-clamp-2 text-xs text-foreground/60">
                            {{ item.content || '--' }}
                        </p>
                        <p v-else class="mt-1 line-clamp-2 text-xs text-foreground/60">
                            <UBadge variant="soft" size="xs">来自本地上传</UBadge>
                        </p>
                        <p
                            class="mt-1 line-clamp-2 flex items-center gap-1 text-xs text-foreground/60"
                        >
                            <UIcon name="tabler:clock-hour-9" />
                            {{ item.create_time || '--' }}
                        </p>
                    </div>
                </div>
            </div>
        </ProList>
        <div class="fixed">
            <Detail ref="detailRef" />
            <LocalUpload ref="localUploadRef" @refresh="refreshList()" />
            <ConfirmModal ref="confirmModalRef" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import { apiGetCloneVoiceList, apiPostCloneVoiceDelete } from '~/api/audio';
import squareDefaultImg from '~/assets/images/1_1_default.png';
import ConfirmModal from '~/components/confirm-modal.vue';
import ProList from '~/components/pro-list.vue';

import Detail from './detail.vue';
import LocalUpload from './local-upload.vue';

const detailRef = getComponentExpose(Detail);
const localUploadRef = getComponentExpose(LocalUpload);
const listRef = getComponentExpose(ProList);
const confirmModalRef = getComponentExpose(ConfirmModal);

const keyword = ref<string>('');

const searchHandle = (e: string) => {
    listRef.value?.getList({ newParams: { keyword: e }, resetPage: true });
};

const tabChange = (e: number) => {
    const status = ['', 1, 0, 2];
    listRef.value?.getList({ newParams: { status: status[e] }, resetPage: true });
};

const refreshList = () => {
    listRef.value?.getList();
    listRef.value?.startPolling();
};

defineExpose({
    refresh: refreshList,
});
</script>

<style lang="scss" scoped></style>
