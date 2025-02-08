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

namespace app\api\logic\voice;


use app\common\model\voicerecord\TenantVoiceRecord;
use app\common\logic\BaseLogic;
use app\common\service\FileService;
use think\facade\Db;


/**
 * TenantVoiceRecord逻辑
 * Class TenantVoiceRecordLogic
 * @package app\tenantapi\logic\voicerecord
 */
class VoiceRecordLogic extends BaseLogic
{

    /**
     * @notes 根据结果文件Id获取对应声音克隆记录
     * @param mixed $fileId
     * @return array
     * @author yfdong
     * @date 2024/11/30 20:41
     */
    public static function getInfoByFileId(mixed $fileId): array
    {
        return TenantVoiceRecord::query()->where(['file_id' => $fileId])->findOrEmpty()->toArray();
    }


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            TenantVoiceRecord::create([
                'tenant_id'            => request()->tenantId,
                'uid'                  => $params['uid'],
                'task_id'              => $params['task_id'],
                'title'                => $params['title'],
                'voice_id'             => $params['voice_id'],
                'content'              => $params['content'],
                'cost_power'           => $params['cost_power'],
                'status'               => $params['status'],
                'completion_time'      => $params['completion_time'] ?? '',
                'cost_time'            => $params['cost_time'] ?? '',
                'file_id'              => $params['file_id'] ?? '',
                'duration'             => $params['duration'] ?? '',
                'size'                 => $params['size'] ?? '',
                'remark'               => $params['remark'] ?? '',
                'cover'                => $params['cover'] ?? '',
                'timbre_name'          => $params['timbre_name'],
                'once_use_video'       => $params['once_use_video'] ?? null,
                'once_use_video_model' => $params['once_use_video_model'] ?? null,
                'terminal'             => $params['terminal'] ?? null,
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
     * @notes 上传本地音频
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2025/01/08 21:33
     */
    public static function uploadLocalVoice(array $params): bool
    {
        Db::startTrans();
        try {
            if (!isset($params['duration']) || $params['duration'] === 0) {
                $params['duration'] = FileService::getVideoDurationById($params['file_id']);
            }

            if ($params['duration'] > 5 * 60) {
                throw new \Exception('音频时长不能超过5分钟');
            }

            TenantVoiceRecord::create([
                'uid'         => $params['uid'],
                'title'       => $params['title'],
                'status'      => 1,
                'upload_flag' => 1,
                'file_id'     => $params['file_id'] ?? null,
                'remark'      => $params['remark'] ?? null,
                'terminal'    => $params['terminal'],
                'duration'    => $params['duration'],
                'cover'       => $params['cover'],
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
     * @date 2024/10/09 22:15
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            TenantVoiceRecord::where('id', $params['id'])->update([
                'tenant_id'       => $params['tenant_id'],
                'uid'             => $params['uid'],
                'task_id'         => $params['task_id'],
                'title'           => $params['title'],
                'voice_id'        => $params['voice_id'],
                'content'         => $params['content'],
                'cost_power'      => $params['cost_power'],
                'status'          => $params['status'],
                'completion_time' => $params['completion_time'],
                'cost_time'       => $params['cost_time'],
                'file_id'         => $params['file_id'],
                'duration'        => $params['duration'],
                'size'            => $params['size'],
                'remark'          => $params['remark'],
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
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public static function delete(array $params): bool
    {
        return TenantVoiceRecord::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public static function detail($params): array
    {
        $voiceRecord = TenantVoiceRecord::query()->with(['file', 'user', 'voice'])->findOrEmpty($params['id'])->toArray();
        if (!empty($voiceRecord['file'])) {
            // 处理 file 字段
            $voiceRecord['resultFile'] = FileService::getFileUrl($voiceRecord['file']['uri']);
            unset($voiceRecord['file']);
        }
        if (!empty($voiceRecord['user'])) {
            // 处理 file 字段
            $voiceRecord['userName'] = $voiceRecord['user']['nickname'];
            $voiceRecord['userAvatar'] = $voiceRecord['user']['avatar'];
            unset($voiceRecord['user']);
        }
        return $voiceRecord;
    }

    /**
     * @notes 根据任务号获取对应声音克隆记录
     * @param mixed $taskid
     * @return array
     * @author yfdong
     * @date 2024/10/29 20:56
     */
    public static function getInfoByTaskId(mixed $taskid)
    {
        return TenantVoiceRecord::where(['task_id' => $taskid, 'status' => 0])->findOrEmpty()->toArray();
    }
}