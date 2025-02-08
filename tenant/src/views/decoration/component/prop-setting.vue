<template>
    <div class="pages-setting h-full bg-body rounded">
        <el-scrollbar height="100%">
            <div class="p-4">
                <div
                    class="title flex items-center before:w-[3px] before:h-[14px] before:block before:bg-primary before:mr-2"
                >
                    {{ title }}
                </div>
                <div class="pt-4">
                    <slot />
                </div>
            </div>
        </el-scrollbar>
    </div>
</template>
<script lang="ts" setup>
const props = defineProps({
    title: {
        type: String,
        default: ''
    }
})
</script>

<style lang="scss" scoped>
.pages-setting {
    width: 300px;
    @apply xl:w-[400px];
}
</style>
