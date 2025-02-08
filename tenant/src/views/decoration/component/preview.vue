<template>
    <div class="pages-preview" :style="styles">
        <el-scrollbar>
            <div
                v-for="(widget, i) in pageData"
                :key="widget.id"
                class="relative widget-item"
                :class="{
                    'cursor-pointer': !widget?.disabled
                }"
                @click="handleClick(widget, i)"
            >
                <div
                    class="absolute w-full h-full z-[100] border-dashed widget-place flex items-center justify-center"
                    :class="{
                        select: i == index,
                        'border-[#dcdfe6] border-2': !widget?.disabled,
                        'is-hidden': !widget?.isShow
                    }"
                >
                    <div class="flex text-white text-[20px] items-center" v-if="!widget?.isShow">
                        <Icon name="el-icon-Hide" :size="30" />
                        <span class="ml-[8px]">已隐藏</span>
                    </div>
                </div>

                <slot :widget="widget" />
            </div>
        </el-scrollbar>
    </div>
</template>
<script lang="ts" setup>
import type { PropType } from 'vue'

const props = defineProps({
    pageData: {
        type: Array as PropType<any[]>,
        default: () => []
    },
    index: {
        type: Number,
        default: 0
    },
    width: {
        type: String,
        default: '100%'
    },
    height: {
        type: String,
        default: 'auto'
    }
})

const styles = computed(() => {
    return {
        width: props.width,
        height: props.height
    }
})
const emit = defineEmits<{
    (event: 'update:index', value: number): void
}>()

const handleClick = (widget: any, index: number) => {
    if (widget.disabled) return
    emit('update:index', index)
}
</script>

<style lang="scss" scoped>
.pages-preview {
    color: #333;
    .widget-place {
        &.select {
            border-width: 3px;
            @apply border-primary border-solid;
        }
        &.is-hidden {
            background: rgba(0, 0, 0, 0.5);
        }
    }
}
</style>
