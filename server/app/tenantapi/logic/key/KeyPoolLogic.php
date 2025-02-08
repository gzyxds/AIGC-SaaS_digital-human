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

namespace app\tenantapi\logic\key;

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
     * @notes 获取key值详情
     * @param int $id
     * @return array
     * @author yfdong
     * @date 2024/11/18 23:38
     */
    public static function detail(int $id): array
    {
        $info = TenantKeyPool::query()
            ->withoutField('delete_time')
            ->where(['id' => $id])
            ->findOrEmpty()
            ->toArray();
        if (!empty($info)) {
            $info['type_name'] = ModuleEnum::getModuleDesc($info['type']);
        }
        return $info;
    }


    /**
     * @notes Key新增
     * @param array $post
     * @return bool
     * @author yfdong
     * @date 2024/11/18 23:47
     */
    public static function add(array $post): bool
    {
        try {
            TenantKeyPool::create([
                'type' => $post['type'] ?? 1,
                'key' => $post['key'] ?? '',
                'status' => $post['status'] ?? 0,
                'remark' => $post['remark'] ?? '',
                'create_time' => time(),
                'update_time' => time()
            ]);
            return true;
        } catch (Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes Key编辑
     * @param array $post
     * @return bool
     * @author yfdong
     * @date 2024/11/18 23:47
     */
    public static function edit(array $post): bool
    {
        try {
            $keyPool = (new TenantKeyPool())->where(['id' => intval($post['id'])])->findOrEmpty();
            if (!$keyPool) {
                throw new Exception('密钥不存在了!');
            }

            TenantKeyPool::update([
                'type' => $post['type'] ?? 1,
                'key' => $post['key'] ?? '',
                'status' => $post['status'] ?? 0,
                'remark' => $post['remark'] ?? '',
            ], ['id' => intval($post['id'])]);
            return true;
        } catch (Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 删除key
     * @param int $id
     * @return bool
     * @author yfdong
     * @date 2024/11/18 23:47
     */
    public static function del(int $id): bool
    {
        try {
            $keyPool = (new TenantKeyPool())->where(['id' => $id])->findOrEmpty();
            $keyPool->delete();
            return true;
        } catch (Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    /**
     * @notes 修改key状态
     * @param int $id
     * @return bool
     * @author yfdong
     * @date 2024/11/18 23:47
     */
    public static function status(int $id): bool
    {
        try {
            $keyPool = (new TenantKeyPool())->where(['id' => $id])->findOrEmpty();
            $keyPool->status = $keyPool->status ? 0 : 1;
            TenantKeyPool::update(['status' => $keyPool->status], ['id' => $id]);
            return true;
        } catch (Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

}