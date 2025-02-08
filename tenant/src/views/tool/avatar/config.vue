<template>
    <div class="">
        <el-card class="!border-none" shadow="never">
            <el-alert
                type="warning"
                title="温馨提示：数字分身合成通道必须开启至少一个，开启后需前往「AI设置-》key池管理」添加秘钥。开启多个时，由用户选择合成通道。"
                :closable="false"
                show-icon
            />
        </el-card>
        <el-card class="!border-none mt-4" shadow="never">
            <el-table size="large" :data="pager">
                <el-table-column label="合成通道" prop="mode" min-width="100">
                    <template #default="{ row }">
                        {{
                            {
                                1: '创客API-极速模式',
                                2: '创客API-高清模式',
                                3: '创客API-专业模式'
                            }[row.mode as number]
                        }}
                    </template>
                </el-table-column>
                <el-table-column label="自定义通道名称" prop="video_mode_title" min-width="100" />
                <el-table-column label="消耗算力（算力/分钟）" prop="video_power" min-width="100" />
                <el-table-column label="说明" min-width="100">
                    <template #default="{ row }">
                        {{
                            {
                                1: '创客API极速模式，合成速度快',
                                2: '创客API高清模式，合成效果好',
                                3: '创客API高清模式，合成效果好'
                            }[row.mode as number]
                        }}
                    </template>
                </el-table-column>
                <el-table-column label="状态" min-width="100">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <el-tag
                                type="success"
                                v-if="row.video_mode_status == 1 && row.disabled === 0"
                                >开启</el-tag
                            >
                            <el-tag type="danger" v-else>关闭</el-tag>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="操作" width="120" fixed="right">
                    <template #default="{ row }">
                        <el-button
                            v-if="row.disabled === 0"
                            type="primary"
                            link
                            @click="handleSetting(row)"
                            v-perms="['setting.power.powerConfig/setAvtarConfig']"
                        >
                            设置
                        </el-button>
                        <span v-else class="text-sm text-info"> 敬请期待 </span>
                    </template>
                </el-table-column>
            </el-table>
        </el-card>
        <edit-popup v-if="showEdit" ref="editRef" @success="getData" @close="showEdit = false" />
    </div>
</template>

<script lang="ts" setup name="avaterConfig">
import { getAvatarAllConfig } from '@/api/tool'

import EditPopup from './config_edit.vue'
const editRef = shallowRef<InstanceType<typeof EditPopup>>()
const showEdit = ref(false)

const pager = ref<any>([])
// 表单数据

const getData = async () => {
    pager.value = []
    const data = await getAvatarAllConfig()
    for (const key in data) {
        //@ts-ignore
        pager.value.push(data[key])
    }
}
const handleSetting = async (data: any) => {
    showEdit.value = true
    await nextTick()
    editRef.value?.open()
    editRef.value?.getDetail(data)
}

onMounted(() => {
    getData()
})
</script>

<style lang="scss" scoped></style>
