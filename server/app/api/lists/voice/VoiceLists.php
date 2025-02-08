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

namespace app\api\lists\voice;


use app\api\lists\BaseApiDataLists;
use app\common\model\voice\TenantVoice;
use app\common\service\FileService;


/**
 * TenantVoice列表
 * Class TenantVoiceLists
 * @package app\tenantapi\listsvoice
 */
class VoiceLists extends BaseApiDataLists
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
            $where[] = ['name', 'like', $this->params['keyword']];
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
     * @date 2024/10/09 22:12
     */
    public function lists(): array
    {
        $records = TenantVoice::query()
            ->where($this->queryWhere())
            ->with('file')
            ->field(['id', 'tenant_id', 'task_id', 'status', 'uid', 'name', 'record', 'file_id', 'cover', 'create_time', 'duration'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
        // 处理 file 不为空的记录
        $records = collect($records)->map(function ($record) {
            if (!empty($record['file'])) {
                // 处理 file 字段
                $record['voice_url'] = FileService::getFileUrl($record['file']['uri']);
            } else {
                $record['voice_url'] = '';
            }
            unset($record['file']);
            return $record;
        })->toArray();
        return $records;
    }


    /**
     * @notes 获取数量
     * @return int
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public function count(): int
    {
        return TenantVoice::query()->where($this->queryWhere())->where($this->searchWhere)->count();
    }

}