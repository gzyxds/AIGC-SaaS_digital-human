<template>
    <div class="logo" @click="handleClick">
        <image-contain
            v-if="settingStore.mode === 'dark'"
            :width="szie"
            :height="szie"
            :src="config.web_logo_dark"
        />
        <image-contain v-else :width="szie" :height="szie" :src="config.web_logo_light" />
        <transition name="title-width">
            <div
                v-show="showTitle"
                class="logo-title overflow-hidden whitespace-nowrap"
                :class="{ 'text-white': theme == ThemeEnum.DARK }"
                :style="{ left: `${szie + 16}px` }"
            >
                <overflow-tooltip
                    :content="title || config.web_name"
                    :teleported="true"
                    placement="bottom"
                    overflo-type="unset"
                >
                </overflow-tooltip>
            </div>
        </transition>
    </div>
</template>

<script setup lang="ts">
import { ThemeEnum } from '@/enums/appEnums'
import useAppStore from '@/stores/modules/app'
import useSettingStore from '@/stores/modules/setting'

defineProps({
    szie: { type: Number, default: 34 },
    title: { type: String },
    theme: { type: String },
    showTitle: { type: Boolean, default: true }
})
const appStore = useAppStore()
const settingStore = useSettingStore()
const config = computed(() => appStore.config)
const router = useRouter()

const handleClick = () => {
    router.push('/')
}
</script>
<style lang="scss" scoped>
.logo {
    height: var(--navbar-height);
    overflow: hidden;
    cursor: pointer;
    user-select: none;
    @apply flex items-center p-2 relative;
    .logo-title {
        width: 70%;
        position: absolute;
        color: var(--sub-theme);
        @apply text-xl;
    }

    .title-width-enter-active {
        opacity: 0;
        transition: all 0.3s ease-out;
    }

    .title-width-leave-active {
        transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
    }

    .title-width-enter-from,
    .title-width-leave-to {
        width: 0;
        opacity: 0;
    }
}

.dark {
    .logo {
        .logo-title {
            color: var(--el-menu-text-color);
        }
    }
}
</style>
