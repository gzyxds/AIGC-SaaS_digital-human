<template>
    <UModal
        v-model="isOpen"
        :ui="{ width: 'sm:max-w-3xl', container: 'items-center', base: 'overflow-hidden' }"
    >
        <div v-load="!detailData" class="min-h-96 p-4 md:p-6">
            <div class="mb-4 flex justify-between">
                <div>
                    <h1 class="text-xl font-medium">{{ detailData?.name }}</h1>
                    <p class="mt-2 gap-1 text-xs text-foreground/60 start">
                        <UIcon name="tabler:clock" />
                        <span>{{ detailData?.create_time }}</span>
                    </p>
                </div>

                <div>
                    <UButton
                        icon="tabler:x"
                        :ui="{
                            icon: { size: { sm: 'h-5 w-5' } },
                            square: { sm: 'p-1' },
                        }"
                        size="sm"
                        variant="ghost"
                        @click="isOpen = false"
                    />
                </div>
            </div>
            <Transition mode="out-in" name="component">
                <Detail
                    v-if="detailData && stepCurent === 0"
                    :data="detailData"
                    @step-change="stepCurent = 1"
                />
                <Generator
                    v-else-if="detailData && stepCurent === 1"
                    :data="detailData"
                    @step-change="stepCurent = 0"
                    @submit="
                        () => {
                            isOpen = false;
                            navigateTo('/avatar/works');
                        }
                    "
                />
            </Transition>
        </div>
    </UModal>
</template>

<script lang="ts" setup>
import { apiGetAvatarVideoDetail } from '~/api/avatar';

import Detail from './detail.vue';
import Generator from './generator.vue';

const isOpen = ref<boolean>(false);
const detailData = ref<AvatarVideoDetail>();
const stepCurent = ref<number>(0);
const show = async (data: AvatarVideoItem, step?: number) => {
    detailData.value = undefined;
    stepCurent.value = step || 0;
    isOpen.value = true;
    try {
        detailData.value = await apiGetAvatarVideoDetail({ id: data.id });
    } catch (error) {
        isOpen.value = false;
        useMessage().error(`获取详情失败：${JSON.stringify(error)}`);
    }
};

defineExpose({ show: show });
</script>

<style lang="scss" scoped></style>
