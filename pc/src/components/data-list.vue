<template>
    <div v-if="!state.pedding && state.count === 0" class="flex-1 py-8 center">
        <ProException :type="emptyType" :text="emptyText" :size="emptySize" />
    </div>
    <div
        v-if="state.count > 0"
        class="flex w-full flex-col"
        :class="[{ 'flex-1 overflow-y-auto': scroll }, containerClass]"
    >
        <div
            :id="componentId"
            v-load="state.pedding && listMode === 'page' && showLoadingIcon"
            :class="listClass"
        >
            <slot />
        </div>
    </div>
    <div v-if="listMode === 'page' && state.count > 0" :class="paginationClass">
        <ProPagination v-model="paginationState" class="mt-4" @change="pageChange" />
    </div>
    <div v-if="listMode === 'load'">
        <div
            v-if="loadMode === 'auto' && !state.isCompleted"
            ref="loadMoreTrigger"
            class="pointer-events-none w-full"
            :class="tiggerAreaHeight"
        />
        <slot v-if="!state.isCompleted" name="loadTrigger">
            <div class="py-2 center">
                <UButton
                    :variant="state.pedding ? 'ghost' : 'soft'"
                    :color="state.pedding ? 'gray' : 'primary'"
                    size="xs"
                    :loading="state.pedding"
                    @click="getData"
                >
                    {{ state.pedding ? '正在加载' : '加载更多' }}
                </UButton>
            </div>
        </slot>
        <slot v-if="state.isCompleted" name="loaded">
            <p class="py-6 text-center text-xs text-foreground/70">没有更多了</p>
        </slot>
    </div>
</template>

<script lang="ts" setup>
import { useIntersectionObserver } from '@vueuse/core';

interface Props {
    state: ListState;
    mode?: 'load' | 'page' | undefined;
    loadMode?: 'auto' | 'manual';
    tiggerHeight?: number;
    class?: string;
    containerClass?: string;
    scroll?: boolean;
    emptyText?: string;
    emptyType?: emptyType;
    emptySize?: number;
    paginationClass?: string;
}

const props = withDefaults(defineProps<Props>(), {
    loadMode: 'auto',
    mode: undefined,
    tiggerHeight: 50,
    class: '',
    containerClass: '',
    scroll: true,
    emptyText: '空空如也',
    emptyType: 'empty',
    emptySize: 160,
    paginationClass: '',
});

const componentId = useId();
const showLoadingIcon = ref<boolean>(true);
const tiggerAreaHeight = computed(() => `h-[${props.tiggerHeight}px]`);
const initial = ref<boolean>(false);
const listMode = ref<'load' | 'page'>('page');
const loadMoreTrigger = ref<HTMLElement>();

const listClass = computed(() => {
    return props.class;
});

const emit = defineEmits<{
    'update:modelValue': [value: ListState];
    refresh: [];
    resetPage: [];
    resetParams: [];
    getList: [value: any[]];
}>();

const paginationState = computed({
    get: () => {
        return props.state;
    },
    set: (val) => {
        emit('update:modelValue', val);
    },
});

const pageChange = async () => {
    document.getElementById(componentId)?.scroll({ top: 0, behavior: 'smooth' });
    await getData();
};

const getData = async () => {
    initial.value = true;
};

onMounted(async () => {
    listMode.value = props.mode !== undefined ? props.mode : isMobile() ? 'load' : 'page';

    getData();
    if (props.loadMode === 'auto') {
        useIntersectionObserver(
            loadMoreTrigger,
            async ([{ isIntersecting }]) => {
                if (isIntersecting) {
                    if (initial.value) {
                        getData();
                    }
                }
            },
            {
                threshold: 0.8, // 触发加载区域可见度
            }
        );
    }
});

defineExpose({});
</script>

<style lang="scss" scoped></style>
