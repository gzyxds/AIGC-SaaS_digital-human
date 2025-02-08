<template>
    <div ref="NavbarBreadcrumbRef" class="flex min-h-5 items-center justify-between gap-4">
        <div
            class="flex max-w-[50%] flex-1 items-center"
            :class="isFixed ? 'show fixed left-4 top-[15px] z-50' : ''"
        >
            <slot>
                <div
                    v-if="back"
                    class="mr-1 flex-shrink-0 cursor-pointer rounded-md center hover:bg-background-soft hover:text-primary active:bg-primary/10 md:p-1"
                    @click="() => $router.back()"
                >
                    <UIcon class="text-xl md:text-2xl" name="tabler:arrow-left" />
                </div>
                <div
                    v-else-if="pcBack"
                    class="mr-1 hidden flex-shrink-0 cursor-pointer rounded-md hover:bg-background-soft hover:text-primary active:bg-primary/10 md:p-1 md:center"
                    @click="() => $router.back()"
                >
                    <UIcon class="text-xl md:text-2xl" name="tabler:arrow-left" />
                </div>

                <div
                    v-else-if="mobileBack"
                    class="mr-1 flex-shrink-0 cursor-pointer rounded-md center hover:bg-background-soft hover:text-primary active:bg-primary/10 md:hidden md:p-1"
                    @click="() => $router.back()"
                >
                    <UIcon class="text-xl md:text-2xl" name="tabler:arrow-left" />
                </div>
                <h1 class="truncate text-lg font-medium leading-none md:text-2xl">
                    {{ title }}
                </h1>
            </slot>
        </div>
        <div class="flex-shrink-0">
            <slot name="right" />
        </div>
    </div>
</template>

<script lang="ts" setup>
import { useElementBounding, useWindowSize } from '@vueuse/core';

import { ResponsiveEnum } from '~/enums/variableEnum';

const appStore = useAppStore();
const router = useRouter();
const route = useRoute();

withDefaults(
    defineProps<{
        back?: boolean;
        pcBack?: boolean;
        mobileBack?: boolean;
        title?: string;
    }>(),
    {
        back: true,
        title: '',
        pcBack: false,
        mobileBack: true,
    }
);

const NavbarBreadcrumbRef = ref<HTMLElement>();
const inited = ref<boolean>(false);

const { width } = useWindowSize();
const { top } = useElementBounding(NavbarBreadcrumbRef);

const isMobileView = computed(() => {
    if (isClient()) return width.value < ResponsiveEnum.MD;
    return false;
});

const isFixed = computed(() => {
    if (!isMobileView.value) return false;
    if (inited.value) {
        if (top.value < 15) {
            appStore.setShowNavbarTitle(true);
            return true;
        } else {
            appStore.setShowNavbarTitle(false);
            return false;
        }
    } else {
        appStore.setShowNavbarTitle(false);
        return false;
    }
});

onMounted(() => {
    inited.value = true;

    router.afterEach(() => {
        appStore.setShowNavbarTitle(false);
    });
});
</script>

<style lang="scss" scoped>
.show {
    animation: show 0.2s ease-in-out;
}

.fade {
    animation: fade 0.2s ease-in-out;
}

@keyframes show {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes fade {
    from {
        opacity: 1;
    }

    to {
        opacity: 0;
    }
}
</style>
