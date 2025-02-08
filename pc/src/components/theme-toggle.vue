<template>
    <ColorScheme>
        <UButton
            class="relative"
            color="gray"
            square
            variant="ghost"
            @click="() => ($colorMode.preference = $colorMode.value === 'dark' ? 'light' : 'dark')"
        >
            <UIcon
                name="tabler:sun-high"
                class="h-5 w-5 rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0"
            />
            <UIcon
                name="tabler:moon"
                class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 rotate-90 scale-0 text-xl transition-all dark:rotate-0 dark:scale-100"
            />
        </UButton>
    </ColorScheme>
</template>

<script lang="ts" setup></script>

<style lang="scss" scoped></style>
