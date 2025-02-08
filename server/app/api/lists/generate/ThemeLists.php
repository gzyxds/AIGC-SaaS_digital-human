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

namespace app\api\lists\generate;


use app\common\lists\BaseDataLists;
use app\common\lists\ListsSearchInterface;
use app\common\model\generate\TenantGenerateTheme;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;


/**
 * ThemeLists列表
 * Class TenantAiAvatarRecordLists
 * @package app\tenantapi\lists
 */
class ThemeLists extends BaseDataLists implements ListsSearchInterface
{

    /**
     * @notes 设置搜索条件
     * @return array
     * @author yfdong
     * @date 2024/12/23 23:16
     */
    public function setSearch(): array
    {
        return [];
    }

    /**
     * @notes 获取列表
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author yfdong
     * @date 2024/12/23 23:22
     */
    public function lists(): array
    {
        $list = TenantGenerateTheme::query()
            ->order(['sort' => 'desc'])
            ->limit($this->limitOffset, $this->limitLength)
            ->select()
            ->toArray();
        return $list;
    }


    /**
     * @notes 获取数量
     * @return int
     * @throws DbException
     * @author yfdong
     * @date 2024/12/23 23:22
     */
    public function count(): int
    {
        return TenantGenerateTheme::query()
            ->where($this->searchWhere)
            ->count();
    }

}