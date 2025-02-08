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

namespace app\api\logic\key;

use app\common\enum\ModuleEnum;
use app\common\logic\BaseLogic;
use app\common\model\key\TenantKeyPool;
use Exception;

/**
 * Key池逻辑类
 */
class KeyPoolLogic extends BaseLogic
{
    /**
     * @notes 获取功能key值
     * @param int $id
     * @return array
     * @author yfdong
     * @date 2024/11/18 23:38
     */
    public static function moduleKey($module, $tenantId = null): array
    {
        $query = ['status' => '1', 'delete_time' => null, 'type' => $module];
        if (null !== $tenantId) {
            $query['tenant_id'] = $tenantId;
        }
        return TenantKeyPool::query()
            ->where($query)
            ->order(['create_time' => 'desc'])
            ->findOrEmpty()
            ->toArray();
    }
}