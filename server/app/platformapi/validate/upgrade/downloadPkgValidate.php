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

namespace app\platformapi\validate\upgrade;

use app\platformapi\logic\upgrade\UpgradeLogic;
use app\common\validate\BaseValidate;

/**
 * 系统更新-获取更新包链接
 */
class downloadPkgValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|checkVersionData',
        'update_type' => 'require'
    ];

    protected $message = [
        'id.require' => '参数缺失',
        'update_type.require' => '参数缺失',
    ];

    /**
     * @notes 验证版本信息
     * @param $value
     * @return bool|string
     * @author 段誉
     * @date 2021/10/9 15:05
     */
    protected function checkVersionData($value): bool|string
    {
        //目标更新版本信息
        $targetVersionData = UpgradeLogic::getVersionDataById($value);

        if (empty($targetVersionData)) {
            return '未获取到对应版本信息';
        }
        return true;
    }
}