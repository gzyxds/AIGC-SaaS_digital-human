<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\api\lists\voicerecord;


use app\api\lists\BaseApiDataLists;
use app\common\service\FileService;
use app\common\model\voicerecord\TenantVoiceRecord;
use app\common\model\file\TenantFile;



/**
 * TenantVoiceRecord列表
 * Class TenantVoiceRecordLists
 * @package app\tenantapi\listsvoicerecord
 */
class VoiceRecordLists extends BaseApiDataLists
{

    public function queryWhere()
    {
        // 指定用户
        $where[] = ['uid', '=', $this->userId];
        // 根据状态筛选
        if (isset($this->params['status']) && in_array($this->params['status'], ['0', '1', '2'])) {
            $where[] = ['status', '=', $this->params['status']];
        }

        // 根据名称模糊搜索
        if (!empty($this->params['keyword'])) {
            $where[] = ['title', 'like', $this->params['keyword']];
        }

        return $where;
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function lists(): array
    {
        $records = TenantVoiceRecord::query()
            ->where($this->queryWhere())
            ->with(['file', 'voice'])
            ->field(['id', 'tenant_id', 'uid', 'task_id', 'title', 'voice_id', 'content', 'cost_power', 'status', 'completion_time', 'cost_time', 'file_id', 'duration', 'size', 'remark', 'cover', 'create_time','timbre_name'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
        // 处理 file 不为空的记录
        $records = collect($records)->map(function ($record) {
            if (!empty($record['voice'])) {
                $voice_file = (new TenantFile())->find($record['voice']['file_id']);
                $record['timbre']['id'] = $record['voice']['id'];
                $record['timbre']['name'] = $record['voice']['name'];
                $record['timbre']['voice_url'] = FileService::getFileUrl($voice_file['uri']);
                $record['timbre']['cover'] = $record['voice']['cover'];
            } else {
                $record['timbre'] = null;
            }

            if (!empty($record['file'])) {
                // 处理 file 字段
                $record['voice_url'] = FileService::getFileUrl($record['file']['uri']);
            } else {
                $record['voice_url'] = '';
            }
            unset($record['file']);
            unset($record['voice']);
            return $record;
        })->toArray();
        return $records;
    }


    /**
     * @notes 获取数量
     * @return int
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function count(): int
    {
        return TenantVoiceRecord::query()->where($this->queryWhere())->count();
    }

}