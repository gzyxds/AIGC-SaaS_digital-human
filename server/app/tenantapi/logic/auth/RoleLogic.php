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

use app\common\logic\BaseLogic;
use app\common\model\auth\TenantSystemRole;
use app\common\model\auth\TenantSystemRoleMenu;
use app\common\{
    cache\TenantAdminAuthCache
};
use think\facade\Db;


/**
 * 角色逻辑层
 * Class RoleLogic
 * @package app\tenantapi\logic\auth
 */
class RoleLogic extends BaseLogic
{

    /**
     * @notes 添加角色
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2021/12/29 11:50
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            $menuId = !empty($params['menu_id']) ? $params['menu_id'] : [];

            $role = TenantSystemRole::create([
                'name' => $params['name'],
                'desc' => $params['desc'] ?? '',
                'sort' => $params['sort'] ?? 0,
            ]);

            $data = [];
            foreach ($menuId as $item) {
                if (empty($item)) {
                    continue;
                }
                $data[] = [
                    'role_id' => $role['id'],
                    'menu_id' => $item,
                ];
            }
            (new TenantSystemRoleMenu)->insertAll($data);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 编辑角色
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2021/12/29 14:16
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            $menuId = !empty($params['menu_id']) ? $params['menu_id'] : [];

            TenantSystemRole::update([
                'name' => $params['name'],
                'desc' => $params['desc'] ?? '',
                'sort' => $params['sort'] ?? 0,
            ], ['id' => $params['id']]);

            if (!empty($menuId)) {
                TenantSystemRoleMenu::where(['role_id' => $params['id']])->delete();
                $data = [];
                foreach ($menuId as $item) {
                    $data[] = [
                        'role_id' => $params['id'],
                        'menu_id' => $item,
                    ];
                }
                (new TenantSystemRoleMenu)->insertAll($data);
            }

            (new TenantAdminAuthCache())->deleteTag();

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 删除角色
     * @param int $id
     * @return bool
     * @author 段誉
     * @date 2021/12/29 14:16
     */
    public static function delete(int $id)
    {
        TenantSystemRole::destroy(['id' => $id]);
        (new TenantAdminAuthCache())->deleteTag();
        return true;
    }


    /**
     * @notes 角色详情
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2021/12/29 14:17
     */
    public static function detail(int $id): array
    {
        $detail = TenantSystemRole::field('id,name,desc,sort')->find($id);
        $authList = $detail->roleMenuIndex()->select()->toArray();
        $menuId = array_column($authList, 'menu_id');
        $detail['menu_id'] = $menuId;
        return $detail->toArray();
    }


    /**
     * @notes 角色数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/10/13 10:39
     */
    public static function getAllData()
    {
        return TenantSystemRole::order(['sort' => 'desc', 'id' => 'desc'])
            ->select()
            ->toArray();
    }


}