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


use app\common\enum\YesNoEnum;
use app\common\logic\BaseLogic;
use app\common\model\auth\TenantAdmin;
use app\common\model\auth\TenantSystemMenu;
use app\common\model\auth\TenantSystemRoleMenu;


/**
 * 系统菜单
 * Class MenuLogic
 * @package app\tenantapi\logic\auth
 */
class MenuLogic extends BaseLogic
{


    /**
     * @notes 获取管理员对应的角色菜单
     * @param $adminId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/7/1 10:50
     */
    public static function getMenuByAdminId($adminId)
    {
        $admin = TenantAdmin::findOrEmpty($adminId);

        $where = [];
        $where[] = ['type', 'in', ['M', 'C']];
        $where[] = ['is_disable', '=', 0];

        if ($admin['root'] != 1) {
            $roleMenu = TenantSystemRoleMenu::whereIn('role_id', $admin['role_id'])->column('menu_id');
            $where[] = ['id', 'in', $roleMenu];
        }

        $menu = TenantSystemMenu::where($where)
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();

        return linear_to_tree($menu, 'children');
    }


    /**
     * @notes 添加菜单
     * @param array $params
     * @return TenantSystemMenu|\think\Model
     * @author 段誉
     * @date 2022/6/30 10:06
     */
    public static function add(array $params)
    {
        return TenantSystemMenu::create([
            'pid' => $params['pid'],
            'type' => $params['type'],
            'name' => $params['name'],
            'icon' => $params['icon'] ?? '',
            'sort' => $params['sort'],
            'perms' => $params['perms'] ?? '',
            'paths' => $params['paths'] ?? '',
            'component' => $params['component'] ?? '',
            'selected' => $params['selected'] ?? '',
            'params' => $params['params'] ?? '',
            'is_cache' => $params['is_cache'],
            'is_show' => $params['is_show'],
            'is_disable' => $params['is_disable'],
        ]);
    }


    /**
     * @notes 编辑菜单
     * @param array $params
     * @return TenantSystemMenu
     * @author 段誉
     * @date 2022/6/30 10:07
     */
    public static function edit(array $params)
    {
        return TenantSystemMenu::update([
            'pid' => $params['pid'],
            'type' => $params['type'],
            'name' => $params['name'],
            'icon' => $params['icon'] ?? '',
            'sort' => $params['sort'],
            'perms' => $params['perms'] ?? '',
            'paths' => $params['paths'] ?? '',
            'component' => $params['component'] ?? '',
            'selected' => $params['selected'] ?? '',
            'params' => $params['params'] ?? '',
            'is_cache' => $params['is_cache'],
            'is_show' => $params['is_show'],
            'is_disable' => $params['is_disable'],
        ], ['id' => $params['id']]);
    }


    /**
     * @notes 详情
     * @param $params
     * @return array
     * @author 段誉
     * @date 2022/6/30 9:54
     */
    public static function detail($params)
    {
        return TenantSystemMenu::findOrEmpty($params['id'])->toArray();
    }


    /**
     * @notes 删除菜单
     * @param $params
     * @author 段誉
     * @date 2022/6/30 9:47
     */
    public static function delete($params)
    {
        // 删除菜单
        TenantSystemMenu::destroy($params['id']);
        // 删除角色-菜单表中 与该菜单关联的记录
        TenantSystemRoleMenu::where(['menu_id' => $params['id']])->delete();
    }


    /**
     * @notes 更新状态
     * @param array $params
     * @return TenantSystemMenu
     * @author 段誉
     * @date 2022/7/6 17:02
     */
    public static function updateStatus(array $params)
    {
        return TenantSystemMenu::update([
            'is_disable' => $params['is_disable']
        ], ['id' => $params['id']]);
    }


    /**
     * @notes 全部数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/10/13 11:03
     */
    public static function getAllData()
    {
        $data = TenantSystemMenu::where(['is_disable' => YesNoEnum::NO])
            ->field('id,pid,name')
            ->order(['sort' => 'desc', 'id' => 'desc'])
            ->select()
            ->toArray();

        return linear_to_tree($data, 'children');
    }

}