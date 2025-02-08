<template>
    <NuxtLink v-if="menu.path" :target="menu.target || '_self'" :to="menu.path">
        <slot>
            {{ menu.title }}
        </slot>
    </NuxtLink>
    <div v-else class="cursor-pointer" @click="menu.click?.()">
        <slot>
            {{ menu.title }}
        </slot>
    </div>
</template>

<script lang="ts" setup>
defineProps<{
    menu: MenuItem;
}>();
</script>

<style lang="scss" scoped></style>
