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

namespace app\tenantapi\lists\sensitive;


use app\common\lists\BaseDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\sensitive\TenantSensitiveWords;


/**
 * 敏感词列表
 * Class TenantVoiceLists
 * @package app\tenantapi\listsvoice
 */
class WordsLists extends BaseDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author yfdong
     */
    public function setSearch(): array
    {
        return [
            '=' => ['tenant_id', 'status'],
            '%like%' => ['words']
        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author yfdong
     */
    public function lists(): array
    {
        $list = TenantSensitiveWords::query()
            ->where($this->searchWhere)
            ->field(['id', 'tenant_id', 'words', 'status', 'create_time'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['create_time' => 'desc'])
            ->select()
            ->toArray();
        return $list;
    }


    /**
     * @notes 获取数量
     * @return int
     * @throws \think\db\exception\DbException
     * @author yfdong
     */
    public function count(): int
    {
        return TenantSensitiveWords::query()->where($this->searchWhere)->count();
    }


}