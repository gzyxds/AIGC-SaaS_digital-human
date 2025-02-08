<template>
    <div ref="PageSearchRef">
        <UInput
            v-model="inputValue"
            class="order-2 hidden md:block"
            icon="tabler:search"
            :placeholder="placeholder"
            @keyup.enter="emit('search', inputValue)"
            @change="
                () => {
                    inputValue === '' ? emit('search', inputValue) : null;
                }
            "
        />
        <UButton
            class="md:hidden"
            :class="{ 'show fixed right-14 top-[7px] z-50': isFixed }"
            icon="tabler:search"
            size="md"
            :variant="isFixed ? 'ghost' : 'solid'"
            :color="isFixed ? 'gray' : 'primary'"
            :ui="{ icon: { size: { md: isFixed ? 'size-5' : 'size-4' } } }"
            @click="isOpen = true"
        />
        <UModal v-model="isOpen" :ui="{ container: 'items-start' }">
            <div class="flex gap-2 p-4">
                <UInput
                    v-model="inputValue"
                    class="flex-1"
                    size="lg"
                    icon="i-heroicons:magnifying-glass-20-solid"
                    :placeholder="placeholder"
                    @keyup.enter="
                        () => {
                            isOpen = false;
                            emit('search', inputValue);
                        }
                    "
                    @change="inputValue === '' ? emit('search', inputValue) : null"
                />
                <UButton
                    label="搜索"
                    size="lg"
                    @click="
                        () => {
                            isOpen = false;
                            emit('search', inputValue);
                        }
                    "
                />
            </div>
        </UModal>
    </div>
</template>

<script lang="ts" setup>
import { ResponsiveEnum } from '~/enums/variableEnum';

const props = withDefaults(
    defineProps<{
        placeholder?: string;
        fixed?: boolean;
        modelValue?: string;
    }>(),
    {
        placeholder: '搜索...',
        fixed: true,
        modelValue: '',
    }
);

const emit = defineEmits<{
    search: [value: string];
    'update:modelValue': [value: string];
}>();

const isOpen = ref<boolean>(false);

const inputValue = computed({
    get: () => props.modelValue,
    set: (value) => {
        emit('update:modelValue', value);
    },
});

const PageSearchRef = ref<HTMLElement>();

const { width } = useWindowSize();
const { top } = useElementBounding(PageSearchRef);

const isMobileView = computed(() => {
    if (isClient()) return width.value < ResponsiveEnum.MD;
    return false;
});

const inited = ref<boolean>(false);

const isFixed = computed(() => {
    if (!props.fixed) return false;
    if (!isMobileView.value) return false;
    if (inited.value) {
        if (top.value <= 15) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
});

onMounted(() => {
    inited.value = true;
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
