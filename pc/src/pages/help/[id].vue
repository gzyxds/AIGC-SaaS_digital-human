<template>
    <PageContainer
        :scroll="true"
        :breadcrumb="detail?.title"
        :padding="true"
        :background="true"
        :pc-back="true"
        prose
    >
        <div
            v-load="isPendding"
            class="flex flex-1 flex-col gap-4 overflow-y-auto py-4 pb-12 md:pb-8"
        >
            <div class="flex flex-col center">
                <div class="mb-8 flex w-full max-w-prose flex-col">
                    <p class="mt-2 flex items-center gap-4 text-foreground/60">
                        <span class="flex items-center gap-0.5">
                            <UIcon name="tabler:clock-hour-3" size="12" />
                            <span class="text-xs">{{ detail?.create_time }}</span>
                        </span>
                        <span class="flex items-center gap-0.5">
                            <UIcon name="tabler:eye" size="16" />
                            <span class="text-xs">{{ detail?.click }}</span>
                        </span>
                    </p>
                </div>
                <div v-dompurify-html="detail?.content" class="prose dark:prose-invert"></div>
            </div>
        </div>
    </PageContainer>
</template>

<script lang="ts" setup>
import { apiGetArticleDetail, apiGetArticleList } from '~/api/article';

useHead({
    title: '帮助中心',
});

const route = useRoute();
const isPendding = ref<boolean>(true);
const detail = ref<ArticleDetail>();

onMounted(async () => {
    detail.value = await apiGetArticleDetail({ id: route.params.id as string });
    document.title = detail.value.title;
    route.meta.title = detail.value.title;
    isPendding.value = false;
});
</script>

<style lang="scss" scoped></style>
