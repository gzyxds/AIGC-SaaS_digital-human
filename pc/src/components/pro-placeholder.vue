<template>
    <div
        class="overflow-hidden rounded-lg bg-gray-50/40 p-4 ring-1 ring-inset ring-gray-900/10 dark:bg-gray-900/40 dark:ring-white/10 md:rounded-xl lg:-m-4"
    >
        <div
            class="relative overflow-hidden rounded-lg border border-dashed border-gray-900/10 dark:border-white/10"
            :class="ratio"
        >
            <svg
                class="absolute inset-0 size-full stroke-gray-900/10 dark:stroke-white/10"
                fill="none"
            >
                <defs>
                    <pattern
                        id="pattern-5c1e4f0e-62d5-498b-8ff0-cf77bb448c8e"
                        x="0"
                        y="0"
                        width="10"
                        height="10"
                        patternUnits="userSpaceOnUse"
                    >
                        <path d="M-3 13 15-5M-5 5l18-18M-1 21 17 3"></path>
                    </pattern>
                </defs>
                <rect
                    stroke="none"
                    fill="url(#pattern-5c1e4f0e-62d5-498b-8ff0-cf77bb448c8e)"
                    width="100%"
                    height="100%"
                ></rect>
            </svg>
        </div>
    </div>
</template>

<script lang="ts" setup>
withDefaults(
    defineProps<{
        ratio?: string;
    }>(),
    {
        ratio: 'aspect-w-16 aspect-h-9',
    }
);
</script>

<style lang="scss" scoped></style>
