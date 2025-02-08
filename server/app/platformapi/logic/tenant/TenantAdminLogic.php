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
namespace app\platformapi\logic\tenant;

use app\common\cache\TenantAdminAuthCache;
use app\common\cache\TenantAdminTokenCache;
use app\common\enum\YesNoEnum;
use app\common\logic\BaseLogic;
use app\common\model\auth\TenantAdmin;
use app\common\model\auth\TenantAdminDept;
use app\common\model\auth\TenantAdminJobs;
use app\common\model\auth\TenantAdminRole;
use app\common\model\auth\TenantAdminSession;
use app\common\service\FileService;
use think\facade\Db;
use think\facade\Config;

/**
 * 用户逻辑层
 * Class TenantLogic
 * @package app\platformapi\logic\user
 */
class TenantAdminLogic extends BaseLogic
{
    /**
     * @notes 新增租户管理员
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/09/04 22:43
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
                'name'             => $params['name'],
                'tenant_id'        => $params['tenant_id'],
                'account'          => $params['account'],
                'avatar'           => $avatar,
                'password'         => $password,
                'create_time'      => time(),
                'disable'          => $params['disable'],
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
     * @notes 租户管理员详情
     * @param int $userId
     * @return array | bool
     * @author yfdong
     * @date 2024/09/04 22:44
     */
    public static function detail(int $userId): array|bool
    {
        $field = "id,root,name,avatar,account,multipoint_login,disable,create_time";

        try {
            return TenantAdmin::where(['id' => $userId])->field($field)->findOrEmpty()->toArray();
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 修改租户管理员
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/09/04 22:44
     */
    public static function edit(array $params)
    {
        Db::startTrans();
        try {
            // 基础信息
            $data = [
                'name'             => $params['name'],
                'account'          => $params['account'],
                'disable'          => $params['disable'],
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
     * @notes 详情场景
     * @param array $params
     * @return bool
     * @author yfdong
     * @date 2024/09/04 22:58
     */
    public static function delete(array $params)
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
     * @notes 设置token过期
     * @param $token
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author JXDN
     * @date 2024/09/06 18:03
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
     * @notes 新增角色
     * @param $adminId
     * @param $roleIds
     * @return void
     * @throws \Exception
     * @author JXDN
     * @date 2024/09/06 18:05
     */
    public static function insertRole($adminId, $roleIds)
    {
        if (!empty($roleIds)) {
            // 角色
            $roleData = [];
            foreach ($roleIds as $roleId) {
                $roleData[] = [
                    'admin_id' => $adminId,
                    'role_id'  => $roleId,
                ];
            }
            (new TenantAdminRole())->saveAll($roleData);
        }
    }


    /**
     * @notes 新增部门
     * @param $adminId
     * @param $deptIds
     * @return void
     * @throws \Exception
     * @author JXDN
     * @date 2024/09/06 18:05
     */
    public static function insertDept($adminId, $deptIds)
    {
        // 部门
        if (!empty($deptIds)) {
            $deptData = [];
            foreach ($deptIds as $deptId) {
                $deptData[] = [
                    'admin_id' => $adminId,
                    'dept_id'  => $deptId
                ];
            }
            (new TenantAdminDept())->saveAll($deptData);
        }
    }


    /**
     * @notes 新增岗位
     * @param $adminId
     * @param $jobsIds
     * @return void
     * @throws \Exception
     * @author JXDN
     * @date 2024/09/06 18:05
     */
    public static function insertJobs($adminId, $jobsIds)
    {
        // 岗位
        if (!empty($jobsIds)) {
            $jobsData = [];
            foreach ($jobsIds as $jobsId) {
                $jobsData[] = [
                    'admin_id' => $adminId,
                    'jobs_id'  => $jobsId
                ];
            }
            (new TenantAdminJobs())->saveAll($jobsData);
        }
    }

    /**
     * @notes 根据加密规则创建密码
     * @param $password
     * @return string
     * @author yfdong
     * @date 2024/09/04 22:53
     */
    public static function createPassword($password)
    {
        // 密码盐
        $passwordSalt = Config::get('project.unique_identification');
        return create_password($password, $passwordSalt);
    }

    /**
     * @notes 初始化管理员账号
     * @param mixed $id
     * @param $name
     * @return TenantAdmin|\think\Model
     * @author yfdong
     * @date 2024/09/05 22:52
     */
    public static function initialization(mixed $id, $sn, $params)
    {
        // 获取配置中的默认密码
        $defaultPassword = Config::get('project.default_password');
        // 初始化管理员账号
        return TenantAdmin::create([
            'tenant_id' => $id,
            'account'   => $params['account'] ?: $sn,
            'name'      => '超级管理员',
            'password'  => self::createPassword($params['password'] ?: $defaultPassword),
            'avatar'    => '',
            'disable'   => 0,
            'root'      => 1
        ]);
    }
}