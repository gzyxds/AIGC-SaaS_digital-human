<template>
    <div class="flex size-full overflow-hidden bg-background">
        <div class="fixed bottom-1/2 left-0 md:hidden">
            <UButton
                icon="tabler:list"
                :ui="{ rounded: 'rounded-s-none' }"
                @click="isOpen = true"
            />
        </div>
        <USlideover v-model="isOpen" side="left" :ui="{ base: '!w-fit flex-0 max-w-fit' }">
            <div
                class="relative flex h-full w-52 flex-col border-r border-border/40 bg-background-soft py-6"
            >
                <div class="absolute -right-7 bottom-1/2 md:hidden">
                    <UButton
                        icon="tabler:text-wrap"
                        :ui="{ rounded: 'rounded-s-none' }"
                        @click="isOpen = false"
                    />
                </div>
                <div class="flex-1 overflow-hidden">
                    <ScrollArea class="px-4">
                        <UAccordion :items="items" multiple>
                            <template
                                #default="{
                                    item,
                                    open,
                                }: {
                                    item: AgreementMenuItem;
                                    open: boolean;
                                }"
                            >
                                <UButton
                                    color="gray"
                                    variant="ghost"
                                    :ui="{ padding: { sm: 'p-3' } }"
                                >
                                    <span class="truncate">{{ item.label }}</span>

                                    <template #trailing>
                                        <UIcon
                                            name="tabler:chevron-right"
                                            class="ms-auto h-4 w-4 transform transition-transform duration-200"
                                            :class="[open && 'rotate-90']"
                                        />
                                    </template>
                                </UButton>
                            </template>
                            <template #item="{ item }: { item: AgreementMenuItem }">
                                <div v-if="menuLoading" class="h-full space-y-3">
                                    <USkeleton class="h-6 w-full" />
                                    <USkeleton class="h-6 w-full" />
                                    <USkeleton class="h-6 w-full" />
                                </div>
                                <div v-else class="flex flex-col gap-3">
                                    <div
                                        v-for="listItem in item.list"
                                        :key="listItem.type"
                                        class="cursor-pointer truncate pl-6 pr-3 hover:text-foreground active:text-primary"
                                        :class="{
                                            'text-primary hover:!text-primary':
                                                listItem.type === content?.type,
                                        }"
                                        @click="
                                            () => {
                                                changeContent(item.id, listItem);
                                                isOpen = false;
                                            }
                                        "
                                    >
                                        {{ listItem.title }}
                                    </div>
                                </div>
                            </template>
                        </UAccordion>
                    </ScrollArea>
                </div>
                <div class="mt-auto px-4 py-2">
                    <ThemeToggle />
                </div>
            </div>
        </USlideover>
        <div
            class="h-full w-0 overflow-x-hidden border-r border-border/40 bg-background-soft transition-[width] md:w-44 lg:w-52"
        >
            <div class="gap-4py-6 flex h-full w-full min-w-44 flex-col">
                <div class="flex-1 overflow-hidden">
                    <ScrollArea class="px-4">
                        <UAccordion :items="items" multiple>
                            <template
                                #default="{
                                    item,
                                    open,
                                }: {
                                    item: AgreementMenuItem;
                                    open: boolean;
                                }"
                            >
                                <UButton
                                    color="gray"
                                    variant="ghost"
                                    :ui="{ padding: { sm: 'p-3' } }"
                                >
                                    <span class="truncate">{{ item.label }}</span>

                                    <template #trailing>
                                        <UIcon
                                            name="tabler:chevron-right"
                                            class="ms-auto h-4 w-4 transform transition-transform duration-200"
                                            :class="[open && 'rotate-90']"
                                        />
                                    </template>
                                </UButton>
                            </template>
                            <template #item="{ item }: { item: AgreementMenuItem }">
                                <div v-if="menuLoading" class="h-full space-y-3">
                                    <USkeleton class="h-6 w-full" />
                                    <USkeleton class="h-6 w-full" />
                                    <USkeleton class="h-6 w-full" />
                                </div>
                                <div v-else class="flex flex-col gap-3">
                                    <div
                                        v-for="listItem in item.list"
                                        :key="listItem.type"
                                        class="cursor-pointer truncate pl-6 pr-3 hover:text-foreground active:text-primary"
                                        :class="{
                                            'text-primary hover:!text-primary':
                                                listItem.type === content?.type,
                                        }"
                                        @click="changeContent(item.id, listItem)"
                                    >
                                        {{ listItem.title }}
                                    </div>
                                </div>
                            </template>
                        </UAccordion>
                    </ScrollArea>
                </div>
                <div class="mt-auto px-4 py-2">
                    <ThemeToggle />
                </div>
            </div>
        </div>
        <div id="__read_area__" class="flex-1 overflow-y-auto">
            <div class="mx-auto flex w-full flex-col gap-4 p-6 md:max-w-prose">
                <div v-if="contentLoading" class="space-y-4">
                    <USkeleton class="h-6 w-[250px]" />
                    <USkeleton class="h-4 w-[200px]" />
                    <div class="!mt-8 space-y-6">
                        <USkeleton v-for="item in 15" :key="item" class="h-4 w-full" />
                    </div>
                </div>
                <div v-else class="space-y-4">
                    <h1 class="text-2xl font-medium">{{ content?.title }}</h1>
                    <p class="text-xs text-foreground/60">
                        更新时间：{{ content?.update_time || '--' }}
                    </p>
                    <div v-dompurify-html="content?.content" class="prose dark:prose-invert" />
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { apiGetArticleDetail, apiGetArticleList } from '~/api/article';
import { apiGetAllPolicy } from '~/api/common';

useHead({
    title: '政策协议',
});

definePageMeta({
    layout: 'full-screen',
    auth: false,
});

interface AgreementMenuItem {
    id: string;
    label: string;
    defaultOpen?: boolean;
    list: AgreementItem[];
}

const route = useRoute();

const isOpen = ref<boolean>(false);
const content = ref<AgreementItem | null>(null);
const menuLoading = ref<boolean>(true);
const contentLoading = ref<boolean>(true);

const { getList } = apiGetArticleList();

const items = ref<AgreementMenuItem[]>([
    {
        id: 'agreement',
        label: '政策协议',
        defaultOpen: true,
        list: [],
    },
    {
        id: 'help',
        label: '帮助中心',
        defaultOpen: true,
        list: [],
    },
    {
        id: 'disclaimer',
        label: '免责声明',
        defaultOpen: true,
        list: [],
    },
]);

const changeContent = async (id: string, item: AgreementItem) => {
    contentLoading.value = true;
    content.value = item;
    if (id === 'help') {
        const detail = await apiGetArticleDetail({ id: item.type });
        if (content.value) {
            content.value.content = detail.content;
        }
    }
    await nextTick();
    document.getElementById('__read_area__')?.scrollTo({ top: 0, behavior: 'smooth' });
    contentLoading.value = false;
};

onMounted(async () => {
    const agreement = items.value.find((item) => item.id === 'agreement');
    const help = items.value.find((item) => item.id === 'help');
    const disclaimer = items.value.find((item) => item.id === 'disclaimer');

    const activeType = route.query.type as string;
    const activeItem = route.query.item as string;

    if (agreement && disclaimer) {
        const agreementRes = await apiGetAllPolicy();
        agreement.list = agreementRes.filter((item) => item.type !== 'disclaimer');
        if (!activeType) {
            content.value = agreement.list[0];
        }

        if (disclaimer) {
            disclaimer.list = agreementRes.filter((item) => item.type === 'disclaimer');
        }
    }

    if (help) {
        const res = await getList();
        const arr = res.lists.map((item) => {
            return {
                title: item.title,
                type: String(item.id),
                content: '',
                update_time: item.update_time,
            };
        });
        help.list = arr;
    }

    if (activeType) {
        switch (activeType) {
            case 'agreement':
                if (agreement) {
                    if (activeItem) {
                        const i = agreement?.list.find((item) => item.type === activeItem);
                        if (i) {
                            changeContent('agreement', i);
                        }
                    } else {
                        if (agreement?.list[0]) {
                            changeContent('agreement', agreement?.list[0]);
                        }
                    }
                }
                break;
            case 'help':
                if (help?.list[0]) {
                    changeContent('help', help?.list[0]);
                }
                break;
            case 'disclaimer':
                if (disclaimer?.list[0]) {
                    changeContent('disclaimer', disclaimer?.list[0]);
                }
                break;
            default:
                break;
        }
    }

    menuLoading.value = false;
    contentLoading.value = false;
});
</script>

<style lang="scss" scoped></style>
