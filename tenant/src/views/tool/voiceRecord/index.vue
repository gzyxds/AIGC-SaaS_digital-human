<template>
    <div>
        <el-card class="!border-none" shadow="never">
            <el-form ref="formRef" class="mb-[-16px]" :model="queryParams" :inline="true">
                <el-form-item label="用户信息">
                    <el-input
                        class="w-[280px]"
                        v-model="queryParams.user_info"
                        placeholder="请输入用户账号/昵称/手机号/sn"
                        clearable
                        @keyup.enter="resetPage"
                    />
                </el-form-item>
                <el-form-item label="创建时间">
                    <daterange-picker
                        v-model:startTime="queryParams.start_time"
                        v-model:endTime="queryParams.end_time"
                    />
                </el-form-item>
                <el-form-item label="状态" class="w-[280px]">
                    <el-select v-model="queryParams.status" clearable>
                        <el-option label="合成中" :value="0" />
                        <el-option label="已完成" :value="1" />
                        <el-option label="已失败" :value="2" />
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                    <!-- <export-data
                        class="ml-2.5"
                        :fetch-fun="getAvatarRecord"
                        :params="queryParams"
                        :page-size="pager.size"
                    /> -->
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none mt-4" shadow="never">
            <el-table size="large" v-loading="pager.loading" :data="pager.lists">
                <el-table-column label="任务ID" prop="task_id" min-width="120" />

                <el-table-column label="用户信息" min-width="180">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <el-avatar class="flex-shrink-0" :src="row.userAvatar" :size="50" />
                            <span class="ml-2">{{ row.userName }}</span>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="声音标题" prop="title" min-width="120">
                    <template #default="{ row }">
                        <span class="line-clamp-2">
                            {{ row.title }}
                        </span>
                    </template>
                </el-table-column>
                <el-table-column label="所用音色" prop="title" min-width="120">
                    <template #default="{ row }">
                        <el-button
                            v-if="row.voice"
                            type="primary"
                            :disabled="!row.voice.voice_url"
                            link
                            @click="handlePre(row.voice.voice_url)"
                        >
                            {{ row.voice.name }}{{ row.voice.id ? '' : '(系统)' }}
                        </el-button>
                        <span v-else class="px-1.5 text-info">暂无音色信息</span>
                    </template>
                </el-table-column>
                <el-table-column label="内容（字）" prop="content" min-width="120">
                    <template #default="{ row }">
                        {{ row?.content?.length || 0 }}
                    </template>
                </el-table-column>

                <el-table-column label="消耗算力" prop="cost_power" min-width="120" />
                <!-- <el-table-column label="音色名称" prop="" min-width="120" /> -->

                <!-- <el-table-column label="消耗时间(秒)" prop="cost_time" min-width="120" /> -->
                <el-table-column label="状态" min-width="100">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <span
                                class="ml-2"
                                :class="{
                                    'text-info': row.status === '0',
                                    'text-success': row.status === '1',
                                    'text-error': row.status === '2'
                                }"
                                >{{ ['合成中', '已完成', '已失败'][Number(row.status)] }}</span
                            >
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="创建时间" prop="create_time" min-width="120" />
                <el-table-column label="内容文本" prop="content" min-width="120">
                    <template #default="{ row }">
                        <el-button type="primary" link @click="handleContent(row.content)">
                            查看详情</el-button
                        >

                        <!-- <el-tooltip
                            class="box-item"
                            effect="dark"
                            :content="row.content"
                            placement="top-start"
                        >
                            <div class="text-content">
                                {{ row.content }}
                            </div>
                        </el-tooltip> -->
                    </template>
                </el-table-column>
                <el-table-column label="操作" width="120" fixed="right">
                    <template #default="{ row }">
                        <el-button
                            v-if="row.resultFile"
                            v-perms="['avatar.aiAvatarRecord/detail']"
                            type="primary"
                            link
                            @click="handlePre(row.resultFile)"
                        >
                            查看结果
                        </el-button>
                        <!-- <el-button
                            v-perms="['avatar.aiAvatarRecord/detail']"
                            type="primary"
                            link
                            @click="editRef?.openHandle(row.id)"
                        >
                            查看结果
                        </el-button> -->
                        <!-- <el-button
                            v-perms="['avatar.aiAvatarRecord/delete']"
                            type="danger"
                            link
                            @click="handledel(row.id)"
                        >
                            删除
                        </el-button> -->
                    </template>
                </el-table-column>
            </el-table>
            <div class="flex justify-end mt-4">
                <pagination
                    v-model="pager"
                    @change="
                        ($) => {
                            getLists()
                            console.log(pager)
                        }
                    "
                />
            </div>
        </el-card>
    </div>
    <el-dialog v-model="dialogVisible" width="740px" title="音频预览" destroy-on-close>
        <VueAudioPlayer
            :audio-list="[url]"
            v-if="dialogVisible"
            theme-color="#409EFF"
        ></VueAudioPlayer>
    </el-dialog>
    <el-dialog v-model="contentVisible" width="740px" title="内容详情" destroy-on-close>
        <div>
            {{ content }}
        </div>
    </el-dialog>
    <Edit ref="editRef" @refresh="getLists()" />
</template>
<script lang="ts" setup name="voiceRecordLists">
import VueAudioPlayer from '@liripeng/vue-audio-player'

import { delvoiceRecord, getvoiceRecord } from '@/api/tool'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'
import { useComponentRef } from '@/utils/getExposeType'

import Edit from './edit.vue'
const dialogVisible = ref(false)
const contentVisible = ref(false)
const content = ref('')
const queryParams = reactive({
    user_info: '',
    status: '',
    start_time: '',
    end_time: ''
})
const editRef = useComponentRef(Edit)
const url = reactive({
    src: ''
})
const handleContent = (val: string) => {
    content.value = val
    contentVisible.value = true
}
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: getvoiceRecord,
    params: queryParams
})
const handledel = async (id: number) => {
    await feedback.confirm('确定要删除？')
    await delvoiceRecord({ id })
    getLists()
}
const handlePre = (val: string) => {
    url.src = val

    dialogVisible.value = true
}
onActivated(() => {
    getLists()
})

getLists()
</script>
<style scoped lang="scss">
.text-content {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
