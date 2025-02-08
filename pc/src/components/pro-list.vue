<template>
    <div v-if="!state.pedding && list.length === 0" class="flex-1 py-8 center">
        <ProException :type="emptyType" :text="emptyText" :size="emptySize" />
    </div>
    <div v-if="state.pedding && list.length === 0 && !initial" class="gap-2 py-8 center">
        <UIcon name="tabler:loader-2" size="18" class="animate-spin" />
        <span class="text-sm">加载中</span>
    </div>
    <div
        v-else
        v-load="state.pedding && listMode === 'page' && showLoadingIcon"
        class="flex w-full flex-col"
        :class="[{ 'flex-1 overflow-y-auto': scroll }, containerClass]"
    >
        <ScrollArea v-if="scroll && listMode === 'page'">
            <div :id="componentId" :class="listClass">
                <slot
                    v-for="(item, index) in list"
                    :key="JSON.stringify(item) + index"
                    :item="item"
                    :index="index"
                />
            </div>
        </ScrollArea>
        <div v-else :id="componentId" :class="listClass">
            <slot
                v-for="(item, index) in list"
                :key="JSON.stringify(item) + index"
                :item="item"
                :index="index"
            />
        </div>
        <div v-if="listMode === 'page' && list.length > 0" :class="paginationClass">
            <ProPagination v-model="state" class="mt-4" @change="pageChange" />
        </div>
        <div v-if="listMode === 'load'">
            <div
                v-if="loadMode === 'auto' && !state.isCompleted"
                ref="loadMoreTrigger"
                class="pointer-events-none w-full"
                :class="tiggerAreaHeight"
            />
            <slot v-if="loadMode === 'manual' && !state.isCompleted" name="loadTrigger">
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
            <slot v-if="state.isCompleted && state.count > 0" name="loaded">
                <p class="py-6 text-center text-xs text-foreground/70">没有更多了</p>
            </slot>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { useIntersectionObserver } from '@vueuse/core';

import type { ListGetOptions } from '~/composables/useRequest';

interface ListRequestRes<T = any> {
    list: globalThis.Ref<any[]>;
    state: ListState;
    getList: (getOpts?: ListGetOptions) => Promise<ListResponse<T>>;
    refresh: (refreshOpts?: ListRefreshOptions) => Promise<ListResponse<T>>;
    resetPage: (immediate?: boolean) => void;
    resetParams: (immediate?: boolean) => void;
}

type ListInstance = () => ListRequestRes;

interface Props {
    requestInstance: ListInstance;
    mode?: 'load' | 'page' | undefined;
    loadMode?: 'auto' | 'manual';
    tiggerHeight?: number;
    class?: string;
    containerClass?: string;
    scroll?: boolean;
    emptyText?: string;
    emptyType?: emptyType;
    emptySize?: number;
    polling?: boolean | ((list: any[]) => boolean) | undefined;
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
    polling: undefined,
    paginationClass: '',
});

const componentId = useId();
const isPolling = ref<boolean>(false);
const showLoadingIcon = ref<boolean>(true);
const tiggerAreaHeight = computed(() => `h-[${props.tiggerHeight}px]`);
const initial = ref<boolean>(false);
const listMode = ref<'load' | 'page'>('page');
const loadMoreTrigger = ref<HTMLElement>();

const listClass = computed(() => {
    return props.class;
});

const emit = defineEmits<{
    'update:state': [value: ListState];
    'update:list': [value: any[]];
    refresh: [];
    resetPage: [];
    resetParams: [];
    getList: [value: any[]];
}>();

const {
    getList: _getList,
    list,
    refresh: listRefresh,
    resetPage,
    resetParams,
    state,
} = props.requestInstance();

const pageChange = async () => {
    document.getElementById(componentId)?.scroll({ top: 0, behavior: 'smooth' });
    await getData();
    emit('getList', list.value);
};

const refresh = (refreshOpts?: ListRefreshOptions) => {
    listRefresh(refreshOpts);
    if (props.polling) {
        startPolling();
    }
};

const getList = async (getOpts?: ListGetOptions) => {
    await _getList(getOpts);
    if (props.polling) {
        startPolling();
    }
};

const getData = async () => {
    showLoadingIcon.value = true;
    await _getList({ mode: listMode.value });
    if (props.polling) {
        startPolling();
    }
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

const startPolling = () => {
    if (isPolling.value) return;
    isPolling.value = true;
    start();
};

const { start, clear } = usePollingTask(
    () => {
        showLoadingIcon.value = false;
        _getList({ mode: listMode.value, polling: true });
    },
    {
        stopCondition:
            typeof props.polling === 'function' && props.polling
                ? () => (props.polling as (list: any[]) => boolean)(list.value)
                : props.polling,
        onEnded: () => {
            isPolling.value = false;
            showLoadingIcon.value = true;
        },
    }
);

onBeforeUnmount(() => {
    clear();
});

defineExpose({
    list,
    getList,
    refresh,
    resetPage,
    resetParams,
    state,
    startPolling,
});
</script>

<style lang="scss" scoped></style>
