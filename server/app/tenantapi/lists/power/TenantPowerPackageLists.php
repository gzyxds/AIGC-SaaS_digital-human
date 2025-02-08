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

namespace app\tenantapi\lists\power;


use app\common\lists\BaseDataLists;
use app\common\model\power\TenantPowerPackage;
use app\common\lists\ListsSearchInterface;


/**
 * power列表
 * Class TenantPowerPackageLists
 * @package app\tenantapi\listspower
 */
class TenantPowerPackageLists extends BaseDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function setSearch(): array
    {
        return [
            '=' => ['tenant_id', 'status'],
        ];
    }


    /**
     * @notes 获取power列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function lists(): array
    {
        return TenantPowerPackage::query()
            ->where($this->searchWhere)
            ->field(['id', 'tenant_id', 'title', 'cost', 'power', 'original_cost', 'recommend', 'gift', 'gift_power', 'sort', 'expire_time', 'note', 'status', 'create_time'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['sort' => 'desc'])
            ->select()
            ->toArray();
    }


    /**
     * @notes 获取power数量
     * @return int
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function count(): int
    {
        return TenantPowerPackage::query()->where($this->searchWhere)->count();
    }

}