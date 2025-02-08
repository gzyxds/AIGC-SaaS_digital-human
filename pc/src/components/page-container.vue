<template>
    <div
        class="flex flex-col"
        :class="[
            {
                'flex-1 md:overflow-y-auto': scroll,
            },
            contentBackground,
            contentRounded,
        ]"
    >
        <div
            v-if="breadcrumb !== false"
            ref="breadcrumbRef"
            class="p-4 pb-0 md:p-6 md:pb-2"
            :class="[
                {
                    'mx-auto w-full max-w-prose': prose,
                    'sticky top-0 z-10 bg-background !pb-2 md:!pb-2': breadcrumbFixed,
                    'lg:!px-0': prose,
                },
            ]"
        >
            <Breadcrumb
                :title="breadcrumbTitle"
                :back="breadcrumbBack !== false"
                :mobile-back="mobileBack"
                :pc-back="pcBack"
            >
                <slot name="breadcrumb" />
                <template #right>
                    <slot name="breadcrumb-right" />
                </template>
            </Breadcrumb>
        </div>

        <div :class="[{ 'flex flex-col md:flex-1 md:overflow-y-auto': scroll }, contentPadding]">
            <slot />
        </div>
    </div>
</template>

<script lang="ts" setup>
import Breadcrumb from '~/components/layout/breadcrumb.vue';
const breadcrumbRef = ref<HTMLElement>();
const route = useRoute();

const props = withDefaults(
    defineProps<{
        scroll?: boolean;
        forceScroll?: boolean;
        padding?: string | boolean;
        background?: string | boolean;
        rounded?: string | boolean;
        breadcrumb?: boolean | string;
        breadcrumbBack?: boolean;
        mobileBack?: boolean;
        pcBack?: boolean;
        breadcrumbFixed?: boolean;
        prose?: boolean;
    }>(),
    {
        scroll: false,
        forceScroll: false,
        padding: true,
        background: true,
        rounded: true,
        breadcrumb: true,
        breadcrumbBack: false,
        mobileBack: true,
        pcBack: false,
        breadcrumbFixed: false,
        prose: false,
    }
);

const contentPadding = computed(() => {
    if (props.padding === false) return '';
    if (props.padding === true || props.padding === undefined)
        return props.breadcrumbFixed ? 'md:p-6 p-4 md:pt-2 pt-2' : 'md:p-6 md:pt-2 p-4';

    return props.padding;
});

const contentBackground = computed(() => {
    if (props.background === false) return '';
    if (props.background === true || props.background === undefined) return 'bg-background';

    return props.background;
});

const contentRounded = computed(() => {
    if (props.rounded === false) return '';
    if (route.meta.layout === 'full-screen') return '';
    if (props.rounded === true || props.rounded === undefined) return 'md:rounded-xl';

    return props.rounded;
});

const breadcrumbTitle = computed(() => {
    if (typeof props.breadcrumb !== 'boolean' && props.breadcrumb !== undefined) {
        return props.breadcrumb;
    }

    return '';
});
</script>

<style lang="scss" scoped></style>
