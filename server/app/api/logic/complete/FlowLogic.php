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

namespace app\api\logic\complete;


use app\common\model\avatar\TenantAiAvatarRecord;
use app\common\model\complete\TenantCompleteFlow;
use app\common\logic\BaseLogic;
use think\facade\Db;


/**
 * FlowLogic逻辑
 * Class FlowLogic
 * @package app\api\logic\complet
 */
class FlowLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public static function add(array $params, $avatarRecord): bool
    {
        Db::startTrans();
        try {
            $taskId = self::generateTaskId();
            TenantCompleteFlow::create([
                'task_id'       => $taskId,
                'uid'           => $params['uid'],
                'host'          => $params['host'],
                'terminal'      => $params['terminal'],
                'voice_id'      => $params['voice_id'],
                'voice_mode'    => $params['voice_mode'],
                'content'       => $params['content'],
                'timbre'        => $params['timbre'] ?? null,
                'video_mode'    => $params['video_mode'],
                'video_id'      => $params['video_id'] ?? null,
                'video_file_id' => $params['video_file_id'] ?? null,
                'video_name'    => $params['video_name'],
            ]);
            $avatarRecord['task_id'] = $taskId;
            TenantAiAvatarRecord::create($avatarRecord);
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
        return TenantCompleteFlow::destroy($params['id']);
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
        return TenantCompleteFlow::query()->with(['file'])->findOrEmpty($params['id'])->toArray();
    }

    /**
     * @notes 获取全流程任务信息
     * @param mixed $taskId
     * @return array
     * @author yfdong
     * @date 2024/12/09 23:15
     */
    public static function getInfoByTaskId(mixed $taskId, int $status)
    {
        return TenantCompleteFlow::query()->where(['task_id' => $taskId, 'status' => $status])->findOrEmpty()->toArray();
    }

    /**
     * 随机生成任务号
     * @return string
     */
    static function generateTaskId()
    {
        // 生成一个长度为 24 位的随机数字
        $taskId = '';
        for ($i = 0; $i < 24; $i++) {
            $taskId .= mt_rand(0, 9); // 生成一个 0 到 9 的随机数字
        }
        return $taskId;
    }


    /**
     * 更新状态
     * @param $id
     * @param $params
     * @return void
     */
    public function changeFlow($id, $params)
    {
        TenantCompleteFlow::update($params, ['id' => $id]);
    }
}