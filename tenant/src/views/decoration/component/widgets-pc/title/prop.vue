<template>
    <div>
        <el-form label-width="70px">
            <el-form-item label="主标题">
                <el-input v-model="propModel.title" />
            </el-form-item>

            <el-form-item label="副标题">
                <el-input v-model="propModel.subtitle" :rows="3" type="textarea" />
            </el-form-item>
        </el-form>
    </div>
</template>
<script lang="ts" setup>
import { useVModel } from '@vueuse/core'
import type { Prop } from './config'

const props = defineProps<{
    isShow: boolean
    prop: Prop
}>()
const emit = defineEmits<{
    (event: 'update:prop', value: Prop): void
}>()
const propModel = useVModel(props, 'prop', emit)
const isShowModel = useVModel(props, 'isShow', emit)
</script>

<style lang="scss" scoped></style>
