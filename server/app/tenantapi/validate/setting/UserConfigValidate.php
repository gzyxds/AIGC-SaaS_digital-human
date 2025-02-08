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
namespace app\tenantapi\validate\setting;

use app\common\validate\BaseValidate;

/**
 * 用户设置验证
 * Class UserConfigValidate
 * @package app\tenantapi\validate\setting
 */
class UserConfigValidate extends BaseValidate
{

    protected $rule = [
        'register_way'      => 'require',
        'default_login_way' => 'require',
        // 'register_sms_verify' => 'require',
        'login_way'         => 'requireIf:scene,register|array',
        'coerce_mobile'     => 'requireIf:scene,register|in:0,1',
        'login_agreement'   => 'require|in:0,1',
        'third_auth'        => 'require|in:0,1',
        'wechat_auth'       => 'require|in:0,1',
        'qq_auth'           => 'require|in:0,1',
        'default_avatar'    => 'require',
    ];


    protected $message = [
        'register_way.require'      => '请选择注册方式',
        'default_login_way.require' => '请选择默认登录方式',
        // 'register_sms_verify.require' => '请选择是否强制短信验证码',
        'login_agreement.require'   => '政策协议值不能为空',
        'default_avatar.require'    => '请上传用户默认头像',
        'login_way.requireIf'       => '请选择登录方式',
        'login_way.array'           => '登录方式值错误',
        'coerce_mobile.requireIf'   => '请选择注册强制绑定手机',
        'coerce_mobile.in'          => '注册强制绑定手机值错误',
        'wechat_auth.in'            => '公众号微信授权登录值错误',
        'third_auth.in'             => '第三方登录值错误',
        'qq_auth.in'                => 'QQ授权登录值错误',
        'wechat_auth.require'       => '公众号微信授权登录值不能为空',
        'third_auth.require'        => '第三方登录值不能为空',
        'qq_auth.require'           => 'QQ微信授权登录值不能为空',
        'login_agreement.in'        => '政策协议值错误',
    ];

    //用户设置验证
    public function sceneUser()
    {
        return $this->only(['default_avatar']);
    }

    //注册验证
    public function sceneRegister()
    {
        return $this->only(['login_way', 'coerce_mobile', 'login_agreement', 'third_auth', 'register_way', 'default_login_way']);
    }
}