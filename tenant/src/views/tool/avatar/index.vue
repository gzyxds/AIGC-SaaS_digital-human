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
                <el-table-column label="任务号" prop="task_id" min-width="120" />

                <el-table-column label="用户信息" min-width="180">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <el-avatar class="flex-shrink-0" :src="row.userAvatar" :size="50" />
                            <span class="ml-2">{{ row.userName }}</span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="合成通道" prop="mode" min-width="120" />

                <el-table-column label="作品名称" min-width="180">
                    <template #header>
                        <div class="flex items-center">
                            <span class="mr-2">合成结果</span>
                            <el-tooltip
                                class="box-item"
                                effect="dark"
                                content="点击作品名称播放视频预览"
                                placement="top-start"
                            >
                                <el-icon><VideoPlay /></el-icon>
                            </el-tooltip>
                        </div>
                    </template>
                    <template #default="{ row }">
                        <el-button
                            type="primary"
                            link
                            @click="handlePre(row.resultFile)"
                            :disabled="!row.resultFile"
                            class="overflow-hidden"
                        >
                            <span class="truncate max-w-40">{{ row?.title || '暂无标题' }}</span>
                        </el-button>
                    </template>
                </el-table-column>
                <el-table-column label="" min-width="180">
                    <template #header>
                        <div class="flex items-center">
                            <span class="mr-2">形象视频</span>
                            <el-tooltip
                                class="box-item"
                                effect="dark"
                                content="点击作品名称播放视频预览"
                                placement="top-start"
                            >
                                <el-icon><VideoPlay /></el-icon>
                            </el-tooltip>
                        </div>
                    </template>
                    <template #default="{ row }">
                        <el-button
                            type="primary"
                            link
                            @click="handlePre(row.video?.video_url)"
                            :disabled="!row.video?.video_url"
                        >
                            <span class="mr-2">{{ row.video?.name || '暂无名称' }}</span>
                        </el-button>
                    </template>
                </el-table-column>
                <el-table-column label="" min-width="180">
                    <template #header>
                        <div class="flex items-center">
                            <span class="mr-2">音频信息</span>
                            <el-tooltip
                                class="box-item"
                                effect="dark"
                                content="点击作品名称播放音频预览"
                                placement="top-start"
                            >
                                <el-icon><VideoPlay /></el-icon>
                            </el-tooltip>
                        </div>
                    </template>
                    <template #default="{ row }">
                        <el-button
                            type="primary"
                            link
                            @click="handlevoicePre(row.voice?.voice_url)"
                            :disabled="!row.voice?.voice_url"
                        >
                            <span class="mr-2">{{ row.voice?.title || '暂无标题' }}</span>
                        </el-button>
                    </template>
                </el-table-column>

                <el-table-column
                    label="失败原因"
                    prop="fail_reason"
                    min-width="150"
                    show-overflow-tooltip
                />
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
                                >{{ ['合成中', '已完成', '合成失败'][Number(row.status)] }}</span
                            >
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="创建时间" prop="create_time" min-width="120" />

                <el-table-column label="操作" width="120" fixed="right">
                    <template #default="{ row }">
                        <!-- <el-button
                            v-perms="['avatar.aiAvatarRecord/detail']"
                            type="primary"
                            link
                            @click="editRef?.openHandle(row.id)"
                        >
                            查看结果
                        </el-button> -->
                        <el-button
                            v-perms="['avatar.aiAvatarRecord/delete']"
                            type="danger"
                            link
                            @click="handledel(row.id)"
                        >
                            删除
                        </el-button>
                    </template>
                </el-table-column>
                <el-table-column label="消耗算力值" prop="cost_power" min-width="120" />
                <el-table-column label="消耗时间(秒)" prop="cost_time" min-width="120">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <span class="ml-2">{{
                                formatSecondsToMinutes(row.cost_time || 0)
                            }}</span>
                        </div>
                    </template>
                </el-table-column>
            </el-table>
            <div class="flex justify-end mt-4">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
    </div>
    <el-dialog v-model="dialogVisible" width="740px" title="视频预览" destroy-on-close>
        <!-- <video-player ref="playerRef" :src="url" width="100%" height="450px" /> -->
        <video width="100%" height="450px" controls style="height: 450px">
            <source :src="url" type="video/mp4" />
            您的浏览器不支持 video 标签。
        </video>
    </el-dialog>
    <el-dialog v-model="dialogvoiceVisible" width="740px" title="音频预览" destroy-on-close>
        <VueAudioPlayer
            :audio-list="[voiceUrl]"
            v-if="dialogvoiceVisible"
            theme-color="#409EFF"
        ></VueAudioPlayer>
    </el-dialog>
    <Edit ref="editRef" @refresh="getLists()" />
</template>
<script lang="ts" setup name="avatarLists">
import VueAudioPlayer from '@liripeng/vue-audio-player'

import { delAvatar, getAvatarRecord } from '@/api/tool'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'
import { useComponentRef } from '@/utils/getExposeType'
import { formatSecondsToMinutes } from '@/utils/util'

import Edit from './edit.vue'

const queryParams = reactive({
    user_info: '',
    status: '',
    start_time: '',
    end_time: ''
})
const url = ref('')
const voiceUrl = ref('')
const dialogVisible = ref(false)
const dialogvoiceVisible = ref(false)

const editRef = useComponentRef(Edit)
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: getAvatarRecord,
    params: queryParams
})
const handledel = async (id: number) => {
    await feedback.confirm('确定要删除？')
    await delAvatar({ id })
    getLists()
}
const handlePre = (val: string) => {
    dialogVisible.value = true
    url.value = val
}
const handlevoicePre = (val: string) => {
    dialogvoiceVisible.value = true
    voiceUrl.value = val
}
onActivated(() => {
    getLists()
})

getLists()
</script>
