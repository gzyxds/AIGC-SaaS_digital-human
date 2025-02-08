<template>
    <ProModal v-model="isOpen" title="本地音频上传" width="sm:max-w-sm">
        <div class="order-2 mt-2 flex-1">
            <UForm
                :schema="schema"
                :state="formData"
                class="flex h-full flex-col space-y-6"
                :validate-on="['submit']"
                @submit="onSubmit"
            >
                <div class="space-y-4">
                    <UFormGroup label="音频文件(必填)" name="file_id" size="lg" required>
                        <ProUploader
                            class="h-24"
                            type="audio"
                            :max-size="200"
                            :max-duration="5 * 60"
                            accept=".mp3,.wav"
                            @change="handleVideoChange"
                        />
                        <template #help>
                            <span class="flex flex-col text-xs">
                                <span class="text-xs"
                                    >Tips: 音频时长不超过5分钟，仅支持.wav、.mp3格式</span
                                >
                            </span>
                        </template>
                    </UFormGroup>

                    <UFormGroup label="音频标题" name="title" size="lg">
                        <UInput
                            v-model="formData.title"
                            placeholder="请填写音频标题"
                            color="gray"
                            @change="handleInput"
                        />
                        <template #help>
                            <span class="flex flex-col text-xs">
                                <span class="text-xs">Tips: 标题长度不超过30个字</span>
                            </span>
                        </template>
                    </UFormGroup>

                    <UFormGroup label="音频头像" name="cover" size="lg">
                        <ProUploader v-model="formData.cover" type="image" />
                        <template #help>
                            <span class="flex flex-col text-xs">
                                <span>Tips: 建议使用1:1比例的.png、.jpg、.jpeg格式的图片</span>
                            </span>
                        </template>
                    </UFormGroup>
                </div>
                <div class="flex-col gap-2 center">
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
                    <UButton
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
        </div>
    </ProModal>
</template>

<script lang="ts" setup>
import { type InferType, object, string } from 'yup';

import type colors from '#ui-colors';
import { apiPostUploadLocalVoice } from '~/api/audio';
import commonVoices from '~/assets/commonVoices.json';
import squareDefaultImg from '~/assets/images/1_1_default.png';

const isConfirmAgreement = ref<boolean>(false);

const isOpen = ref<boolean>(false);
const schema = object({
    file_id: string().required('请上传音频文件'),
});
const btnStatus = ref<boolean>(false);
const fileName = ref<string>('');

const emit = defineEmits<{
    refresh: [];
}>();

const defaultFormData: UploadLocalVoice = {
    cover: '',
    title: '',
    file_id: '',
    duration: 0,
};
const formData = reactive<UploadLocalVoice>({ ...defaultFormData });

const show = () => {
    isOpen.value = true;
    resetForm(formData, defaultFormData);
};

const handleInput = async () => {
    await nextTick();
    if (formData.title && formData.title.length > 30) {
        formData.title = formData.title.slice(0, 30);
        useMessage().warn('标题最多为30个字');
    }
};

const handleVideoChange = async (e: UploadResponse & UploaderTempFile) => {
    formData.file_id = e.id;
    formData.duration = e.duration || 0;
    fileName.value = e.file?.name.split('.')?.[0] || createFileName();
};

const onSubmit = async () => {
    try {
        btnStatus.value = true;
        if (!formData.title) {
            formData.title = fileName.value;
        }
        await apiPostUploadLocalVoice(formData);
        resetForm(formData, defaultFormData);
        btnStatus.value = false;
        isOpen.value = false;
        emit('refresh');
    } catch (error) {
        btnStatus.value = false;
    }
};

defineExpose({ show: show });
</script>

<style lang="scss" scoped></style>
