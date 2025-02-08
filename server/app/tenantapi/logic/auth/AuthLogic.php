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

namespace app\tenantapi\logic\auth;

use app\common\model\auth\TenantAdminRole;
use app\common\model\auth\TenantSystemMenu;
use app\common\model\auth\TenantSystemRoleMenu;


/**
 * 权限功能类
 * Class AuthLogic
 * @package app\tenantapi\logic\auth
 */
class AuthLogic
{

    /**
     * @notes 获取全部权限
     * @return mixed
     * @author 段誉
     * @date 2022/7/1 11:55
     */
    public static function getAllAuth()
    {
        return TenantSystemMenu::distinct(true)
            ->where([
                ['is_disable', '=', 0],
                ['perms', '<>', '']
            ])
            ->column('perms');
    }


    /**
     * @notes 获取当前管理员角色按钮权限
     * @param $roleId
     * @return mixed
     * @author 段誉
     * @date 2022/7/1 16:10
     */
    public static function getBtnAuthByRoleId($admin)
    {
        if ($admin['root']) {
            return ['*'];
        }

        $menuId = TenantSystemRoleMenu::whereIn('role_id', $admin['role_id'])
            ->column('menu_id');

        $where[] = ['is_disable', '=', 0];
        $where[] = ['perms', '<>', ''];

        $roleAuth = TenantSystemMenu::distinct(true)
            ->where('id', 'in', $menuId)
            ->where($where)
            ->column('perms');

        $allAuth = TenantSystemMenu::distinct(true)
            ->where($where)
            ->column('perms');

        $hasAllAuth = array_diff($allAuth, $roleAuth);
        if (empty($hasAllAuth)) {
            return ['*'];
        }

        return $roleAuth;
    }


    /**
     * @notes 获取管理员角色关联的菜单id(菜单，权限)
     * @param int $adminId
     * @return array
     * @author 段誉
     * @date 2022/7/1 15:56
     */
    public static function getAuthByAdminId(int $adminId): array
    {
        $roleIds = TenantAdminRole::where('admin_id', $adminId)->column('role_id');
        $menuId = TenantSystemRoleMenu::whereIn('role_id', $roleIds)->column('menu_id');

        return TenantSystemMenu::distinct(true)
            ->where([
                ['is_disable', '=', 0],
                ['perms', '<>', ''],
                ['id', 'in', array_unique($menuId)],
            ])
            ->column('perms');
    }
}