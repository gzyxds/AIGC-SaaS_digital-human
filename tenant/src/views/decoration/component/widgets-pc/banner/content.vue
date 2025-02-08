<template>
    <div class="bg-center banner">
        <div class="w-full" style="height: 850px">
            <div class="flex justify-center items-center h-full">
                <!-- <el-image
                    fit="cover"
                    :src="getImageUrl(prop.data[0].image)"
                    class="flex-none w-[840px] h-[478px]"
                /> -->
                <decoration-img
                    width="1350"
                    height="750"
                    :src="resolveUrl(prop.data[0].image)"
                    fit="contain"
                />
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import useAppStore from '@/stores/modules/app'

import DecorationImg from '../../decoration-img.vue'
import type { Prop } from './config'

const { getImageUrl } = useAppStore()

function resolveUrl(url: string): string {
    if (url.startsWith('http://') || url.startsWith('https://')) {
        return url
    }
    return getImageUrl(url)
}

defineProps<{
    prop: Prop
}>()
</script>

<style lang="scss" scoped>
.banner {
    background-image: url(../../../image/banner.png);
    background-size: cover;
}
</style>
