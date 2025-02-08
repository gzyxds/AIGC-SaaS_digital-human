<template>
    <div id="__pro-list__" class="relative">
        <slot />
        <div
            v-if="auto && !state.isCompleted"
            ref="loadMoreTrigger"
            class="absolute bottom-0 left-0 z-10 w-full"
            :class="tiggerAreaHeight"
        />
        <slot v-if="!auto && !state.isCompleted" name="tigger">
            <div class="py-2 center">
                <UButton
                    :variant="loadingMore ? 'ghost' : 'soft'"
                    :color="loadingMore ? 'gray' : 'primary'"
                    size="xs"
                    :loading="loadingMore"
                    @click="loadMoreHandle"
                >
                    {{ loadingMore ? '正在加载' : '加载更多' }}
                </UButton>
            </div>
        </slot>
        <slot v-if="state.isCompleted" name="loaded">
            <p class="py-6 text-center text-xs text-foreground/70">没有更多了</p>
        </slot>
    </div>
</template>

<script setup lang="ts">
import { useIntersectionObserver } from '@vueuse/core';

const props = withDefaults(
    defineProps<{
        loadMore?: (...args: any[]) => Promise<any>;
        loadMoreText?: string;
        auto?: boolean;
        tiggerHeight?: number;
        state: ListState;
    }>(),
    {
        loadMoreText: '加载更多',
        loadMore: () => Promise.resolve(),
        auto: false,
        tiggerHeight: 50,
    }
);

const loadingMore = ref<boolean>(false);

const loadMoreTrigger = ref<HTMLElement | null>(null);

const tiggerAreaHeight = computed(() => `h-[${props.tiggerHeight}px]`);

const loadMoreHandle = async () => {
    try {
        loadingMore.value = true;
        await props.loadMore();
        loadingMore.value = false;
    } catch (error) {
        console.error(error);
        loadingMore.value = false;
    }
};

onMounted(() => {
    if (props.auto) {
        useIntersectionObserver(
            loadMoreTrigger,
            async ([{ isIntersecting }]) => {
                if (isIntersecting) {
                    loadMoreHandle();
                }
            },
            {
                threshold: 0.01, // 可见度
            }
        );
    }
});
</script>

<style lang="scss" scoped></style>
