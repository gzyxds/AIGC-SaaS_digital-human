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

namespace app\platformapi\validate\setting;

use app\common\validate\BaseValidate;

/**
 * 网站设置验证器
 * Class WebSettingValidate
 * @package app\tenantapi\validate\setting
 */
class ApiSettingValidate extends BaseValidate
{
    protected $rule = [
        'key' => 'require',
        'sign' => 'require',
        'accessKey' => 'require',
        'accessSecret' => 'require',
    ];

    protected $message = [
        'key.require' => '请输入数字人平台key',
        'sign.require' => '请选择是否加密',
        'accessKey.require' => '请输入阿里云平台key',
        'accessSecret.require' => '请输入阿里云加密私钥',
    ];

    protected $scene = [
        'apiSet' => ['key', 'sign'],
        'ali'    => ['accessKey', 'accessSecret']
    ];
}