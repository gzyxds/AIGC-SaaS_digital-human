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

namespace app\tenantapi\logic\notice;

use app\common\logic\BaseLogic;
use app\common\service\ConfigService;

/**
 * 邮件配置逻辑类
 */
class EmailLogic extends BaseLogic
{
    /**
     * @notes 获取邮箱配置
     * @return array
     * @author JXDN
     * @date 2024/12/01 20:37
     */
    public static function getConfig(): array
    {
        return [
            'form_address'  => ConfigService::get('email', 'form_address', ''),
            'auth_password' => ConfigService::get('email', 'auth_password', ''),
            'smtp_host'     => ConfigService::get('email', 'smtp_host', ''),
            'smtp_port'     => ConfigService::get('email', 'smtp_port', ''),
        ]??[];
    }

    /**
     * @notes 设置邮箱配置
     * @param $params
     * @return bool
     * @author JXDN
     * @date 2024/12/01 20:37
     */
    public static function setConfig($params): bool
    {
        ConfigService::set('email', 'form_address', trim($params['form_address']??''));
        ConfigService::set('email', 'auth_password', trim($params['auth_password']??''));
        ConfigService::set('email', 'smtp_host', trim($params['smtp_host']??''));
        ConfigService::set('email', 'smtp_port', trim($params['smtp_port']??''));
        return true;
    }
}