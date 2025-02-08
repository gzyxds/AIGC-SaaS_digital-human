<template>
    <PageContainer
        :scroll="true"
        breadcrumb="我的形象"
        :mobile-back="false"
        :padding="true"
        :background="true"
    >
        <template #breadcrumb-right>
            <div class="flex items-center gap-2">
                <UButton
                    class="md:order-1 md:hidden"
                    icon="tabler:clock"
                    variant="soft"
                    size="md"
                    to="/avatar/works"
                />
                <UButton
                    class="md:order-1 md:hidden"
                    :icon="isBatchSelect ? 'tabler:x' : 'tabler:list-check'"
                    size="md"
                    :variant="isBatchSelect ? 'outline' : 'solid'"
                    @click="
                        () => {
                            if (isBatchSelect) batchList = [];
                            isBatchSelect = !isBatchSelect;
                        }
                    "
                />
                <UButton
                    id="__driver_addonce_h5__"
                    class="md:order-1 md:hidden"
                    icon="tabler:plus"
                    size="md"
                    @click="showCreater = true"
                />
                <PageSearch v-model="keyword" @search="searchHandle" />
            </div>
        </template>

        <div class="flex flex-col justify-between gap-4 md:mb-4 md:flex-row md:items-center">
            <UTabs
                v-if="!isBatchSelect"
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
            <div v-else class="md:h-10"></div>
            <div class="flex items-center gap-2">
                <UButton
                    id="__driver_addonce__"
                    class="hidden md:flex"
                    :ui="{ rounded: 'rounded-full', padding: { sm: 'px-4' } }"
                    @click="showCreater = true"
                >
                    新建
                </UButton>
                <UButton
                    id="__driver_addbatch__"
                    class="hidden md:flex"
                    :ui="{ rounded: 'rounded-full', icon: { size: { sm: 'h-4 h-4' } } }"
                    :icon="isBatchSelect ? 'tabler:x' : 'tabler:list-check'"
                    @click="
                        () => {
                            if (isBatchSelect) batchList = [];
                            isBatchSelect = !isBatchSelect;
                        }
                    "
                >
                    {{ isBatchSelect ? '取消批量' : '批量合成' }}
                </UButton>
                <UButton
                    v-if="batchList.length > 0"
                    id="__driver_start__"
                    :class="{
                        'fixed bottom-20 left-1/2 z-[999] -translate-x-1/2 md:static md:translate-x-0':
                            batchList.length > 0,
                    }"
                    :ui="{ rounded: 'rounded-full', padding: { sm: 'px-4' } }"
                    @click="
                        () => {
                            batchGeneratorRef?.open(batchList);
                            isBatchSelect = false;
                        }
                    "
                >
                    开始合成
                </UButton>
            </div>
        </div>
        <ProList
            ref="listRef"
            v-slot="{ item }: { item: AvatarVideoItem }"
            :request-instance="apiGetAvatarVideoList"
            :polling="(list) => list.filter((item) => isTaskPendding(item.status)).length === 0"
            :scroll="true"
            class="grid grid-cols-1 gap-4 p-0.5 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5"
        >
            <div
                class="relative flex flex-col overflow-hidden rounded-lg bg-background shadow dark:bg-gray-800"
                :class="{
                    'opacity-70': isTaskPendding(item.status) || isTaskInvalid(item.status),
                    'bg-red-50 dark:opacity-50': isTaskFail(item.status),
                }"
            >
                <div
                    v-if="isBatchSelect"
                    class="absolute left-0 top-0 z-[100] flex size-full cursor-pointer justify-end rounded-lg bg-foreground/20 p-2"
                    @click="batchSelect(item)"
                >
                    <UIcon
                        v-if="batchList.find((batchItem) => batchItem.id === item.id)"
                        name="tabler:square-rounded-check-filled"
                        class="text-primary"
                        size="28"
                    />
                    <UIcon v-else name="tabler:square-rounded" class="text-white" size="28" />
                </div>
                <div class="relative">
                    <div
                        v-if="!isTaskFail(item.status) && item.video_url"
                        class="absolute inset-0 z-10 size-full gap-4 bg-black/10 opacity-0 backdrop-blur transition-opacity center hover:opacity-100"
                    >
                        <div class="flex flex-col items-center gap-1">
                            <UButton
                                icon="i-tabler:player-play-filled"
                                class="text-white hover:bg-transparent dark:text-white dark:hover:bg-transparent"
                                :ui="{ rounded: 'rounded-full' }"
                                size="lg"
                                variant="outline"
                                @click="videoPlayerRef?.open(item.video_url)"
                            />
                            <span class="text-xs text-white">播放</span>
                        </div>
                        <div
                            v-if="!isTaskInvalid(item.status)"
                            class="flex flex-col items-center gap-1"
                        >
                            <UButton
                                icon="tabler:wand"
                                class="text-white hover:bg-transparent dark:text-white dark:hover:bg-transparent"
                                :ui="{ rounded: 'rounded-full' }"
                                size="lg"
                                variant="outline"
                                @click="previewRef?.show(item, 1)"
                            />
                            <span class="text-xs text-white">合成</span>
                        </div>
                    </div>
                    <AspectRatio :src="item.cover" class="bg-background-soft" />
                </div>
                <div class="p-3">
                    <h1 class="truncate text-sm" :class="{ 'text-foreground/80': !item.name }">
                        {{ item.name || '暂无名称' }}
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
                                        '0': '训练中',
                                        '1': formatSecond(item.duration),
                                        '2': '训练失败',
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

        <ProModal v-model="showCreater" title="新的形象" width="sm:max-w-2xl">
            <UForm
                :schema="schema"
                :state="formData"
                class="flex h-full flex-col space-y-6 md:flex-row md:space-x-6 md:space-y-0"
                :validate-on="['submit']"
                @submit="onCreateSubmit"
            >
                <div id="__driver_create_video_form__" class="flex-1 space-y-4">
                    <UFormGroup label="形象名称" name="name" size="lg" required>
                        <UInput v-model="formData.name" placeholder="请填写形象名称" color="gray" />
                    </UFormGroup>

                    <UFormGroup label="形象视频" name="avatar" size="lg" required>
                        <ProUploader
                            v-model="video_url"
                            class="h-40"
                            type="video"
                            :max-size="1024"
                            :min-duration="30"
                            :max-duration="300"
                            accept=".mp4,.mov"
                            @clear="
                                () => {
                                    formData.duration = 0;
                                    if (isAutoCover) {
                                        formData.cover = '';
                                    }
                                }
                            "
                            @change="handleVideoChange"
                        />
                        <template #help>
                            <span class="flex flex-col text-xs">
                                <span class="text-xs text-primary">
                                    视频时长在30秒～5分钟，大小最大不超过1GB
                                </span>
                                <ClientOnly>
                                    <span v-if="!isMobile()">
                                        PC端打开时系统会自动截取视频第一帧作为封面
                                    </span>
                                </ClientOnly>
                            </span>
                        </template>
                    </UFormGroup>

                    <UFormGroup label="自定义形象封面" name="avatar" size="lg">
                        <ProUploader v-model="formData.cover" type="image" />
                        <template #help>
                            <span class="flex flex-col text-xs">
                                <span> 建议使用16:9比例的.png、.jpg、.jpeg格式的图片。 </span>
                            </span>
                        </template>
                    </UFormGroup>
                </div>

                <div class="flex-1 md:flex md:flex-col">
                    <div
                        id="__driver_create_video_rule__"
                        class="rounded-lg bg-background-soft p-4 md:bg-transparent md:p-0"
                    >
                        <div>
                            <div class="flex content-center items-center justify-between text-sm">
                                <label
                                    for="v-0-38-31"
                                    class="block font-medium text-gray-700 dark:text-gray-200"
                                >
                                    形象示例
                                </label>
                            </div>
                            <div class="relative mt-1 grid grid-cols-3 gap-4">
                                <div class="flex-col center">
                                    <img :src="Example1" alt="" />
                                    <p class="mt-1 flex items-center gap-1">
                                        <UIcon
                                            name="tabler:circle-check-filled"
                                            size="18"
                                            class="text-green-500"
                                        />
                                        <span class="text-xs text-foreground/70">正脸自拍</span>
                                    </p>
                                </div>
                                <div class="flex-col center">
                                    <img :src="Example2" alt="" />
                                    <p class="mt-1 flex items-center gap-1">
                                        <UIcon
                                            name="tabler:circle-check-filled"
                                            size="18"
                                            class="text-green-500"
                                        />
                                        <span class="text-xs text-foreground/70">可张口闭口</span>
                                    </p>
                                </div>
                                <div class="flex-col center">
                                    <img :src="Example3" alt="" />
                                    <p class="mt-1 flex items-center gap-1">
                                        <UIcon
                                            name="tabler:circle-x-filled"
                                            size="18"
                                            class="text-red-500"
                                        />
                                        <span class="text-xs text-foreground/70">面部有干扰</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="flex content-center items-center justify-between text-sm">
                                <label
                                    for="v-0-38-31"
                                    class="block font-medium text-gray-700 dark:text-gray-200"
                                >
                                    形象视频要求
                                </label>
                            </div>
                            <div class="relative mt-1 text-xs leading-5 text-foreground/70">
                                <p>1.视频时长要求在30秒～5分钟，文件大小不超过1GB</p>
                                <p>
                                    2.为保障效果，视频必须保证每一帧都要正面露脸，脸部无任何遮挡，并且视频中只能出现同一个人脸
                                </p>
                                <p>
                                    3.视频人物建议闭口或微微张口，张口幅度不宜过大；距离镜头一定距离。可根据合成效果自行调整。
                                </p>
                                <p>4.视频格式为MP4/MOV，建议分辨率1080p~4K</p>
                            </div>
                        </div>
                    </div>

                    <p class="mb-4 mt-4 md:mt-auto md:center">
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

                    <UButton
                        id="__driver_create_video_start__"
                        type="submit"
                        :loading="btnStatus"
                        :disabled="!isConfirmAgreement"
                        :ui="{ rounded: 'rounded-full' }"
                        block
                        size="lg"
                    >
                        开始创建
                    </UButton>
                </div>
            </UForm>
        </ProModal>
        <Preview ref="previewRef" />
        <BatchGenerator
            ref="batchGeneratorRef"
            @close="
                () => {
                    batchList = [];
                }
            "
        />
        <ConfirmModal ref="confirmModalRef" />
        <VideoPlayer ref="videoPlayerRef" />
    </PageContainer>
</template>

<script lang="ts" setup>
import type { AxiosProgressEvent } from 'axios';
import { type InferType, object, string } from 'yup';

import {
    apiGetAvatarVideoList,
    apiPostAvatarVideoCreate,
    apiPostAvatarVideoDelete,
} from '~/api/avatar';
import { uploadFile } from '~/api/common';
import Example1 from '~/assets/images/example/1.png';
import Example2 from '~/assets/images/example/2.png';
import Example3 from '~/assets/images/example/3.png';
import ConfirmModal from '~/components/confirm-modal.vue';
import ProList from '~/components/pro-list.vue';
import VideoPlayer from '~/components/video-player.vue';

import BatchGenerator from './components/batch-generator.vue';
import Preview from './components/preview.vue';

useHead({
    title: '我的形象',
});

definePageMeta({
    layout: 'avatar',
});

const {
    start: driverStart,
    state: driverState,
    next,
} = useDriver(
    [
        {
            element: isMobile() ? '#__driver_addonce_h5__' : '#__driver_addonce__',
            popover: {
                title: '新建形象',
                description: '点击打开新建面板',
                onNextClick: async () => {
                    showCreater.value = true;
                    await nextTick();
                    next();
                },
            },
        },
        {
            element: '#__driver_create_video_rule__',
            popover: {
                title: '形象要求',
                description: '仔细阅读形象视频要求，否则可能无法正常合成数字人',
            },
        },
        {
            element: '#__driver_create_video_form__',
            popover: {
                title: '形象信息',
                description: '上传形象视频，填写形象相关信息',
            },
        },
        {
            element: '#__driver_create_video_start__',
            popover: {
                title: '开始创建',
                description: '点击开始创建数字人形象',
                onNextClick: () => {
                    showCreater.value = false;
                    next();
                },
            },
        },
    ],
    {
        cache: {
            key: '__avatar_generator__',
            enable: true,
        },
    }
);

onMounted(() => {
    driverStart();
});

const previewRef = getComponentExpose(Preview);
const batchGeneratorRef = getComponentExpose(BatchGenerator);
const confirmModalRef = getComponentExpose(ConfirmModal);
const videoPlayerRef = getComponentExpose(VideoPlayer);

const listRef = getComponentExpose(ProList);
const showCreater = ref<boolean>(false);
const isBatchSelect = ref<boolean>(false);
const batchList = ref<AvatarVideoItem[]>([]);
const isConfirmAgreement = ref<boolean>(false);
const tempCover = ref<File | null>(null);
const uploadProgress = ref<number>(0);
const keyword = ref<string>('');
const btnStatus = ref<boolean>(false);
const isAutoCover = ref<boolean>(false);

const schema = object({});

const defaultFormData: CreateAvatarVideo = {
    cover: '',
    name: '',
    file_id: '',
    duration: 0,
};
const formData = reactive<CreateAvatarVideo>({ ...defaultFormData });

const searchHandle = (e: string) => {
    listRef.value?.getList({ newParams: { keyword: e }, resetPage: true });
};

const batchSelect = (item: AvatarVideoItem) => {
    if (batchList.value.includes(item)) {
        batchList.value = batchList.value.filter((i) => i !== item);
        return;
    }
    batchList.value.push(item);
};

const video_url = ref<string>('');

const handleVideoChange = async (e: UploadResponse & UploaderTempFile) => {
    formData.duration = e.duration || 0;
    formData.file_id = e.id;
    if (e.file) {
        const blobUrl = URL.createObjectURL(e.file);
        tempCover.value = await getVideoThumbnail(blobUrl, createFileName('.png'));
    }
};

const getDownMenuItems = (item: AvatarVideoItem) => {
    const items: DropdownItem[][] = [
        [
            {
                label: '详情',
                icon: 'i-tabler:info-circle',
                click: () => previewRef.value?.show(item),
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
                        message: `确认要删除 《${item.name}》 吗？`,
                        title: '删除形象',
                        confirm: async () => {
                            await apiPostAvatarVideoDelete({
                                id: item.id,
                            });
                            listRef.value?.getList();
                        },
                    });
                },
            },
        ],
    ];

    if (item.video_url) {
        items[0].push({
            label: '下载',
            icon: 'i-tabler:download',
            click: () => {
                downloadFile({ src: item.video_url });
            },
        });
    }

    return items;
};

const tabChange = (e: number) => {
    const status = ['', '1', '0', '2'];
    listRef.value?.getList({ newParams: { status: status[e] }, resetPage: true });
};

const onCreateSubmit = async () => {
    try {
        btnStatus.value = true;
        if (!formData.cover && !isIOSWeChatBrowser()) {
            if (tempCover.value) {
                const coverRes = await uploadFile(
                    'image',
                    { file: tempCover.value },
                    {
                        onUploadProgress: (e: AxiosProgressEvent) => {
                            if (e.total) {
                                uploadProgress.value = Math.round((e.loaded / e.total) * 100);
                            }
                        },
                    }
                );
                formData.cover = coverRes.uri;
                isAutoCover.value = true;
            }
        }
        await apiPostAvatarVideoCreate(formData);
        resetForm(formData, defaultFormData);
        video_url.value = '';
        listRef.value?.refresh();
        showCreater.value = false;
        btnStatus.value = false;
    } catch (error) {
        btnStatus.value = false;
    }
};
</script>

<style lang="scss" scoped></style>
