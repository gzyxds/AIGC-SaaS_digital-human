<template>
    <div>
        <div class="flex-1">
            <draggable
                class="draggable"
                v-model="propModel.data"
                animation="300"
                handle=".drag-move"
                item-key="index"
            >
                <template v-slot:item="{ element: item, index }">
                    <del-wrap :key="index" @close="handleDelete(index)">
                        <div class="bg-fill-light p-4 mt-4 w-full drag-move cursor-move">
                            <div class="flex justify-center w-full">
                                <material-picker
                                    size="122px"
                                    v-model="item.image"
                                    upload-class="bg-body"
                                    :exclude-domain="false"
                                >
                                    <template #upload>
                                        <div class="w-[122px] h-[122px] banner-upload-btn">
                                            轮播图
                                        </div>
                                    </template>
                                </material-picker>
                            </div>
                        </div>
                    </del-wrap>
                </template>
            </draggable>
        </div>
        <div class="mt-4">
            <el-button class="w-full" type="primary" @click="handleAdd">添加图片</el-button>
        </div>
    </div>
</template>
<script lang="ts" setup>
import { useVModel } from '@vueuse/core'
import { cloneDeep } from 'lodash-es'
import Draggable from 'vuedraggable'

import feedback from '@/utils/feedback'

import type { Prop } from './config'

const props = defineProps<{
    isShow: boolean
    prop: Prop
}>()
const emit = defineEmits<{
    (event: 'update:prop', value: Prop): void
}>()

const handleAdd = () => {
    const content = cloneDeep(propModel.value)
    content.data.push({
        image: ''
    })
    emit('update:prop', content)
}
const handleDelete = (index: number) => {
    if (propModel.value.data?.length <= 1) {
        return feedback.msgError('最少保留一张图片')
    }
    const content = cloneDeep(propModel.value)
    content.data.splice(index, 1)
    emit('update:prop', content)
}
const propModel = useVModel(props, 'prop', emit)
</script>

<style lang="scss" scoped>
.banner-upload-btn {
    @apply text-tx-secondary box-border rounded border-br border-dashed border flex flex-col justify-center items-center;
}
</style>
