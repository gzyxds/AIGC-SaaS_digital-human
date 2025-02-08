<template>
    <div class="inline-flex flex-col items-center justify-center">
        <img :src="iconMap[type]" :width="size" :alt="type" />
        <p class="text-sm text-foreground/60">{{ text }}</p>
    </div>
</template>

<script lang="ts" setup>
import Exception403 from '~/assets/icons/exception/403.svg';
import Exception404 from '~/assets/icons/exception/404.svg';
import Exception500 from '~/assets/icons/exception/500.svg';
import ExceptionEmpty from '~/assets/icons/exception/empty.svg';
import ExceptionNoFile from '~/assets/icons/exception/no-file.svg';
import ExceptionNoImage from '~/assets/icons/exception/no-image.svg';
import ExceptionNoNotice from '~/assets/icons/exception/no-notice.svg';
import ExceptionNoSetting from '~/assets/icons/exception/no-setting.svg';
import ExceptionNosignal from '~/assets/icons/exception/no-signal.svg';
import ExceptionSlow from '~/assets/icons/exception/slow.svg';

withDefaults(
    defineProps<{
        type?: emptyType;
        size?: number;
        text?: string;
    }>(),
    {
        type: 'empty',
        size: 160,
        text: '空空如也',
    }
);

const iconMap: Record<emptyType, string> = {
    empty: ExceptionEmpty,
    403: Exception403,
    404: Exception404,
    500: Exception500,
    'no-file': ExceptionNoFile,
    'no-image': ExceptionNoImage,
    'no-notice': ExceptionNoNotice,
    'no-setting': ExceptionNoSetting,
    'no-signal': ExceptionNosignal,
    slow: ExceptionSlow,
};
</script>

<style lang="scss" scoped></style>
