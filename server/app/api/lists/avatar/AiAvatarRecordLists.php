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

namespace app\api\lists\avatar;


use app\api\lists\BaseApiDataLists;
use app\common\model\avatar\TenantAiAvatarRecord;
use app\common\model\voice\TenantVoice;
use app\common\service\FileService;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;


/**
 * TenantAiAvatarRecord列表
 * Class TenantAiAvatarRecordLists
 * @package app\tenantapi\listsavatar
 */
class AiAvatarRecordLists extends BaseApiDataLists
{

    public function queryWhere()
    {
        // 指定用户
        $where[] = ['uid', '=', $this->userId];
        // 根据状态筛选
        if (isset($this->params['status']) && in_array($this->params['status'], ['0', '1', '2'])) {
            $where[] = ['status', '=', $this->params['status']];
        }
        return $where;
    }

    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function lists(): array
    {
        $aiAvatarRecordList = TenantAiAvatarRecord::query()
            ->where($this->queryWhere())
            ->with(['user', 'voice', 'video', 'file'])
            ->field(['id', 'tenant_id', 'uid', 'title', 'mode', 'task_id', 'voice_id', 'video_id', 'cost_power', 'status', 'completion_time', 'cost_time', 'file_id', 'duration', 'size', 'cover', 'create_time', 'fail_reason', 'timbre_name'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['create_time' => 'desc'])
            ->select()
            ->toArray();

        // 系统音色信息
        $timbre = config('timbre.system_timbre');

        foreach ($timbre as $item) {
            $timbreName[$item['voice_name']] = $item['name'];
        }
        foreach ($aiAvatarRecordList as &$item) {
            // 合成结果文件处理
            if (isset($item['file'])) {
                $item['resultFile'] = FileService::getFileUrl($item['file']['uri']);
                unset($item['file']);
            }
            // 处理合成声音合成结果
            if (isset($item['timbre_name'])) {
                $item['voice_name'] = $item['timbre_name'];
                unset($item['timbre_name']);
            } else {
                if (isset($item['voice'])) {
                    if (isset($item['voice']['voice_id']) && !empty($item['voice']['voice_id'])) {
                        $voice = TenantVoice::query()->where(['id' => $item['voice']['voice_id']])->findOrEmpty()->toArray();
                        if (isset($voice['name'])) {
                            $item['voice_name'] = $voice['name'];
                        }
                    } else {
                        if ($item['voice']['timbre_name']) {
                            $item['voice_name'] = $timbreName[$item['voice']['timbre_name']];
                        }
                    }
                }
            }
        }
        return $aiAvatarRecordList;
    }


    /**
     * @notes 获取数量
     * @return int
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function count(): int
    {
        return TenantAiAvatarRecord::query()->where($this->queryWhere())->count();
    }

}