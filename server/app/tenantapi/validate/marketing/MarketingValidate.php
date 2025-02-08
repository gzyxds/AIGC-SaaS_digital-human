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

namespace app\tenantapi\validate\marketing;

use app\common\validate\BaseValidate;


/**
 * 部门验证器
 * Class DeptValidate
 * @package app\tenantapi\validate\dept
 */
class MarketingValidate extends BaseValidate
{

    protected $rule = [
        'unit' => 'require',
        'gift_switch' => 'require|in:0,1',
    ];


    protected $message = [
        'unit.require' => '单位缺失',
        'gift_switch.require' => '登录注册开关配置不能为空',
        'gift_switch.in' => '登录注册开关选择错误',
    ];


    /**
     * @notes 单位配置场景
     * @return MarketingValidate
     * @author yfdong
     * @date 2024/11/16 00:34
     */
    public function sceneUnit()
    {
        return $this->only(['unit']);
    }


    /**
     * @notes 场景
     * @return MarketingValidate
     * @author 段誉
     * @date 2022/5/25 18:16
     */
    public function sceneGift()
    {
        return $this->only(['gift_switch']);
    }

}