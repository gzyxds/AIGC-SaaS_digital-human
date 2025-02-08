<template>
    <PageContainer :scroll="true" breadcrumb="我的作品" :padding="true" :background="true">
        <template #breadcrumb-right>
            <PageSearch v-model="keyword" @search="searchHandle" />
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
            v-slot="{ item }: { item: AiAvatarItem }"
            :request-instance="apiGetAiAvatarList"
            :scroll="true"
            :polling="(list) => list.filter((item) => isTaskPendding(item.status)).length === 0"
            class="grid grid-cols-1 gap-4 p-0.5 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5"
        >
            <div
                class="relative flex flex-col overflow-hidden rounded-lg bg-background shadow dark:bg-gray-800"
                :class="{
                    'opacity-70': isTaskPendding(item.status) || isTaskInvalid(item.status),
                    'bg-red-50 dark:opacity-50': isTaskFail(item.status),
                }"
            >
                <div class="relative">
                    <div
                        v-if="!isTaskFail(item.status) && item.resultFile"
                        class="absolute inset-0 z-10 size-full gap-4 bg-black/10 opacity-0 backdrop-blur transition-opacity center hover:opacity-100"
                    >
                        <div class="flex flex-col items-center gap-1">
                            <UButton
                                icon="i-tabler:player-play-filled"
                                class="text-white hover:bg-transparent dark:text-white dark:hover:bg-transparent"
                                :ui="{ rounded: 'rounded-full' }"
                                size="lg"
                                variant="outline"
                                @click="videoPlayerRef?.open(item.resultFile)"
                            />
                            <span class="text-xs text-white">播放</span>
                        </div>
                        <div
                            v-if="!isTaskInvalid(item.status)"
                            class="flex flex-col items-center gap-1"
                        >
                            <UButton
                                icon="tabler:dots"
                                class="text-white hover:bg-transparent dark:text-white dark:hover:bg-transparent"
                                :ui="{ rounded: 'rounded-full' }"
                                size="lg"
                                variant="outline"
                                @click="recordDetailRef?.open(item)"
                            />
                            <span class="text-xs text-white">详情</span>
                        </div>
                    </div>
                    <AspectRatio :src="item.cover" class="bg-background-soft" />
                </div>
                <div class="p-3">
                    <h1 class="truncate text-sm" :class="{ 'text-foreground/80': !item.title }">
                        {{ item.title || '暂无标题' }}
                    </h1>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-foreground/60">{{ item.create_time }}</span>
                        <div class="flex items-center gap-1">
                            <span
                                class="whitespace-nowrap rounded-full border-[0.5px] border-foreground/5 bg-foreground/5 px-1.5 py-0.5 text-xs text-foreground/60 center dark:bg-foreground/10"
                                :class="{
                                    'bg-red-100 text-red-500/60 dark:text-foreground/60':
                                        isTaskFail(item.status),
                                }"
                            >
                                {{
                                    {
                                        '0': '合成中',
                                        '1': formatSecond(item.duration),
                                        '2': '合成失败',
                                        null: '已失效',
                                    }[item.status]
                                }}
                                <UIcon
                                    v-if="isTaskPendding(item.status)"
                                    name="tabler:loader-2"
                                    class="animate-spin text-foreground/70"
                                />
                            </span>
                            <UDropdown
                                :ui="{
                                    width: 'w-20',
                                    container: 'z-[9999] group',
                                    item: {
                                        icon: { base: 'size-4' },
                                        padding: 'px-1 py-1',
                                    },
                                }"
                                :items="getDownMenuItems(item)"
                                :popper="{ placement: 'top-end' }"
                            >
                                <UButton
                                    size="2xs"
                                    variant="ghost"
                                    :ui="{
                                        rounded: 'rounded-full',
                                        icon: { base: 'text-foreground/70' },
                                    }"
                                    icon="i-tabler:dots-vertical"
                                />
                            </UDropdown>
                        </div>
                    </div>
                </div>
            </div>
        </ProList>
        <RecordDetail ref="recordDetailRef" />
        <ConfirmModal ref="confirmModalRef" />
        <VideoPlayer ref="videoPlayerRef" />
    </PageContainer>
</template>

<script lang="ts" setup>
import type { NuxtLinkProps } from '#app';
import { apiGetAiAvatarList, apiPostAiAvatarDelete } from '~/api/avatar';
import ConfirmModal from '~/components/confirm-modal.vue';
import ProList from '~/components/pro-list.vue';
import VideoPlayer from '~/components/video-player.vue';

import RecordDetail from './components/record-detail.vue';

const recordDetailRef = getComponentExpose(RecordDetail);
const confirmModalRef = getComponentExpose(ConfirmModal);
const videoPlayerRef = getComponentExpose(VideoPlayer);

useHead({
    title: '我的作品',
});

definePageMeta({
    layout: 'avatar',
});

const listRef = getComponentExpose(ProList);

const keyword = ref<string>('');

const getDownMenuItems = (item: AiAvatarItem) => {
    const items: DropdownItem[][] = [
        [
            {
                label: '详情',
                icon: 'i-tabler:info-circle',
                click: () => recordDetailRef.value?.open(item),
            },
        ],
        [
            {
                label: '删除',
                icon: 'i-heroicons-trash-20-solid',
                iconClass: 'text-red-500',
                labelClass: 'text-red-500',
                click: () => {
                    confirmModalRef.value?.open({
                        message: `确认要删除 《${item.title}》 吗？`,
                        title: '删除记录',
                        confirm: async () => {
                            await apiPostAiAvatarDelete({
                                id: item.id,
                            });
                            listRef.value?.getList();
                        },
                    });
                },
            },
        ],
    ];

    if (item.resultFile) {
        items[0].push({
            label: '下载',
            icon: 'i-tabler:download',
            click: () => {
                downloadFile({ src: item.resultFile });
            },
        });
    }

    return items;
};

const searchHandle = (e: string) => {
    listRef.value?.getList({ newParams: { keyword: e }, resetPage: true });
};

const tabChange = (e: number) => {
    const status = ['', '1', '0', '2'];
    listRef.value?.getList({ newParams: { status: status[e] }, resetPage: true });
};
</script>

<style lang="scss" scoped></style>
