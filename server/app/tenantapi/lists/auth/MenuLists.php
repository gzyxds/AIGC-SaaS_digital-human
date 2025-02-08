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

namespace app\tenantapi\lists\auth;

use app\tenantapi\lists\BaseAdminDataLists;
use app\common\model\auth\TenantSystemMenu;
use think\db\exception\DbException;


/**
 *  菜单列表
 * Class MenuLists
 * @package app\tenantapi\lists\auth
 */
class MenuLists extends BaseAdminDataLists
{

    /**
     * @notes 获取菜单列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/6/29 16:41
     */
    public function lists(): array
    {
        $lists = TenantSystemMenu::order(['sort' => 'desc', 'id' => 'asc'])
            ->select()
            ->toArray();
        return linear_to_tree($lists, 'children');
    }


    /**
     * @notes 获取菜单数量
     * @return int
     * @throws DbException
     * @author 段誉
     * @date 2022/6/29 16:41
     */
    public function count(): int
    {
        return TenantSystemMenu::count();
    }

}