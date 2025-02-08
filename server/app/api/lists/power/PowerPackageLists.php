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

namespace app\api\lists\power;


use app\api\lists\BaseApiDataLists;
use app\common\model\power\TenantPowerPackage;


/**
 * power列表
 * Class TenantPowerPackageLists
 * @package app\tenantapi\listspower
 */
class PowerPackageLists extends BaseApiDataLists
{

    public function queryWhere()
    {
        // 指定有效状态
        $where[] = ['status', '=', '1'];
        return $where;
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
            ->where($this->queryWhere())
            ->field(['id', 'title', 'cost', 'power', 'original_cost', 'recommend', 'gift', 'gift_power', 'sort', 'expire_time', 'note', 'status', 'create_time'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['sort' => 'desc', 'id' => 'desc'])
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
        return TenantPowerPackage::query()->where($this->queryWhere())->count();
    }

}