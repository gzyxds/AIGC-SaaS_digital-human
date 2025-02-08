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


use app\common\enum\LoginEnum;
use app\common\enum\notice\NoticeEnum;
use app\common\model\user\User;
use app\common\service\sms\SmsDriver;
use app\common\validate\BaseValidate;

/**
 * 注册验证器
 * Class RegisterValidate
 * @package app\api\validate
 */
class RegisterValidate extends BaseValidate
{

    protected $regex = [
        'password' => '/^(?=.*[0-9])(?=.*[a-zA-Z]|.*[\W_])[0-9a-zA-Z\W_]{6,20}$/'
    ];

    protected $rule = [
        'channel'          => 'require',
        'account'          => 'require|length:8,20|unique:' . User::class,
        'password'         => 'require|length:6,20|regex:password',
        'password_confirm' => 'require|confirm',
        'scene'            => 'require|in:' . LoginEnum::ACCOUNT_PASSWORD . ',' . LoginEnum::MOBILE_CAPTCHA . '|checkScene'
    ];

    protected $message = [
        'channel.require'          => '注册来源参数缺失',
        'account.require'          => '请输入账号或手机号',
        'account.length'           => '账号或手机号须为8-20位之间',
        'account.unique'           => '账号已存在',
        'password.require'         => '请输入密码',
        'password.length'          => '密码须在6-25位之间',
        'password.regex'           => '密码须为数字+字母或符号组合',
        'password_confirm.require' => '请确认密码',
        'password_confirm.confirm' => '两次输入的密码不一致',
        'scene.require'            => '场景值不能为空',
    ];

    public function checkCode($data)
    {
        $smsDriver = new SmsDriver();
        $result = $smsDriver->verify($data['account'], $data['code'], NoticeEnum::REGISTER_CAPTCHA);
        if ($result) {
            return true;
        }
        return '验证码不正确，请重新输入';
    }

    public function checkScene($scene, $rule, $data)
    {
        // 账号密码登录
        if (LoginEnum::MOBILE_CAPTCHA == $scene) {
            if (!isset($data['code']) || empty($data['code'])) {
                return '请输入验证码';
            }
            return $this->checkCode($data);
        }

        return true;
    }
}