<template>
    <section v-if="$route.meta.layout === 'full-screen'" class="h-screen w-full">
        <main
            id="__nuxt_content__"
            class="flex size-full flex-col overflow-y-auto overflow-x-hidden pb-[env(safe-area-inset-bottom)]"
        >
            <NuxtLayout name="full-screen" />
        </main>
    </section>
    <section v-else class="h-screen w-full">
        <Navbar />
        <SideBar />

        <main
            id="__nuxt_content__"
            class="flex size-full flex-col overflow-y-auto overflow-x-hidden pt-[50px] transition-[padding] md:pb-2 md:pl-20 md:pr-2 md:pt-2"
            :class="
                inTabbar($route.path)
                    ? 'pb-[calc(64px_+_env(safe-area-inset-bottom))]'
                    : 'pb-[env(safe-area-inset-bottom)]'
            "
        >
            <NuxtLayout :name="$route.meta.layout || 'content'" />
        </main>

        <Tabbar />
    </section>
</template>

<script lang="ts" setup>
import Navbar from '~/components/layout/navbar.vue';
import SideBar from '~/components/layout/sidebar.vue';
import Tabbar from '~/components/layout/tabbar.vue';
</script>

<style lang="scss" scoped></style>
