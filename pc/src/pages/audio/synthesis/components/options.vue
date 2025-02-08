<template>
    <UForm
        :schema="schema"
        :state="formData"
        class="flex h-full flex-col"
        :validate-on="['submit']"
        @submit="onSubmit"
    >
        <div class="flex-1 space-y-4 overflow-y-auto p-4 md:p-6">
            <UFormGroup
                id="__driver_select_voice__"
                label="选择音色"
                :name="inputName"
                size="lg"
                required
            >
                <div
                    class="relative flex h-10 w-full cursor-pointer select-none rounded-md border-0 bg-gray-50 px-3.5 py-2.5 pe-11 ps-11 text-sm text-gray-900 placeholder-gray-400 shadow-sm ring-1 ring-inset ring-gray-300 active:bg-gray-100 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:ring-gray-700 dark:active:bg-gray-900"
                    @click="VoiceSelectRef?.open()"
                >
                    <span
                        class="pointer-events-none absolute inset-y-0 start-0 flex items-center px-3"
                    >
                        <UAvatar
                            v-if="voiceSelected === undefined"
                            icon="tabler:volume"
                            :ui="{
                                icon: { base: 'text-white' },
                            }"
                            class="bg-primary"
                            size="xs"
                            alt="Avatar"
                        />
                        <UAvatar
                            v-else-if="voiceSelected?.cover"
                            :src="voiceSelected?.cover"
                            class="bg-background"
                            size="xs"
                            alt="Avatar"
                        />
                        <img
                            v-else
                            :src="squareDefaultImg"
                            class="size-6 rounded-full bg-background"
                            size="xs"
                            alt="Avatar"
                        />
                    </span>
                    <span class="truncate" :class="{ 'text-foreground/55': !voiceSelected }">
                        {{ voiceSelected?.title || '未选择音色' }}
                    </span>
                    <span class="absolute inset-y-0 end-0 flex items-center px-3.5">
                        <UIcon
                            name="heroicons:chevron-up-down-solid"
                            class="iconify i-heroicons:chevron-up-down-20-solid h-5 w-5 flex-shrink-0 text-gray-400 dark:text-gray-500"
                        />
                    </span>
                </div>
            </UFormGroup>

            <UFormGroup id="__driver_title__" label="声音标题" name="title" size="lg" required>
                <UInput v-model="formData.title" placeholder="请填写声音标题" color="gray" />
            </UFormGroup>

            <UFormGroup
                id="__driver_content__"
                label="内容文本"
                name="content"
                required
                :hint="`${formData.content?.length}/900`"
            >
                <UTextarea
                    ref="textareaRef"
                    v-model="formData.content"
                    color="gray"
                    resize
                    variant="outline"
                    :rows="5"
                    placeholder="请填写需要合成的内容文本"
                    @change="calculateCost"
                    @input="calculateCost"
                />
                <template #help>
                    <span class="text-xs">字数上限为900字，使用标点符号控制停顿</span>
                </template>
            </UFormGroup>
            <UFormGroup id="__driver_speed__" label="语速调节" name="speed" required>
                <div class="flex items-center gap-2">
                    <URange v-model="formData.speed" :step="0.1" :min="0.1" :max="2" />
                    <span class="min-w-10 text-end">{{ formData.speed }}x</span>
                </div>
                <template #help>
                    <span class="text-xs">范围在0.1x～2x，值越大语速越快，1x为默认语速</span>
                </template>
            </UFormGroup>
        </div>

        <div id="__driver_create__" class="p-6">
            <ClientOnly>
                <p class="center">
                    <UCheckbox
                        v-model="isConfirmAgreement"
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
            </ClientOnly>
            <p class="py-2 text-xs center">
                预计消耗
                <span class="mx-0.5 text-sm font-bold text-primary">{{ costPower }}</span>
                {{ useAppStore().siteConfig?.unit.power }}
            </p>

            <UButton
                :disabled="!isConfirmAgreement"
                :loading="btnStatus"
                class="w-full"
                type="submit"
                :ui="{ rounded: 'rounded-full' }"
                block
                size="lg"
            >
                <span>{{ btnStatus ? '创建任务中' : '开始合成' }}</span>
            </UButton>
        </div>
        <VoiceSelect ref="VoiceSelectRef" @select="voiceSelectHandle" />
    </UForm>
</template>

<script lang="ts" setup>
import { type AnyObject, object, type ObjectSchema, string } from 'yup';

import UTextarea from '#ui/components/forms/Textarea.vue';
import { apiPostCloneVoiceCreate } from '~/api/audio';
import { apiGetVoicePowerConfig } from '~/api/power';
import squareDefaultImg from '~/assets/images/1_1_default.png';
import VoiceSelect from '~/components/voice-select.vue';
import { siteConfig } from '~/config/system';

const { start: driverStart, state: driverState } = useDriver(
    [
        {
            element: '#__driver_select_voice__',
            popover: {
                title: '选择音色',
                description: '点击唤起音色选择起选择一个你想要的音色',
            },
        },
        {
            element: '#__driver_title__',
            popover: {
                title: '声音标题',
                description: '填写一个便于查询的标题',
            },
        },
        {
            element: '#__driver_content__',
            popover: {
                title: '内容文本',
                description: '输入你想要合成的内容，最多900个字',
            },
        },
        {
            element: '#__driver_speed__',
            popover: {
                title: '语速调节',
                description: '值越大语速越快，反之值越小语速越慢，1x为默认语速',
            },
        },
        {
            element: '#__driver_create__',
            popover: {
                title: '开始合成',
                description: '点击开始合成声音，任务创建完成后将自动刷新列表',
            },
        },
    ],
    {
        cache: {
            key: '__voice_synthesis__',
            enable: true,
        },
    }
);

const VoiceSelectRef = getComponentExpose(VoiceSelect);
const textareaRef = getComponentExpose(UTextarea);

const btnStatus = ref<boolean>(false);
const isConfirmAgreement = ref<boolean>(false);
const inputName = ref<'voice_id' | 'timbre_name'>('voice_id');

const emit = defineEmits(['refresh']);

const schema = ref(
    object({
        title: string().required('请填写声音标题'),
        content: string().required('请填写内容文本'),
    })
);

const voiceSelected = ref<VoiceSelectItem>();

const initFormData: CreateCloneVoice = {
    voice_id: '',
    timbre_name: '',
    cover: '',
    title: '',
    content: '',
    speed: 1,
    remark: '',
    model: 'V1',
};
const formData = reactive<CreateCloneVoice>(useCloned(initFormData).cloned.value);

const route = useRoute();

const powerConfig = ref<VoiceSynthesisPowerConfig>();
const costPower = ref<number>(0);
const isComposing = ref<boolean>(false);

const voiceSelectHandle = (item: VoiceSelectItem) => {
    voiceSelected.value = item;
    if (item.type === 'local') {
        inputName.value = 'timbre_name';
        schema.value = object({
            timbre_name: string().required('请选择音色'),
            title: string().required('请填写声音标题'),
            content: string().required('请填写内容文本'),
        });
        formData.voice_id = '';
        formData.timbre_name = item.name;
        formData.model = 'V2';
    } else {
        inputName.value = 'voice_id';
        schema.value = object({
            voice_id: string().required('请选择音色'),
            title: string().required('请填写声音标题'),
            content: string().required('请填写内容文本'),
        });
        formData.voice_id = item.id;
        formData.timbre_name = '';
        formData.model = 'V1';
    }
};

const calculateCost = async () => {
    await nextTick();
    const wordCount = formData.content?.length || 0;
    if (formData.content && formData.content?.length > 900) {
        formData.content = formData.content?.slice(0, 900);
        useMessage().warn('内容最多为900字');
    }
    if (powerConfig.value) {
        if (formData.content?.length === 0) return (costPower.value = 0);
        if (isComposing.value) return;
        // 每5个字算1次费用，超出的部分不足5个字也按5个字算
        const fullUnits = Math.ceil(wordCount / powerConfig.value.voice_words);
        costPower.value = fullUnits * powerConfig.value.voice_power;
    }
};

async function onSubmit() {
    btnStatus.value = true;
    try {
        await useRefreshUser(async () => {
            await apiPostCloneVoiceCreate(formData);
        });
        resetForm(formData, initFormData);
        voiceSelected.value = undefined;
        emit('refresh');
        btnStatus.value = false;
    } catch (error) {
        btnStatus.value = false;
    }
}

onMounted(async () => {
    if (route.query.voice_data) {
        const urlData = JSON.parse(
            await CryptoUtil.decrypt(route.query.voice_data as string, siteConfig.appSrcret)
        );
        voiceSelectHandle(urlData);
    } else {
        schema.value = object({
            voice_id: string().required('请选择音色'),
            title: string().required('请填写声音标题'),
            content: string().required('请填写内容文本'),
        });
    }

    powerConfig.value = await apiGetVoicePowerConfig();

    const textarea = textareaRef.value?.textarea;
    if (textarea) {
        textarea.addEventListener('compositionstart', handleCompositionStart);
        textarea.addEventListener('compositionend', handleCompositionEnd);
    }
    driverStart();
});

const handleCompositionStart = (): void => {
    isComposing.value = true;
};

// 拼音输入结束
const handleCompositionEnd = (): void => {
    isComposing.value = false;
    // 拼音输入结束后手动触发计算
    calculateCost();
};

// 在组件卸载时移除事件监听
onBeforeUnmount(() => {
    const textarea = textareaRef.value?.textarea;
    if (textarea) {
        textarea.removeEventListener('compositionstart', handleCompositionStart);
        textarea.removeEventListener('compositionend', handleCompositionEnd);
    }
});
</script>
