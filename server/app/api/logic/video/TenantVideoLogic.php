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

namespace app\api\logic\video;


use app\common\model\video\TenantVideo;
use app\common\logic\BaseLogic;
use app\common\service\FileService;
use think\facade\Db;


/**
 * TenantVideo逻辑
 * Class TenantVideoLogic
 * @package app\tenantapi\logic\video
 */
class TenantVideoLogic extends BaseLogic
{
    /**
     * @notes 根据训练模型id获取对应数字人形象数据
     * @param mixed $mode_id
     * @return array
     * @author yfdong
     * @date 2024/12/18 23:14
     */
    public static function queryByImageId(mixed $mode_id)
    {
        return TenantVideo::query()->where(['image_id' => $mode_id])->findOrEmpty()->toArray();
    }


    public static function updateVideoStetus($id, $status)
    {
        return TenantVideo::query()->where('id', $id)->update([
            'status' => $status ? 1 : 2,
        ]);
    }


    /**
     * @notes 根据用户id和数字人形象id获取视频信息
     * @param $videoId
     * @param $uid
     * @return string|null
     * @author yfdong
     * @date 2024/12/17 00:05
     */
    public function queryUrlById($videoId, $uid)
    {
        $videoInfo = TenantVideo::query()->with('file')->where(['id' => $videoId, 'uid' => $uid])->findOrEmpty()->toArray();
        return isset($videoInfo['file']['uri']) ? FileService::getFileUrl($videoInfo['file']['uri']) : null;

    }

    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            TenantVideo::create([
                'tenant_id' => request()->tenantId,
                'uid'       => $params['uid'],
                'name'      => $params['name'],
                'record'    => $params['record'] ?? '',
                'file_id'   => $params['file_id'],
                'cover'     => $params['cover'] ?? '',
                'duration'  => $params['duration'],
                'image_id'  => $params['image_id'] ?? null,
                'status'    => 0,
                'terminal'  => $params['terminal'] ?? null,
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
     * @date 2024/10/09 22:19
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            TenantVideo::where('id', $params['id'])->update([
                'tenant_id' => $params['tenant_id'],
                'uid'       => $params['uid'],
                'name'      => $params['name'],
                'record'    => $params['record'] ?? '',
                'file_id'   => $params['file_id'],
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
     * @date 2024/10/09 22:19
     */
    public static function delete(array $params): bool
    {
        return TenantVideo::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public static function detail($params): array
    {
        $video = TenantVideo::with(['file', 'user'])->findOrEmpty($params['id'])->toArray();
        if (isset($video['file'])) {
            $video['fileUrl'] = FileService::getFileUrl($video['file']['uri']);
            unset($video['file']);
        }
        if (!empty($video['user'])) {
            $video['userName'] = $video['user']['nickname'];
            $video['userAvatar'] = $video['user']['avatar'];
            unset($video['user']);
        }
        return $video;
    }
}