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
                        <el-option label="训练中" :value="0" />
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
                <el-table-column label="任务号" prop="image_id" min-width="80" />

                <el-table-column label="用户信息" min-width="180">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <el-avatar class="flex-shrink-0" :src="row.userAvatar" :size="50" />
                            <span class="ml-2">{{ row.userName }}</span>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="形象名称" prop="name" min-width="120" />
                <el-table-column label="时长(秒)" prop="duration" min-width="120" />
                <el-table-column label="状态" min-width="100">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <span
                                class="ml-2"
                                :class="{
                                    'text-info': row.status == '0',
                                    'text-success': row.status == '1',
                                    'text-error': row.status == '2'
                                }"
                                >{{ ['训练中', '训练完成', '训练失败'][Number(row.status)] }}</span
                            >
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="创建时间" prop="create_time" min-width="120" />
                <el-table-column label="操作" min-width="120" fixed="right">
                    <template #default="{ row }">
                        <el-button
                            v-perms="['video.video/detail']"
                            type="primary"
                            link
                            @click="handlePre(row.fileUrl)"
                        >
                            查看视频
                        </el-button>
                        <el-button
                            v-perms="['video.video/delete']"
                            type="danger"
                            link
                            @click="handledel(row.id)"
                        >
                            删除
                        </el-button>
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
    <Edit ref="editRef" @refresh="getLists()" />
</template>
<script lang="ts" setup name="avatarLists">
import { delVideoRecord, getVideoRecord } from '@/api/tool'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'
import { useComponentRef } from '@/utils/getExposeType'

import Edit from './edit.vue'

const queryParams = reactive({
    user_info: '',
    status: '',
    start_time: '',
    end_time: ''
})
const editRef = useComponentRef(Edit)
const url = ref('')
const dialogVisible = ref(false)
const handlePre = (val: string) => {
    dialogVisible.value = true
    url.value = val
}
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: getVideoRecord,
    params: queryParams
})
const handledel = async (id: number) => {
    await feedback.confirm('确定要删除？')
    await delVideoRecord({ id })
    getLists()
}
onActivated(() => {
    getLists()
})

getLists()
</script>
