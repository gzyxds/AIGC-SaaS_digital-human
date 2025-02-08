<template>
    <div
        class="form-input focus-within:ring-primary-500 dark:focus-within:ring-primary-400 relative block w-full rounded-md border-0 bg-gray-50 p-0 text-sm text-gray-900 placeholder-gray-400 shadow-sm ring-1 ring-inset ring-gray-300 file:mr-1.5 file:border-0 file:bg-transparent file:p-0 file:font-medium file:text-gray-500 file:outline-none focus-within:outline-none focus-within:ring-2 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:ring-gray-700 dark:file:text-gray-400"
        :class="[customClassName, disabled ? 'cursor-not-allowed opacity-75' : '']"
    >
        <UInput
            id="__pro_uploader__"
            ref="fileInputRef"
            type="file"
            color="gray"
            class="size-full"
            :disabled="disabled"
            :ui="{
                wrapper: 'absolute',
                base: 'inset-0 w-full h-full cursor-pointer opacity-0',
            }"
            :accept="inputAccept"
            @change="handleFileUpload"
        />
        <div
            v-if="uploadStatus === UploadStatusEnum.INITIAL"
            class="pointer-events-none absolute z-[1] size-full rounded-md"
        >
            <div class="size-full flex-col gap-1 text-gray-400 center dark:text-gray-500">
                <i
                    v-dompurify-html="uploaderIcons[type]"
                    class="text-xl"
                    :class="errorMessage !== '' ? 'text-red-500' : ''"
                />
                <p v-if="errorMessage !== ''" class="text-xs text-red-500">{{ errorMessage }}</p>
                <p v-else class="text-xs">{{ uploaderText }}</p>
            </div>
        </div>
        <div
            v-if="uploadStatus !== UploadStatusEnum.INITIAL"
            class="absolute inset-0 z-[1] size-full rounded-md"
        >
            <div
                v-if="uploadStatus === UploadStatusEnum.SUCCESS"
                class="group absolute inset-0 z-[1] size-full cursor-pointer rounded-md"
            >
                <img
                    v-if="type === 'image'"
                    :src="tempFile.url"
                    class="size-full rounded-md object-contain"
                    alt=""
                />
                <video
                    v-if="type === 'video' && tempFile.url"
                    ref="videoRef"
                    preload=""
                    class="size-full rounded-md object-contain"
                    :src="tempFile.url"
                    controls
                />
                <div
                    v-if="type === 'audio' && tempFile.url"
                    class="flex size-full flex-col justify-center px-4"
                >
                    <p class="truncate text-sm center">{{ tempFile.file?.name }}</p>
                    <AudioPlayer
                        :src="tempFile.url"
                        @get-duration="(duration) => emit('loaded:audio', duration)"
                    />
                </div>
                <div
                    v-if="type === 'file' && tempFile.url"
                    class="size-full gap-2 rounded-md center"
                >
                    <i v-dompurify-html="uploaderIcons.folder" class="text-primary" />
                    <span class="text-sm text-primary">{{ tempFile.file?.name }}</span>
                </div>
                <div
                    class="absolute bottom-0 left-0 flex w-full translate-y-0 overflow-hidden rounded-b-md bg-black/30 opacity-0 backdrop-blur-lg transition-[transform,opacity] group-hover:translate-y-6 group-hover:opacity-100 dark:bg-gray-800"
                >
                    <span
                        class="flex-1 cursor-pointer p-2 center hover:bg-primary"
                        @click="reSelect"
                    >
                        <UIcon name="tabler:reload" class="text-white" />
                    </span>
                    <span
                        class="flex-1 cursor-pointer p-2 center hover:bg-red-500"
                        @click="clearFile"
                    >
                        <UIcon name="tabler:trash" class="text-white" />
                    </span>
                    <a
                        v-if="tempFile.file"
                        :download="tempFile.file?.name"
                        :href="tempFile.url"
                        class="flex-1 cursor-pointer p-2 center hover:bg-green-500"
                    >
                        <UIcon name="tabler:download" class="text-white" />
                    </a>
                </div>
            </div>

            <div
                v-if="uploadStatus === UploadStatusEnum.UPLOADING"
                class="absolute inset-0 z-50 size-full flex-col overflow-hidden rounded-md px-4 backdrop-blur-lg center"
            >
                <UProgress class="my-2" :value="uploadProgress" indicator />
                <p class="text-xs text-foreground/60">
                    {{ uploadProgress === 100 ? '上传成功' : '正在上传' }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { AxiosProgressEvent } from 'axios';

import UInput from '#ui/components/forms/Input.vue';
import { uploadFile } from '~/api/common';
import { uploaderIcons } from '~/assets/svgIcon';
import { UploadFileTypeEnum } from '~/enums/variableEnum';
import type { fileTypes } from '~/utils/check';
import { formatSecondsToMinutes } from '~/utils/helper';

type FileType = 'image' | 'video' | 'file' | 'audio';

enum UploadStatusEnum {
    INITIAL = 0,
    UPLOADING = 1,
    SUCCESS = 2,
    FAIL = 3,
}

const props = withDefaults(
    defineProps<{
        modelValue?: string;
        type: FileType;
        text?: string;
        icon?: string;
        square?: boolean;
        showDelete?: boolean;
        class?: string;
        maxSize?: number;
        maxDuration?: number;
        minDuration?: number;
        disabled?: boolean;
        accept?: string;
    }>(),
    {
        color: 'gray',
        modelValue: '',
        type: 'image',
        text: '',
        icon: '',
        square: false,
        showDelete: true,
        class: '',
        maxSize: 200,
        maxDuration: undefined,
        disabled: false,
        accept: '',
        minDuration: 0,
    }
);

const emit = defineEmits<{
    'update:modelValue': [value: string];
    change: [value: UploadResponse & UploaderTempFile];
    clear: [];
    'loaded:video': [value: number];
    'loaded:audio': [value: number];
}>();

// 定义上传进度和状态
const uploadProgress = ref<number>(0);
const init = ref<boolean>(false);
const uploadStatus = ref<UploadStatusEnum>(UploadStatusEnum.INITIAL);
const tempFile = ref<UploaderTempFile>({
    file: null,
    url: '',
    duration: 0,
});
const fileInputRef = getComponentExpose(UInput);
const videoRef = ref<HTMLVideoElement>();
const errorMessage = ref<string>('');

const inputAccept = computed(() => {
    if (props.accept) return props.accept;
    if (props.type === 'image') {
        return UploadFileTypeEnum.IMAGE;
    } else if (props.type === 'file') {
        return UploadFileTypeEnum.FILE;
    } else if (props.type === 'audio') {
        return UploadFileTypeEnum.AUDIO;
    } else {
        return UploadFileTypeEnum.VIDEO;
    }
});

onMounted(() => {
    if (props.modelValue) {
        initial(props.modelValue);
    }
});

watch(
    () => props.modelValue,
    (val) => {
        if (val) {
            initial(val);
        }
    }
);

const initial = (src: string) => {
    if (init.value) return;
    init.value = true;
    const suffix = props.modelValue.split('.').pop();
    const typeSuffix = inputAccept.value.split(',');
    if (!typeSuffix.includes(`.${suffix}`) && !typeSuffix.includes(`.${suffix?.toLowerCase()}`)) {
        const msg = '文件类型不匹配';
        clearFile();
        setError(msg);
        useMessage().warn(msg);
        throw new Error(msg);
    }
    uploadStatus.value = UploadStatusEnum.SUCCESS;
    tempFile.value.url = src;
};

const customClassName = computed(() => {
    if (!props.class) return 'h-28 w-full';

    // 拆分 class 字符串为数组
    const classArray = props.class.split(/\s+/);

    // 检测是否存在 h-、w- 和 size- 开头的类名
    const hasH = classArray.some((className) => /^h-/.test(className));
    const hasW = classArray.some((className) => /^w-/.test(className));
    const hasSize = classArray.some((className) => /^size-/.test(className));

    // 如果不存在 h- 和 w- 且不存在 size-，添加默认类
    if (!hasH && !hasSize) {
        classArray.push('h-28');
    }
    if (!hasW && !hasSize) {
        classArray.push('w-full');
    }

    // 输出处理后的 class 字符串
    return classArray.join(' ');
});

const uploaderText = computed(() => {
    if (props.text) {
        return props.text;
    }
    return `点击上传${{ image: '图片', video: '视频', audio: '音频', file: '文件' }[props.type]}`;
});

// 上传文件的函数
const upload = async (file: File) => {
    uploadStatus.value = UploadStatusEnum.UPLOADING;

    const typeMap: Record<FileType, 'image' | 'video' | 'file'> = {
        image: 'image',
        audio: 'file',
        video: 'video',
        file: 'file',
    };

    return uploadFile(
        typeMap[props.type],
        { file },
        {
            onUploadProgress: (e: AxiosProgressEvent) => {
                if (e.total) {
                    uploadProgress.value = Math.round((e.loaded / e.total) * 100);
                }
            },
        }
    );
};

const beforeUpload = (file: File) => {
    try {
        const { size, type, name } = file;

        if (size > props.maxSize * 1024 * 1024) {
            const msg = `文件大小不能超过${formatFileSize(props.maxSize * 1024 * 1024)}`;
            clearFile();
            setError(msg);
            useMessage().warn(msg);
            throw new Error(msg);
        }

        const inputType = getInputFileType(type as keyof typeof fileTypes);
        const typeSuffix = inputAccept.value.split(',');
        if (!typeSuffix.includes(inputType) && !typeSuffix.includes(inputType?.toLowerCase())) {
            const msg = '文件类型不匹配';
            clearFile();
            setError(msg);
            useMessage().warn(msg);
            throw new Error(msg);
        }

        const blobUrl = URL.createObjectURL(file);

        const handleMap = {
            image: (file: File) => {},
            video: async (file: File) => {
                tempFile.value.duration = await getVideoDuration(blobUrl);
                if (!isMobile()) {
                    if (props.minDuration > 0 && tempFile.value.duration < props.minDuration) {
                        const msg = `视频时长不能小于${formatSecondsToMinutes(props.minDuration)}`;
                        clearFile();
                        setError(msg);
                        useMessage().warn(msg);
                        throw new Error(msg);
                    }
                    if (props.maxDuration && tempFile.value.duration > props.maxDuration) {
                        const msg = `视频时长不能超过${formatSecondsToMinutes(props.maxDuration)}`;
                        clearFile();
                        setError(msg);
                        useMessage().warn(msg);
                        throw new Error(msg);
                    }
                }
            },
            audio: async (file: File) => {
                tempFile.value.duration = await getAudioDuration(blobUrl);
                if (!isMobile()) {
                    if (props.minDuration > 0 && tempFile.value.duration < props.minDuration) {
                        const msg = `音频时长不能小于${formatSecondsToMinutes(props.minDuration)}`;
                        clearFile();
                        setError(msg);
                        useMessage().warn(msg);
                        throw new Error(msg);
                    }
                    if (props.maxDuration && tempFile.value.duration > props.maxDuration) {
                        const msg = `音频时长不能超过${formatSecondsToMinutes(props.maxDuration)}`;
                        clearFile();
                        setError(msg);
                        useMessage().warn(msg);
                        throw new Error(msg);
                    }
                }
            },
            file: (file: File) => {},
        };
        pushTempFile(file);

        return handleMap[props.type](file);
    } catch (error) {
        setError(error as string);
        console.error(error);
    }
};

// 处理文件上传
const handleFileUpload = async (event: FileList) => {
    const file: File = event[0];

    if (file) {
        clearError();
        await beforeUpload(file);

        upload(file)
            .then((response) => {
                emit('update:modelValue', response.uri);
                emit('change', { ...response, ...tempFile.value });
                useMessage().success('上传成功');
                uploadStatus.value = UploadStatusEnum.SUCCESS;
            })
            .catch((error) => {
                console.error(error);
                setError('上传失败');
                uploadStatus.value = UploadStatusEnum.INITIAL;
            });
    }
};

const reSelect = () => {
    fileInputRef.value?.input.click();
};

const clearFile = () => {
    emit('update:modelValue', '');
    removeTempFile();
    if (fileInputRef.value) {
        fileInputRef.value.input.value = '';
    }
    uploadStatus.value = UploadStatusEnum.INITIAL;
    emit('clear');
};

const pushTempFile = (file: File) => {
    tempFile.value.file = file;
    tempFile.value.url = URL.createObjectURL(file);
};

const removeTempFile = () => {
    tempFile.value.file = null;
    tempFile.value.url = '';
    tempFile.value.duration = 0;
};

const setError = (msg: string, toast?: boolean) => {
    errorMessage.value = msg;
    if (toast) {
        useMessage().error(msg);
    }
};

const clearError = () => {
    errorMessage.value = '';
};
</script>

<style lang="scss" scoped>
#__pro_uploader__ {
    &::-webkit-file-upload-button {
        opacity: 0 !important;
    }
    @apply text-transparent;
}
</style>
