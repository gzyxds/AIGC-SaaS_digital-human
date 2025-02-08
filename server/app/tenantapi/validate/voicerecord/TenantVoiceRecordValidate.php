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

namespace app\tenantapi\validate\voicerecord;


use app\common\validate\BaseValidate;


/**
 * TenantVoiceRecord验证器
 * Class TenantVoiceRecordValidate
 * @package app\tenantapi\validate\voicerecord
 */
class TenantVoiceRecordValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'id' => 'require',
        'title' => 'require',
        'voice_id' => 'require',
        'content' => 'require',
        'speed' => 'require|float',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'title' => '声音合成',
        'voice_id' => '声音素材标识',
        'content' => '合成素材文本内容',
        'speed' => '合成音频语速',
    ];


    /**
     * @notes 添加场景
     * @return TenantVoiceRecordValidate
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function sceneAdd()
    {
        return $this->only(['voice_id', 'content', 'speed']);
    }


    /**
     * @notes 编辑场景
     * @return TenantVoiceRecordValidate
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function sceneEdit()
    {
        return $this->only(['id', 'tenant_id', 'uid']);
    }


    /**
     * @notes 删除场景
     * @return TenantVoiceRecordValidate
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return TenantVoiceRecordValidate
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}