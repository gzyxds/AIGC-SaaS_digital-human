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

namespace app\tenantapi\logic\avatar;


use app\common\model\avatar\TenantAiAvatarRecord;
use app\common\logic\BaseLogic;
use app\common\service\FileService;
use think\facade\Db;


/**
 * TenantAiAvatarRecord逻辑
 * Class TenantAiAvatarRecordLogic
 * @package app\tenantapi\logic\avatar
 */
class TenantAiAvatarRecordLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            TenantAiAvatarRecord::create([
                'uid'             => $params['uid'],
                'title'           => $params['title'],
                'cover'           => $params['cover'],
                'task_id'         => $params['task_id'],
                'voice_id'        => $params['voice_id'],
                'video_id'        => $params['video_id'],
                'mode'            => $params['mode'] ?? null,
                'cost_power'      => $params['cost_power'] ?? '',
                'status'          => $params['status'] ?? '0',
                'completion_time' => $params['completion_time'] ?? '',
                'cost_time'       => $params['cost_time'] ?? '',
                'file_id'         => $params['file_id'] ?? '',
                'duration'        => $params['duration'] ?? '',
                'size'            => $params['size'] ?? '',
                'terminal'        => $params['terminal'] ?? null,
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
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            TenantAiAvatarRecord::where('id', $params['id'])->update([
                'tenant_id'       => $params['tenant_id'],
                'title'           => $params['title'],
                'uid'             => $params['uid'],
                'task_id'         => $params['task_id'],
                'voice_id'        => $params['voice_id'],
                'video_id'        => $params['video_id'],
                'cost_power'      => $params['cost_power'],
                'status'          => $params['status'],
                'completion_time' => $params['completion_time'],
                'cost_time'       => $params['cost_time'],
                'file_id'         => $params['file_id'],
                'duration'        => $params['duration'],
                'size'            => $params['size'],
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
     * @notes 删除
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public static function delete(array $params): bool
    {
        return TenantAiAvatarRecord::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public static function detail($params): array
    {
        $record = TenantAiAvatarRecord::query()->with(['user', 'voice', 'video', 'file'])->findOrEmpty($params['id'])->toArray();
        // 合成结果文件处理
        if (isset($record['file'])) {
            $record['resultFile'] = FileService::getFileUrl($record['file']['uri']);
            unset($record['file']);
        }
        // 用户信息处理
        if (isset($record['user'])) {
            $record['userName'] = $record['user']['nickname'];
            $record['userAvatar'] = $record['user']['avatar'];
            unset($record['user']);
        }
        return $record;
    }


    /**
     * @notes 根据任务号获取对应记录信息
     * @param $params
     * @return array
     * @author yfdong
     * @date 2024/10/10 21:39
     */
    public static function getInfoByTaskId($taskId): array
    {
        return TenantAiAvatarRecord::where(['task_id' => $taskId, 'status' => 0])->findOrEmpty()->toArray();
    }


}