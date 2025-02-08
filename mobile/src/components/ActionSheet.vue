<script lang="ts" setup>
import type { UniPopupInstance } from '@uni-helper/uni-ui-types';

const props = withDefaults(
    defineProps<{
        actions: {
            title: string;
            desc?: string;
            type?: keyof typeof colorType;
            show?: boolean; // 是否显示该选项
            click?: () => void;
        }[];
    }>(),
    {
        actions: () => [],
    }
);

const emit = defineEmits<{
    confirm: [];
    close: [];
    change: [];
    open: [];
}>();

const uniPopupRef = ref<UniPopupInstance>();
const colorType = {
    default: 'text-white',
    primary: 'text-primary',
    danger: 'text-danger',
    warning: 'text-warning',
};

function close() {
    uniPopupRef.value?.close?.();
    emit('close');
}

function open() {
    uniPopupRef.value?.open?.();
    emit('open');
}

function getTypeColor(type: keyof typeof colorType = 'default') {
    return colorType[type];
}

defineExpose({
    open,
    close,
});

onMounted(() => {});
</script>

<template>
    <uni-popup ref="uniPopupRef" type="bottom" :safe-area="false" z-index="99">
        <view class="action-sheet overflow-hidden rounded-t-[var(--ui-radius)] bg-background-muted">
            <view flex="~ col" gap-px>
                <view
                    v-for="(item, index) in props.actions"
                    v-show="item.show !== false"
                    :key="index"
                    p="4"
                    text="center"
                    flex="~ items-center justify-center col"
                    gap="2"
                    class="bg-background active:bg-background-bold"
                    @click="
                        () => {
                            item.click?.();
                            close();
                        }
                    "
                >
                    <text :class="getTypeColor(item.type)">
                        {{ item.title }}
                    </text>
                    <text v-if="item.desc" text="xs gray/70">
                        {{ item.desc }}
                    </text>
                </view>
            </view>
            <view v-if="props.actions.length > 0" h-2 />
            <view
                p="4"
                text="center"
                class="bg-background active:bg-background-bold"
                @click="close"
            >
                <text>取消</text>
            </view>
        </view>
    </uni-popup>
</template>

<style lang="scss" scoped>
.action-sheet {
    // padding-bottom: env(safe-area-inset-bottom);
    /* #ifdef H5 */
    padding-bottom: calc(env(safe-area-inset-bottom) + 50px);
    /* #endif */
}
</style>
