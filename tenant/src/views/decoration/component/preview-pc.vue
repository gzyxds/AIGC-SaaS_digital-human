<template>
    <div
        class="flex flex-col items-center p-4"
        ref="previewWrap"
        :style="[
            {
                minWidth: `${size.minWidth + 32}px`
            },
            theme
        ]"
    >
        <Preview v-model:index="indexModel" :style="styles" :page-data="pageData">
            <template #default="{ widget }">
                <component
                    :is="widgets[widget?.name]?.content"
                    :prop="widget.prop"
                    :key="widget.id"
                />
            </template>
        </Preview>
    </div>
</template>
<script lang="ts" setup>
import { useElementSize, useVModel } from '@vueuse/core'
import widgets from './widgets-pc'
import Preview from './preview.vue'
// import layout_bg from '../image/layout_bg.png'
import type { CSSProperties, PropType } from 'vue'
import { generateTheme } from '@/utils/theme'

const props = defineProps({
    pageData: {
        type: Array as PropType<any[]>,
        default: () => []
    },
    index: {
        type: Number,
        default: 0
    }
})
const emit = defineEmits<{
    (event: 'update:index', value: number): void
}>()
const indexModel = useVModel(props, 'index', emit)
const previewWrap = shallowRef<HTMLDivElement>()
const { width, height } = useElementSize(previewWrap)
const size = {
    width: 1600,
    height: 900,
    minWidth: 600,
    maxWidth: 1000
}
const styles = computed<CSSProperties>(() => {
    let calcWidth = width.value
    if (width.value < size.minWidth) {
        calcWidth = size.minWidth
    }
    if (width.value > size.maxWidth) {
        calcWidth = size.maxWidth
    }
    const scale = calcWidth / size.width
    return {
        width: `${size.width}px`,
        height: `${height.value * (1 / scale)}px`,
        transform: `scale(${scale})`,
        transformOrigin: 'center top',
        // backgroundImage: `url(${layout_bg})`,
        backgroundSize: 'cover'
    }
})

const theme = generateTheme(
    {
        primary: '#3C5EFD'
    },
    false
)
</script>
<style lang="scss" scoped></style>
