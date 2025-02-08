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

namespace app\tenantapi\validate\video;


use app\common\validate\BaseValidate;


/**
 * TenantVideo验证器
 * Class TenantVideoValidate
 * @package app\tenantapi\validate\video
 */
class TenantVideoValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'id'      => 'require',
        'name'    => 'require',
        'file_id' => 'require',
        'errcode' => 'require',
        'mode_id' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id'      => 'id',
        'name'    => '标题',
        'file_id' => '视频文件',
        'errcode' => '状态码',
        'mode_id' => '模型id',
    ];


    /**
     * @notes 添加场景
     * @return TenantVideoValidate
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function sceneAdd()
    {
        return $this->only(['name', 'file_id']);
    }


    /**
     * @notes 编辑场景
     * @return TenantVideoValidate
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function sceneEdit()
    {
        return $this->only(['id', 'tenant_id', 'uid']);
    }


    /**
     * @notes 删除场景
     * @return TenantVideoValidate
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return TenantVideoValidate
     * @author yfdong
     * @date 2024/10/09 22:19
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    /**
     * @notes 通知接口参数限制
     * @return TenantVideoValidate
     * @author yfdong
     * @date 2024/12/18 23:22
     */
    public function sceneReceive()
    {
        return $this->only(['errcode', 'mode_id']);
    }

}