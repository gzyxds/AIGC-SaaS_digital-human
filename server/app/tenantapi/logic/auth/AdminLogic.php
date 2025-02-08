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

use app\common\cache\TenantAdminAuthCache;
use app\common\enum\YesNoEnum;
use app\common\logic\BaseLogic;
use app\common\model\auth\TenantAdmin;
use app\common\model\auth\TenantAdminDept;
use app\common\model\auth\TenantAdminJobs;
use app\common\model\auth\TenantAdminRole;
use app\common\model\auth\TenantAdminSession;
use app\common\cache\TenantAdminTokenCache;
use app\common\service\FileService;
use think\facade\Config;
use think\facade\Db;

/**
 * 管理员逻辑
 * Class AdminLogic
 * @package app\tenantapi\logic\auth
 */
class AdminLogic extends BaseLogic
{

    /**
     * @notes 添加管理员
     * @param array $params
     * @author 段誉
     * @date 2021/12/29 10:23
     */
    public static function add(array $params)
    {
        Db::startTrans();
        try {
            $passwordSalt = Config::get('project.unique_identification');
            $password = create_password($params['password'], $passwordSalt);
            $defaultAvatar = config('project.default_image.admin_avatar');
            $avatar = !empty($params['avatar']) ? FileService::setFileUrl($params['avatar']) : $defaultAvatar;

            $admin = TenantAdmin::create([
                'name' => $params['name'],
                'account' => $params['account'],
                'avatar' => $avatar,
                'password' => $password,
                'create_time' => time(),
                'disable' => $params['disable'],
                'multipoint_login' => $params['multipoint_login'],
            ]);

            // 角色
            self::insertRole($admin['id'], $params['role_id'] ?? []);
            // 部门
            self::insertDept($admin['id'], $params['dept_id'] ?? []);
            // 岗位
            self::insertJobs($admin['id'], $params['jobs_id'] ?? []);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 编辑管理员
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2021/12/29 10:43
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            // 基础信息
            $data = [
                'name' => $params['name'],
                'account' => $params['account'],
                'disable' => $params['disable'],
                'multipoint_login' => $params['multipoint_login']
            ];

            // 头像
            $data['avatar'] = !empty($params['avatar']) ? FileService::setFileUrl($params['avatar']) : '';

            // 密码
            if (!empty($params['password'])) {
                $passwordSalt = Config::get('project.unique_identification');
                $data['password'] = create_password($params['password'], $passwordSalt);
            }

            // 禁用或更换角色后.设置token过期
            $roleId = TenantAdminRole::where('admin_id', $params['id'])->column('role_id');
            $editRole = false;
            if (!empty(array_diff_assoc($roleId, $params['role_id']))) {
                $editRole = true;
            }

            if ($params['disable'] == 1 || $editRole) {
                $tokenArr = TenantAdminSession::where('admin_id', $params['id'])->select()->toArray();
                foreach ($tokenArr as $token) {
                    self::expireToken($token['token']);
                }
            }

            TenantAdmin::update($data, ['id' => $params['id']]);
            (new TenantAdminAuthCache($params['id']))->clearAuthCache();

            // 删除旧的关联信息
            TenantAdminRole::delByUserId($params['id']);
            TenantAdminDept::delByUserId($params['id']);
            TenantAdminJobs::delByUserId($params['id']);
            // 角色
            self::insertRole($params['id'], $params['role_id']);
            // 部门
            self::insertDept($params['id'], $params['dept_id'] ?? []);
            // 岗位
            self::insertJobs($params['id'], $params['jobs_id'] ?? []);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 删除管理员
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2021/12/29 10:45
     */
    public static function delete(array $params): bool
    {
        Db::startTrans();
        try {
            $admin = TenantAdmin::findOrEmpty($params['id']);
            if ($admin->root == YesNoEnum::YES) {
                throw new \Exception("超级管理员不允许被删除");
            }
            TenantAdmin::destroy($params['id']);

            //设置token过期
            $tokenArr = TenantAdminSession::where('admin_id', $params['id'])->select()->toArray();
            foreach ($tokenArr as $token) {
                self::expireToken($token['token']);
            }
            (new TenantAdminAuthCache($params['id']))->clearAuthCache();

            // 删除旧的关联信息
            TenantAdminRole::delByUserId($params['id']);
            TenantAdminDept::delByUserId($params['id']);
            TenantAdminJobs::delByUserId($params['id']);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 过期token
     * @param $token
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2021/12/29 10:46
     */
    public static function expireToken($token): bool
    {
        $adminSession = TenantAdminSession::where('token', '=', $token)
            ->with('admin')
            ->find();

        if (empty($adminSession)) {
            return false;
        }

        $time = time();
        $adminSession->expire_time = $time;
        $adminSession->update_time = $time;
        $adminSession->save();

        return (new TenantAdminTokenCache())->deleteAdminInfo($token);
    }


    /**
     * @notes 查看管理员详情
     * @param $params
     * @return array
     * @author 段誉
     * @date 2021/12/29 11:07
     */
    public static function detail($params, $action = 'detail'): array
    {
        $admin = TenantAdmin::field([
            'id', 'account', 'name', 'disable', 'root',
            'multipoint_login', 'avatar',
        ])->findOrEmpty($params['id'])->toArray();

        if ($action == 'detail') {
            return $admin;
        }

        $result['user'] = $admin;
        // 当前管理员角色拥有的菜单
        $result['menu'] = MenuLogic::getMenuByAdminId($params['id']);
        // 当前管理员橘色拥有的按钮权限
        $result['permissions'] = AuthLogic::getBtnAuthByRoleId($admin);
        return $result;
    }


    /**
     * @notes 编辑超级管理员
     * @param $params
     * @author 段誉
     * @date 2022/4/8 17:54
     */
    public static function editSelf($params)
    {
        $data = [
            'name' => $params['name'],
            'avatar' => FileService::setFileUrl($params['avatar']),
        ];

        if (!empty($params['password'])) {
            $passwordSalt = Config::get('project.unique_identification');
            $data['password'] = create_password($params['password'], $passwordSalt);
        }

        return TenantAdmin::update($data, ['id' => $params['admin_id']]);
    }


    /**
     * @notes 新增角色
     * @param $adminId
     * @param $roleIds
     * @throws \Exception
     * @author 段誉
     * @date 2022/11/25 14:23
     */
    public static function insertRole($adminId, $roleIds)
    {
        if (!empty($roleIds)) {
            // 角色
            $roleData = [];
            foreach ($roleIds as $roleId) {
                $roleData[] = [
                    'admin_id' => $adminId,
                    'role_id' => $roleId,
                ];
            }
            (new TenantAdminRole())->saveAll($roleData);
        }
    }


    /**
     * @notes 新增部门
     * @param $adminId
     * @param $deptIds
     * @throws \Exception
     * @author 段誉
     * @date 2022/11/25 14:22
     */
    public static function insertDept($adminId, $deptIds)
    {
        // 部门
        if (!empty($deptIds)) {
            $deptData = [];
            foreach ($deptIds as $deptId) {
                $deptData[] = [
                    'admin_id' => $adminId,
                    'dept_id' => $deptId
                ];
            }
            (new TenantAdminDept())->saveAll($deptData);
        }
    }


    /**
     * @notes 新增岗位
     * @param $adminId
     * @param $jobsIds
     * @throws \Exception
     * @author 段誉
     * @date 2022/11/25 14:22
     */
    public static function insertJobs($adminId, $jobsIds)
    {
        // 岗位
        if (!empty($jobsIds)) {
            $jobsData = [];
            foreach ($jobsIds as $jobsId) {
                $jobsData[] = [
                    'admin_id' => $adminId,
                    'jobs_id' => $jobsId
                ];
            }
            (new TenantAdminJobs())->saveAll($jobsData);
        }
    }

}