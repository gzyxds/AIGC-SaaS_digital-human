<template>
    <div class="bg-center title">
        <div class="w-full" style="height: 550px">
            <div class="flex justify-center items-center h-full w-full flex-col">
                <div class="font-medium text-[45px] mx-auto">{{ prop.title }}</div>
                <div class="text-[20px] mx-auto">{{ prop.subtitle }}</div>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import type { Prop } from './config'
import useAppStore from '@/stores/modules/app'
const props = defineProps<{
    prop: Prop
}>()
</script>

<style lang="scss" scoped>
.title {
    background-image: url(../../../image/title-bg.png);
    background-size: cover;
}
</style>
