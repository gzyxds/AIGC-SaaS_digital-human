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

namespace app\platformapi\lists\upgrade;

use app\platformapi\lists\BaseAdminDataLists;
use app\platformapi\logic\upgrade\UpgradeLogic;

/**
 * 系统更新列表
 */
class UpgradeLists extends BaseAdminDataLists
{
    /**
     * @notes 查看系统更新列表
     * @return array
     * @author 段誉
     * @date 2021/8/14 17:16
     */
    public function lists(): array
    {
        $lists = UpgradeLogic::getRemoteVersion($this->pageNo, $this->pageSize)['lists'] ?? [];
        if (empty($lists)) {
            return $lists;
        }
        return UpgradeLogic::formatLists($lists, $this->pageNo);
    }

    /**
     * @notes 查看系统更新列表总数
     * @return int
     * @author 段誉
     * @date 2021/8/14 17:15
     */
    public function count(): int
    {
        $result = UpgradeLogic::getRemoteVersion($this->limitOffset, $this->limitLength);
        return $result['count'] ?? 0;
    }
}