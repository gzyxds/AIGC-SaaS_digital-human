<template>
    <PageContainer :scroll="true" breadcrumb="帮助中心" :padding="true" :background="true">
        <div v-load="state.pedding">
            <LoadList :state="state" :load-more="() => getList({ mode: 'load' })">
                <div
                    class="grid flex-1 grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5"
                >
                    <NuxtLink
                        v-for="item in list"
                        :key="item.id"
                        class="flex flex-col"
                        :to="`/help/${item.id}`"
                    >
                        <div
                            class="mt-2 overflow-hidden rounded-lg border border-border/20 bg-background-soft"
                        >
                            <AspectRatio :src="item.image" />
                        </div>
                        <h1 class="mt-2 truncate py-2 font-medium">{{ item.title }}</h1>
                        <p class="line-clamp-2 min-h-8 text-xs leading-5 text-foreground/60">
                            {{ item.desc }}
                        </p>
                        <p class="mt-2 flex items-center justify-between text-foreground/60">
                            <span class="flex items-center gap-0.5">
                                <UIcon name="tabler:clock-hour-3" size="12" />
                                <span class="text-xs">{{ item.create_time }}</span>
                            </span>
                            <span class="flex items-center gap-0.5">
                                <UIcon name="tabler:eye" size="16" />
                                <span class="text-xs">{{ item.click }}</span>
                            </span>
                        </p>
                    </NuxtLink>
                </div>
            </LoadList>
        </div>
    </PageContainer>
</template>

<script lang="ts" setup>
import { apiGetArticleList } from '~/api/article';

useHead({
    title: '帮助中心',
});

const { getList, list, state } = apiGetArticleList();

onMounted(() => {
    getList({ mode: 'load' });
});
</script>

<style lang="scss" scoped></style>
