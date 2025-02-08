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

namespace app\tenantapi\logic\sensitive;


use app\common\logic\BaseLogic;
use app\common\model\sensitive\TenantSensitiveWords;
use think\facade\Db;


/**
 * 敏感词逻辑
 * Class TenantVoiceLogic
 * @package app\tenantapi\logic\voice
 */
class WordsLogic extends BaseLogic
{

    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author yfdong
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            TenantSensitiveWords::create([
                'words' => $params['words'],
                'status' => $params['status'],
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
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            TenantSensitiveWords::query()
                ->where('id', $params['id'])->update([
                    'words' => $params['words'],
                    'status' => $params['status'],
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
     */
    public static function delete(array $params): bool
    {
        return TenantSensitiveWords::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author yfdong
     */
    public static function detail($params): array
    {
        $info = TenantSensitiveWords::query()->findOrEmpty($params['id'])->toArray();
        return $info;
    }

}