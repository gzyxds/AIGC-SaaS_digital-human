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

namespace app\api\validate\voicerecord;


use app\common\validate\BaseValidate;


/**
 * TenantVoiceRecord验证器
 * Class TenantVoiceRecordValidate
 * @package app\tenantapi\validate\voicerecord
 */
class VoiceRecordValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'title' => 'require',
        'content' => 'require',
        'speed' => 'require|float',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'title' => '声音合成标题',
        'content' => '合成素材文本内容',
        'speed' => '合成音频语速',
    ];


    /**
     * @notes 添加场景
     * @return VoiceRecordValidate
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function sceneAdd()
    {
        return $this->only(['title', 'content', 'speed']);
    }


    /**
     * @notes 上传本地文件场景
     * @return VoiceRecordValidate
     * @author yfdong
     * @date 2025/01/08 21:26
     */
    public function sceneUpload(): VoiceRecordValidate
    {
        return $this->only(['file_id']);
    }
}