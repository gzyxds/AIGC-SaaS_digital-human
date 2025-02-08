<template>
    <div>
        <div class="cursor-pointer" @click="isOpen = true">
            <slot name="trigger" />
        </div>

        <UModal
            v-model="isOpen"
            :ui="{ container: 'items-center', width }"
            :appear="appear"
            :fullscreen="fullscreen"
            :prevent-close="preventClose"
            :overlay="overlay"
            :transition="transition"
            @close="
                () => {
                    emit('close');
                }
            "
        >
            <div :id="contentId" class="relative flex flex-col gap-4 p-4 sm:p-7">
                <div class="flex items-center justify-between pr-8">
                    <slot name="title">
                        <h1 class="text-lg font-medium md:text-xl">{{ title || 'Title' }}</h1>
                    </slot>
                    <UButton
                        class="absolute right-4 top-4"
                        icon="tabler:x"
                        color="gray"
                        :ui="{
                            icon: { size: { sm: 'h-5 w-5' } },
                            square: { sm: 'p-1' },
                        }"
                        :disabled="disabledClose"
                        size="sm"
                        variant="ghost"
                        @click="close"
                    />
                </div>
                <div>
                    <slot />
                </div>
            </div>
        </UModal>
    </div>
</template>

<script setup lang="ts">
const props = withDefaults(
    defineProps<{
        title?: string;
        transition?: boolean;
        fullscreen?: boolean;
        preventClose?: boolean;
        overlay?: boolean;
        appear?: boolean;
        modelValue?: boolean;
        disabledClose?: boolean;
        contentId?: string;
        width?:
            | 'xs'
            | 'sm'
            | 'md'
            | 'lg'
            | 'xl'
            | '2xl'
            | '3xl'
            | '4xl'
            | '5xl'
            | '6xl'
            | '7xl'
            | string;
    }>(),
    {
        title: 'Title',
        transition: true,
        fullscreen: false,
        preventClose: false,
        overlay: true,
        appear: true,
        disabledClose: false,
        modelValue: false,
        width: 'sm:max-w-sm',
        contentId: '',
    }
);

const emit = defineEmits<{
    'update:modelValue': [value: boolean];
    close: [];
}>();

const isOpen = useVModel(props, 'modelValue', emit);

const close = () => {
    if (props.disabledClose) return;
    isOpen.value = false;
    emit('close');
};

defineExpose({ close });
</script>
