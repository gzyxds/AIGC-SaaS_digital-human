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

namespace app\tenantapi\validate\generate;


use app\common\validate\BaseValidate;


/**
 * TenantAiAvatarRecord验证器
 * Class TenantAiAvatarRecordValidate
 * @package app\tenantapi\validate\avatar
 */
class GenerateValidate extends BaseValidate
{

     /**
      * 设置校验规则
      * @var string[]
      */
    protected $rule = [
        'id' => 'require',
        'uid' => 'require',
        'theme' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'uid' => '用户ID',
        'theme' => '请输入主题',
    ];


    /**
     * @notes 添加场景
     * @return GenerateValidate
     * @author yfdong
     * @date 2024/12/23 00:21
     */
    public function sceneAdd()
    {
        return $this->only(['uid','theme']);
    }


    /**
     * @notes 编辑场景
     * @return GenerateValidate
     * @author yfdong
     * @date 2024/12/23 00:22
     */
    public function sceneEdit()
    {
        return $this->only(['id','uid']);
    }


    /**
     * @notes 删除场景
     * @return GenerateValidate
     * @author yfdong
     * @date 2024/12/23 00:22
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return GenerateValidate
     * @author yfdong
     * @date 2024/12/23 00:22
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}