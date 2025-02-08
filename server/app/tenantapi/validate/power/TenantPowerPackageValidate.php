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

namespace app\tenantapi\validate\power;


use app\common\validate\BaseValidate;


/**
 * power验证器
 * Class TenantPowerPackageValidate
 * @package app\tenantapi\validate\power
 */
class TenantPowerPackageValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'id' => 'require',
        'cost' => 'require',
        'power' => 'require',
        'title' => 'require',
        // 'original_cost' => 'require',
        // 'recommend' => 'require',
        'gift' => 'require',
        'gift_power' => 'requireIf:gift,1',
        'note' => 'require',
        'expire_time' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'cost' => '金额',
        'power' => '算力',
        'title' => '标题',
        'original_cost' => '原价',
        // 'recommend' => '是否推荐',
        'gift' => '是否赠送',
        'gift_power' => '赠送算力',
        'note' => '备注',
        'expire_time' => '有效期',
    ];


    /**
     * @notes 添加场景
     * @return TenantPowerPackageValidate
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function sceneAdd()
    {
        return $this->only(['cost', 'power', 'title', 'gift', 'gift_power']);
    }


    /**
     * @notes 编辑场景
     * @return TenantPowerPackageValidate
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function sceneEdit()
    {
        return $this->only(['id', 'cost', 'power', 'title', 'original_cost', 'gift', 'gift_power']);
    }


    /**
     * @notes 删除场景
     * @return TenantPowerPackageValidate
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return TenantPowerPackageValidate
     * @author yfdong
     * @date 2024/10/15 21:42
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}