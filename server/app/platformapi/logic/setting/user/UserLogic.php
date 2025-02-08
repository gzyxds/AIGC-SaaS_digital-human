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
namespace app\platformapi\logic\setting\user;

use app\common\service\ConfigService;
use app\common\service\{FileService};

/**
 * 设置-用户设置逻辑层
 * Class TenantLogic
 * @package app\platformapi\logic\config
 */
class UserLogic
{

    /**
     * @notes 获取用户设置
     * @return array
     * @author 段誉
     * @date 2022/3/29 10:09
     */
    public static function getConfig(): array
    {
        $defaultAvatar = config('project.default_image.user_avatar');
        $config = [
            //默认头像
            'default_avatar' => FileService::getFileUrl(ConfigService::get('default_image', 'user_avatar', $defaultAvatar)),
        ];
        return $config;
    }


    /**
     * @notes 设置用户设置
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2022/3/29 10:09
     */
    public function setConfig(array $params): bool
    {
        $avatar = FileService::setFileUrl($params['default_avatar']);
        ConfigService::set('default_image', 'user_avatar', $avatar);
        return true;
    }


    /**
     * @notes 获取注册配置
     * @return array
     * @author 段誉
     * @date 2022/3/29 10:10
     */
    public function getRegisterConfig(): array
    {
        $config = [
            // 注册方式
            'register_way'        => $config['register_way'] ?? config('project.login.register_way'),
            // 默认登录方式: 1-手机号登录; 2-邮箱登录, 3-微信
            'default_login_way'   => ConfigService::get('login', 'default_login_way', config('project.login.default_login_way')),
            // 短信验证码
            'register_sms_verify' => ConfigService::get('login', 'register_sms_verify', config('project.login.register_sms_verify')),
            // 登录方式
            'login_way'           => ConfigService::get('login', 'login_way', config('project.login.login_way')),
            // 注册强制绑定手机
            'coerce_mobile'       => ConfigService::get('login', 'coerce_mobile', config('project.login.coerce_mobile')),
            // 政策协议
            'login_agreement'     => ConfigService::get('login', 'login_agreement', config('project.login.login_agreement')),
            // 第三方登录 开关
            'third_auth'          => ConfigService::get('login', 'third_auth', config('project.login.third_auth')),
            // 微信授权登录
            'wechat_auth'         => ConfigService::get('login', 'wechat_auth', config('project.login.wechat_auth')),
            // qq授权登录
            'qq_auth'             => ConfigService::get('login', 'qq_auth', config('project.login.qq_auth')),
        ];
        return $config;
    }


    /**
     * @notes 设置登录注册
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2022/3/29 10:10
     */
    public static function setRegisterConfig(array $params): bool
    {
        // 注册方式: [1=手机号注册, 2-邮箱注册]
        ConfigService::set('login', 'register_way', $params['register_way']);
        // 默认登录方式:  1-手机号登录; 2-邮箱登录, 3-微信登录/公众号授权登录;
        ConfigService::set('login', 'default_login_way', $params['default_login_way']);
        // 短信验证码
        ConfigService::set('login', 'register_sms_verify', $params['register_sms_verify']);
        // 登录方式：1-账号密码登录；2-手机短信验证码登录
        ConfigService::set('login', 'login_way', $params['login_way']);
        // 注册强制绑定手机
        ConfigService::set('login', 'coerce_mobile', $params['coerce_mobile']);
        // 政策协议
        ConfigService::set('login', 'login_agreement', $params['login_agreement']);
        // 第三方授权登录
        ConfigService::set('login', 'third_auth', $params['third_auth']);
        // 微信授权登录
        ConfigService::set('login', 'wechat_auth', $params['wechat_auth']);
        // qq登录
        ConfigService::set('login', 'qq_auth', $params['qq_auth']);
        return true;
    }

}