<template>
    <div class="setting-drawer">
        <el-drawer
            v-model="showSetting"
            append-to-body
            direction="rtl"
            size="250px"
            title="主题设置"
        >
            <div class="h-full flex flex-col">
                <div class="setting-item mb-5 flex justify-between items-center">
                    <span class="text-tx-secondary">开启黑暗模式</span>
                    <div>
                        <el-switch :model-value="isDark" @change="toggleDark" />
                    </div>
                </div>
                <div class="setting-item mb-5 flex justify-between items-center">
                    <span class="text-tx-secondary">开启多页签栏</span>
                    <div>
                        <el-switch
                            v-model="openMultipleTabs"
                            :active-value="true"
                            :inactive-value="false"
                        />
                    </div>
                </div>
                <div class="setting-item mb-5 flex justify-between items-center">
                    <span class="text-tx-secondary">只展开一个一级菜单</span>
                    <div>
                        <el-switch
                            v-model="isUniqueOpened"
                            :active-value="true"
                            :inactive-value="false"
                        />
                    </div>
                </div>
                <div class="setting-item mb-5 flex justify-between items-center">
                    <div class="text-tx-secondary flex-none mr-3">菜单栏宽度</div>
                    <div>
                        <el-input-number v-model="sideWidth" :min="180" :max="250" />
                    </div>
                </div>
                <div class="setting-item mb-5 flex justify-between items-center">
                    <div class="text-tx-secondary flex-none mr-3">显示LOGO</div>
                    <div>
                        <el-switch
                            v-model="showLogo"
                            :active-value="true"
                            :inactive-value="false"
                        />
                    </div>
                </div>
                <div class="setting-item mb-5 flex justify-between items-center">
                    <div class="text-tx-secondary flex-none mr-3">显示面包屑</div>
                    <div>
                        <el-switch
                            v-model="showCrumb"
                            :active-value="true"
                            :inactive-value="false"
                        />
                    </div>
                </div>
                <div class="setting-item flex justify-center items-center mt-auto">
                    <el-button @click="resetTheme" class="w-full">重置主题</el-button>
                </div>
            </div>
        </el-drawer>
    </div>
</template>

<script setup lang="ts">
import { useDark, useToggle } from '@vueuse/core'

import useSettingStore from '@/stores/modules/setting'

const settingStore = useSettingStore()

const openMultipleTabs = computed({
    get() {
        return settingStore.openMultipleTabs
    },
    set(value) {
        settingStore.setSetting({
            key: 'openMultipleTabs',
            value
        })
    }
})

const isUniqueOpened = computed({
    get() {
        return settingStore.isUniqueOpened
    },
    set(value) {
        settingStore.setSetting({
            key: 'isUniqueOpened',
            value
        })
    }
})

const sideWidth = computed({
    get() {
        return settingStore.sideWidth
    },
    set(value) {
        settingStore.setSetting({
            key: 'sideWidth',
            value
        })
    }
})
const showSetting = computed({
    get() {
        return settingStore.showDrawer
    },
    set(value) {
        settingStore.setSetting({
            key: 'showDrawer',
            value
        })
    }
})

const showLogo = computed({
    get() {
        return settingStore.showLogo
    },
    set(value) {
        settingStore.setSetting({
            key: 'showLogo',
            value
        })
    }
})

const showCrumb = computed({
    get() {
        return settingStore.showCrumb
    },
    set(value) {
        settingStore.setSetting({
            key: 'showCrumb',
            value
        })
    }
})

const isDark = useDark()
const themeChange = () => {
    settingStore.setTheme(isDark.value)
}

const toggleDark = () => {
    useToggle(isDark)()
    themeChange()
}
const resetTheme = () => {
    isDark.value = false
    settingStore.resetTheme()
    themeChange()
}
</script>

<style lang="scss" scoped>
.icon-select {
    @apply absolute left-1/2 top-1/2;
    transform: translate(-50%, -50%);
}
</style>
