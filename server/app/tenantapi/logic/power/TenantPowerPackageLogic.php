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

namespace app\tenantapi\logic\power;


use app\common\model\power\TenantPowerPackage;
use app\common\logic\BaseLogic;
use think\facade\Db;


/**
 * power逻辑
 * Class TenantPowerPackageLogic
 * @package app\tenantapi\logic\power
 */
class TenantPowerPackageLogic extends BaseLogic
{


    /**
     * @notes 添加power
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            TenantPowerPackage::create([
                'title' => $params['title'],
                'cost' => $params['cost'],
                'power' => $params['power'],
                'original_cost' => $params['original_cost'] ?? '',
                // 'recommend' => $params['recommend'],
                'gift' => $params['gift'],
                'gift_power' => $params['gift_power']??null,
                'sort' => $params['sort'] ?? 0,
                'expire_time' => $params['expire_time'] ?? null,
                'note' => $params['note'] ?? null,
                'status' => $params['status'] ?? 1
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
     * @notes 编辑power
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            TenantPowerPackage::query()->where('id', $params['id'])->update([
                'title' => $params['title'],
                'cost' => $params['cost'],
                'power' => $params['power'],
                'status' => $params['status'],
                'original_cost' => $params['original_cost'],
                // 'recommend' => $params['recommend'],
                'gift' => $params['gift']??null,
                'gift_power' => $params['gift_power'],
                'sort' => $params['sort'] ?? 0,
                'expire_time' => $params['expire_time'] ?? null,
                'note' => $params['note'],
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
     * @notes 删除power
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public static function delete(array $params): bool
    {
        return TenantPowerPackage::destroy($params['id']);
    }


    /**
     * @notes 获取power详情
     * @param $params
     * @return array
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public static function detail($params): array
    {
        return TenantPowerPackage::query()->findOrEmpty($params['id'])->toArray();
    }
}