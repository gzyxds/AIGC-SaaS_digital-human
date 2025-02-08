<template>
    <ProModal
        v-model="isOpen"
        width="sm:max-w-xs"
        :title="confirmInfo?.title || '操作确认'"
        :disabled-close="btnLoading"
        @close="cancelHandle"
    >
        <slot>
            <div>{{ confirmInfo?.message || '您确认要这样做吗?' }}</div>
        </slot>
        <div class="mt-4 flex justify-end gap-4">
            <UButton
                size="md"
                :ui="{ padding: { md: 'px-6' } }"
                color="white"
                :disabled="btnLoading"
                @click="cancelHandle"
                >{{ confirmInfo?.cancelText || '取消' }}</UButton
            >
            <UButton
                size="md"
                color="red"
                :ui="{ padding: { md: 'px-6' } }"
                :loading="btnLoading"
                @click="confirmHandle"
                >{{ confirmInfo?.confirmText || '确认' }}</UButton
            >
        </div>
    </ProModal>
</template>

<script setup lang="ts">
import type colors from '#ui-colors';

interface ConfirmInfo {
    title: string;
    message: string;
    confirm?: () => void;
    cancel?: () => void;
    confirmText?: string;
    cancelText?: string;
    confirmColor?: (typeof colors)[number] | undefined;
}

const isOpen = ref<boolean>(false);
const confirmInfo = ref<ConfirmInfo>();
const btnLoading = ref<boolean>(false);

const open = (info: ConfirmInfo) => {
    isOpen.value = true;
    confirmInfo.value = info;
};

const cancelHandle = () => {
    isOpen.value = false;
    confirmInfo.value?.cancel?.();
};
const confirmHandle = async () => {
    try {
        btnLoading.value = true;
        await confirmInfo.value?.confirm?.();
        btnLoading.value = false;
        isOpen.value = false;
    } catch (error) {
        btnLoading.value = false;
    }
};

defineExpose({ open });
</script>
