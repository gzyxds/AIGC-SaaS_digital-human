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

namespace app\api\logic\power;


use app\common\model\power\TenantPowerPackage;
use app\common\logic\BaseLogic;


/**
 * power逻辑
 * Class TenantPowerPackageLogic
 * @package app\tenantapi\logic\power
 */
class PowerPackageLogic extends BaseLogic
{
    /**
     * @notes 获取power详情
     * @param $params
     * @return array
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public static function detail($params): array
    {
        return TenantPowerPackage::query()->where(['status' => 1])->findOrEmpty($params['id'])->toArray();
    }
}