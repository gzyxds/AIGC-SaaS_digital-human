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

namespace app\tenantapi\logic\generate;


use app\common\logic\BaseLogic;
use app\common\model\generate\TenantGenerateTheme;
use think\facade\Db;


/**
 * GenerateThemeLogic逻辑
 * Class TenantAiAvatarRecordLogic
 * @package app\tenantapi\logic\avatar
 */
class GenerateThemeLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/12/23 23:28
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            TenantGenerateTheme::create([
                'name'   => $params['name'],
                'status' => $params['status'] ?? '1',
                'sort'   => $params['sort'] ?? 0,
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
     * @date 2024/12/23 23:30
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            TenantGenerateTheme::query()->where('id', $params['id'])->update([
                'name'   => $params['name'],
                'status' => $params['status'] ?? '1',
                'sort'   => $params['sort'] ?? 0,
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
     * @date 2024/12/23 23:30
     */
    public static function delete(array $params): bool
    {
        return TenantGenerateTheme::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author yfdong
     * @date 2024/12/23 23:30
     */
    public static function detail($params): array
    {
        return TenantGenerateTheme::query()->with(['user', 'voice', 'video', 'file'])->findOrEmpty($params['id'])->toArray();
    }


    /**
     * @notes 重命名校验
     * @param $name
     * @return false
     * @author yfdong
     * @date 2024/12/23 23:45
     */
    public static function checkName($name): bool
    {
        $num = TenantGenerateTheme::query()->where(['name' => $name])->count();
        return $num > 0 ? false : true;
    }

}