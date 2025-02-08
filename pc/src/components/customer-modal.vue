<template>
    <ProModal v-model="controlStore.customerModal" width="sm:max-w-xs" title="人工客服">
        <div
            v-if="controlStore.customerConfig"
            class="flex flex-col items-center justify-center gap-3"
        >
            <img
                v-if="controlStore.customerConfig?.manual_kf.qr_code"
                :src="controlStore.customerConfig?.manual_kf.qr_code"
                class="size-44 rounded-md bg-background-soft"
                alt="客服二维码"
            />
            <p v-if="controlStore.customerConfig?.manual_kf.title.status == 1">
                {{ controlStore.customerConfig?.manual_kf.title.value || '--' }}
            </p>

            <p
                v-if="controlStore.customerConfig?.manual_kf.service_time.status == 1"
                class="text-sm text-foreground/70"
            >
                服务时间：{{ controlStore.customerConfig?.manual_kf.service_time.value || '--' }}
            </p>

            <p
                v-if="controlStore.customerConfig?.manual_kf.phone.status == 1"
                class="flex items-center text-sm text-foreground/70"
            >
                服务热线：{{ controlStore.customerConfig?.manual_kf.phone.value || '--' }}
                <UIcon
                    name="tabler:copy"
                    class="ml-0.5 cursor-pointer"
                    @click="
                        useCopy(
                            controlStore.customerConfig?.manual_kf.phone.value,
                            '已复制服务热线至剪切板'
                        )
                    "
                />
            </p>
        </div>
        <div v-else class="flex flex-col items-center justify-center gap-2">
            <USkeleton class="size-44" />
            <USkeleton class="h-4 w-44" />
            <USkeleton class="h-4 w-44" />
        </div>
    </ProModal>
</template>

<script lang="ts" setup>
const controlStore = useControlsStore();
</script>

<style lang="scss" scoped></style>
