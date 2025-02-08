<template>
    <div style="position: relative" :style="ratioStr" class="w-full overflow-hidden">
        <div style="position: absolute; inset: 0px; user-select: none">
            <img
                v-if="type === 'image' && !isError"
                :id="useId()"
                ref="imgRef"
                :src="src || defaultImg"
                :alt="src || alt"
                :draggable="draggable"
                class="w-full"
                style="object-fit: contain; width: 100%; height: 100%"
            />

            <div
                v-else-if="type === 'image' && isError"
                style="object-fit: contain; width: 100%; height: 100%"
                class="relative flex-col center"
            >
                <img
                    :id="useId()"
                    :src="ExceptionNoImage"
                    alt="图片加载失败，当前为异常图片"
                    class="relative h-full"
                />
                <span class="absolute inset-0 text-9xl font-bold text-primary/15 center">404</span>
            </div>

            <video
                v-else
                :id="useId()"
                :src="src"
                :controls="controls"
                style="object-fit: contain; width: 100%; height: 100%"
            />
        </div>
    </div>
</template>

<script lang="ts" setup>
import ExceptionNoImage from '~/assets/icons/exception/no-image.svg';
import defaultImg from '~/assets/images/16_9_default.png';

const imgRef = ref<HTMLImageElement>();

const props = withDefaults(
    defineProps<{
        ratio?: [number, number];
        alt?: string;
        type?: 'image' | 'video';
        src: string;
        controls?: boolean;
        draggable?: boolean;
        fallback?: string;
    }>(),
    {
        alt: '',
        controls: false,
        type: 'image',
        src: '',
        ratio: () => [16, 9],
        draggable: true,
        fallback: '',
    }
);

const isError = ref<boolean>(false);

const ratioStr = computed(() => {
    const [width, paddingBottom] = props.ratio;
    return `width: 100%;padding-bottom: ${(paddingBottom / width) * 100}%;`;
});

const handleError = () => {
    if (isError.value) return;
    isError.value = true;
    imgRef.value?.removeEventListener('error', handleError);
};

onMounted(async () => {
    await nextTick();
    imgRef.value?.addEventListener('error', handleError);
});
</script>

<style lang="scss" scoped></style>
