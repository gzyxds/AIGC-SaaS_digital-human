<template>
    <Teleport v-if="init" :to="selector">
        <slot />
    </Teleport>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{ selector: string }>(), {
    selector: '',
});
const init = ref<boolean>(false);
onMounted(async () => {
    let count = 0;
    const findElement = async () => {
        if (count > 10) throw new Error('Cannot find target element.');
        count++;
        await nextTick();
        if (getElementBySelector(props.selector)) {
            init.value = true;
        } else {
            findElement();
        }
    };

    findElement();
});

/**
 * 判断传入的字符串是 id、class 还是标签名，并返回对应的元素
 * @param selector - 传入的选择器字符串
 */
const getElementBySelector = (selector: string): HTMLElement | null => {
    if (selector.startsWith('#')) {
        return document.getElementById(selector.slice(1));
    } else if (selector.startsWith('.')) {
        return document.querySelector(selector);
    } else {
        return document.querySelector(selector);
    }
};
</script>
