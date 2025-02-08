<template>
    <div class="flex items-center justify-between p-4">
        <h1 class="text-xl font-bold">页面导航</h1>
        <UButton
            color="gray"
            variant="ghost"
            size="sm"
            :ui="{
                icon: {
                    size: {
                        sm: 'w-5 h-5',
                    },
                },
            }"
            icon="tabler:x"
            square
            padded
            @click="emit('close')"
        />
    </div>
    <div class="flex-1 px-2">
        <UAccordion :items="siteNavigationList" multiple>
            <template #default="{ item, open }: { item: MenuItem; open: boolean }">
                <UButton color="gray" variant="ghost" :ui="{ padding: { sm: 'p-3' } }">
                    <div
                        class="rounded-full bg-background-soft p-1 center"
                        :class="
                            isActive(item.path) ? 'bg-primary/5 text-primary' : 'text-foreground/80'
                        "
                    >
                        <UIcon :name="item.icon" class="size-3.5" />
                    </div>
                    <span
                        class="truncate"
                        :class="isActive(item.path) ? 'text-primary' : 'text-foreground/80'"
                    >
                        {{ item.label }}
                    </span>

                    <template #trailing>
                        <UIcon
                            name="tabler:chevron-right"
                            class="ms-auto h-4 w-4 transform transition-transform duration-200"
                            :class="[open && 'rotate-90']"
                        />
                    </template>
                </UButton>
            </template>
            <template #item="{ item }: { item: MenuItem }">
                <div class="flex flex-col gap-3">
                    <div
                        v-for="listItem in item.list"
                        :key="listItem.type"
                        class="cursor-pointer truncate pl-6 pr-3 hover:text-foreground active:text-primary"
                        :class="
                            listItem.path === $route.path ? 'text-primary' : 'text-foreground/80'
                        "
                        @click="
                            () => {
                                emit('close');
                                navigateTo(listItem.path);
                            }
                        "
                    >
                        {{ listItem.title }}
                    </div>
                </div>
            </template>
        </UAccordion>
    </div>
    <div class="flex justify-between p-4">
        <ThemeToggle />
    </div>
</template>

<script setup lang="ts">
import { siteNavigationList } from '~/config/navigation';

const emit = defineEmits<{
    close: [];
}>();
</script>
