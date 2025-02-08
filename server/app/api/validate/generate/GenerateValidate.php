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

namespace app\api\validate\generate;


use app\api\validate\voicerecord\VoiceRecordValidate;
use app\common\validate\BaseValidate;


/**
 * GenerateValidate验证器
 * Class GenerateValidate
 * @package app\api\validate\generate
 */
class GenerateValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'theme'   => 'require',
        'content' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'theme'   => 'AI合成主题',
        'content' => '合成内容描述',
    ];


    /**
     * 生成AI文案新增校验
     * @return GenerateValidate
     */
    public function sceneAdd()
    {
        return $this->only(['theme', 'content']);
    }
}