<template>
    <PageContainer :scroll="true" :breadcrumb="false" :padding="false" :background="true">
        <div v-if="initial" class="block border-b border-border/40 py-2 md:hidden">
            <UTabs
                :ui="{
                    container: 'hidden',
                    list: {
                        background: 'bg-white dark:bg-gray-800',
                        marker: {
                            background: 'bg-primary/10 dark:bg-gray-900',
                        },
                        tab: {
                            active: 'text-primary dark:text-white',
                        },
                    },
                }"
                :default-index="defaultIndex"
                :items="tabList"
                class="w-full p-4 py-0"
                @change="onChange"
            />
        </div>
        <div v-if="initial" class="flex size-full flex-1 overflow-hidden">
            <div
                class="size-full overflow-y-auto rounded-lg border-border/40 bg-background shadow-none ring-0 md:block md:w-80 md:rounded-none md:border-r"
                :class="{ hidden: activeIndex === 1 }"
            >
                <Options @refresh="refreshRecord" />
            </div>
            <div
                class="h-full flex-1 overflow-hidden rounded-xl md:flex"
                :class="{ 'hidden md:flex': activeIndex === 0 }"
            >
                <Records ref="recordsRef" />
            </div>
        </div>
    </PageContainer>
</template>

<script lang="ts" setup>
import Options from './components/options.vue';
import Records from './components/records.vue';

useHead({
    title: '声音合成',
});

const recordsRef = getComponentExpose(Records);
const route = useRoute();

const tabList = [
    {
        icon: 'tabler:tool',
        label: '合成器',
    },
    {
        icon: 'tabler:clock-record',
        label: '合成记录',
    },
];

const activeIndex = ref<number>(0);
const initial = ref<boolean>(false);
const refreshRecord = () => {
    onChange(1);
    recordsRef.value?.refresh();
};

const onChange = (index: number) => {
    activeIndex.value = index;
};

const defaultIndex = ref<number>(0);

onMounted(() => {
    const tab_index = route.query.tab_index;
    if (tab_index) {
        activeIndex.value = Number(tab_index);
        defaultIndex.value = Number(tab_index);
    }
    initial.value = true;
});
</script>

<style lang="scss" scoped></style>
