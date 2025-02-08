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

namespace app\tenantapi\lists\key;

use app\common\enum\ModuleEnum;
use app\tenantapi\lists\BaseAdminDataLists;
use app\common\model\key\TenantKeyPool;

/**
 * Key池列表
 */
class KeyPoolLists extends BaseAdminDataLists
{
    /**
     * @notes 搜索条件
     * @return array
     * @author yfdong
     * @date 2024/11/18 23:40
     */
    public function setSearch(): array
    {
        $where = [];

        if (isset($this->params['type']) && $this->params['type']) {
            $where[] = ['type', '=', $this->params['type']];
        }

        if (isset($this->params['keyword']) && $this->params['keyword']) {
            $where[] = ['key', 'like', '%' . $this->params['keyword'] . '%'];
        }

        if (isset($this->params['start_time']) && $this->params['start_time']) {
            $where[] = ['create_time', '>=', strtotime($this->params['start_time'])];
        }

        if (isset($this->params['end_time']) && $this->params['end_time']) {
            $where[] = ['create_time', '<=', strtotime($this->params['end_time'])];
        }

        if (isset($this->params['status']) && $this->params['status'] != '') {
            $where[] = ['status', '=', intval($this->params['status'])];
        }

        return $where;
    }

    /**
     * @notes key池子列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author yfdong
     * @date 2024/11/18 23:42
     */
    public function lists(): array
    {
        $lists = TenantKeyPool::query()
            ->where($this->setSearch())
            ->field('id,tenant_id,type,key,remark,status,create_time,update_time')
            ->limit($this->limitOffset, $this->limitLength)
            ->order('create_time desc')
            ->select()
            ->toArray();
        foreach ($lists as &$item) {
            $item['type_name'] = ModuleEnum::getModuleDesc($item['type']);
        }
        return $lists;
    }

    /**
     * @notes 数量
     * @return int
     * @throws \think\db\exception\DbException
     * @author yfdong
     * @date 2024/11/18 23:43
     */
    public function count(): int
    {
        return TenantKeyPool::query()
            ->where($this->setSearch())
            ->count();
    }
}