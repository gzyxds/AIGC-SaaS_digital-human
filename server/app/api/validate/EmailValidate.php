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

namespace app\api\validate;

use app\common\validate\BaseValidate;

/**
 * 邮件发送参数验证
 */
class EmailValidate extends BaseValidate
{
    protected $rule = [
        'email' => 'require|email',
        'scene' => 'require',
    ];

    protected $message = [
        'email.require' => '请输入邮箱',
        'email.email'   => '邮箱错误',
        'scene.require' => '请输入场景值',
    ];

    public function sceneSendCode(): EmailValidate
    {
        return $this->only(['email','scene']);
    }
}