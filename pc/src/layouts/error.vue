<template>
    <div>
        <h1>{{ error.statusCode }}</h1>
        <NuxtLink to="/">Go back home</NuxtLink>
    </div>
</template>

<script setup lang="ts">
import type { NuxtError } from '#app';

withDefaults(
    defineProps<{
        error: NuxtError;
    }>(),
    {}
);
</script>
