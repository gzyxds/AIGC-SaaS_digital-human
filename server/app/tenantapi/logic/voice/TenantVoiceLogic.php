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

namespace app\tenantapi\logic\voice;


use app\common\model\voice\TenantVoice;
use app\common\logic\BaseLogic;
use app\common\service\FileService;
use think\facade\Db;


/**
 * TenantVoice逻辑
 * Class TenantVoiceLogic
 * @package app\tenantapi\logic\voice
 */
class TenantVoiceLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            TenantVoice::create([
                'tenant_id'        => request()->tenantId,
                'uid'              => $params['uid'],
                'name'             => $params['name'],
                'record'           => $params['record'] ?? '',
                'file_id'          => $params['file_id'],
                'cover'            => $params['cover'] ?? null,
                'duration'         => $params['duration'] ?? null,
                'expected_content' => $params['expected_content'] ?? null,
                'status'           => $params['status'] ?? 0,
                'task_id'          => $params['task_id'] ?? null,
            ]);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 编辑
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            TenantVoice::update([
                'status'         => $params['status'],
                'actual_content' => $params['actual_content'] ?? '',
            ], ['id' => $params['id']]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 删除
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public static function delete(array $params): bool
    {
        return TenantVoice::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public static function detail($params): array
    {
        $voice = TenantVoice::query()->with(['file', 'user'])->findOrEmpty($params['id'])->toArray();
        if (isset($voice['file'])) {
            $voice['fileUrl'] = FileService::getFileUrl($voice['file']['uri']);
            unset($voice['file']);
        }
        if (!empty($voice['user'])) {
            $voice['userName'] = $voice['user']['nickname'];
            $voice['userAvatar'] = $voice['user']['avatar'];
            unset($voice['user']);
        }
        return $voice;
    }

    /**
     * @notes 获取声音克隆音色信息
     * @param mixed $taskId
     * @return array
     * @author yfdong
     * @date 2024/12/09 23:15
     */
    public static function getInfoByTaskId(mixed $taskId)
    {
        return TenantVoice::query()->where(['task_id' => $taskId, 'status' => 0])->findOrEmpty()->toArray();
    }
}