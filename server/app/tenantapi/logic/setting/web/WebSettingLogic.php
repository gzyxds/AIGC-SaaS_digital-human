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

namespace app\tenantapi\logic\setting\web;


use app\common\logic\BaseLogic;
use app\common\service\ConfigService;
use app\common\service\FileService;


/**
 * 网站设置
 * Class WebSettingLogic
 * @package app\tenantapi\logic\setting
 */
class WebSettingLogic extends BaseLogic
{

    /**
     * @notes 获取网站信息
     * @return array
     * @author 段誉
     * @date 2021/12/28 15:43
     */
    public static function getWebsiteInfo(): array
    {
        return [
            'name'        => ConfigService::get('tenant', 'name'),
            'web_favicon' => FileService::getFileUrl(ConfigService::get('tenant', 'web_favicon')),
            'web_logo'    => FileService::getFileUrl(ConfigService::get('tenant', 'web_logo')),
            'login_image' => FileService::getFileUrl(ConfigService::get('tenant', 'login_image')),

            'shop_name' => ConfigService::get('website', 'shop_name'),
            'shop_logo' => FileService::getFileUrl(ConfigService::get('website', 'shop_logo')),

            'pc_logo'     => FileService::getFileUrl(ConfigService::get('website', 'pc_logo')),
            'pc_title'    => ConfigService::get('website', 'pc_title', ''),
            'pc_ico'      => FileService::getFileUrl(ConfigService::get('website', 'pc_ico')),
            'pc_desc'     => ConfigService::get('website', 'pc_desc', ''),
            'pc_keywords' => ConfigService::get('website', 'pc_keywords', ''),
            'h5_favicon'  => FileService::getFileUrl(ConfigService::get('website', 'h5_favicon')),
        ];
    }


    /**
     * @notes 设置网站信息
     * @param array $params
     * @author 段誉
     * @date 2021/12/28 15:43
     */
    public static function setWebsiteInfo(array $params)
    {
        $h5favicon = FileService::setFileUrl($params['h5_favicon']);
        $favicon = FileService::setFileUrl($params['web_favicon']);
        $logo = FileService::setFileUrl($params['web_logo']);
        $login = FileService::setFileUrl($params['login_image']);
        $shopLogo = FileService::setFileUrl($params['shop_logo']);
        $pcLogo = FileService::setFileUrl($params['pc_logo']);
        $pcIco = FileService::setFileUrl($params['pc_ico'] ?? '');

        ConfigService::set('tenant', 'name', $params['name']);
        ConfigService::set('tenant', 'web_favicon', $favicon);
        ConfigService::set('tenant', 'web_logo', $logo);
        ConfigService::set('tenant', 'login_image', $login);

        ConfigService::set('website', 'pc_logo', $pcLogo);
        ConfigService::set('website', 'pc_title', $params['pc_title']);
        ConfigService::set('website', 'pc_ico', $pcIco);
        ConfigService::set('website', 'pc_desc', $params['pc_desc'] ?? '');
        ConfigService::set('website', 'pc_keywords', $params['pc_keywords'] ?? '');

        ConfigService::set('website', 'shop_name', $params['shop_name']);
        ConfigService::set('website', 'shop_logo', $shopLogo);
        ConfigService::set('website', 'h5_favicon', $h5favicon);
    }


    /**
     * @notes 获取版权备案
     * @return array
     * @author 段誉
     * @date 2021/12/28 16:09
     */
    public static function getCopyright(): array
    {
        return ConfigService::get('copyright', 'config', []);
    }


    /**
     * @notes 设置版权备案
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2022/8/8 16:33
     */
    public static function setCopyright(array $params)
    {
        try {
            if (!is_array($params['config'])) {
                throw new \Exception('参数异常');
            }
            ConfigService::set('copyright', 'config', $params['config'] ?? []);
            return true;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 设置政策协议
     * @param array $params
     * @author ljj
     * @date 2022/2/15 10:59 上午
     */
    public static function setAgreement(array $params)
    {
        $serviceContent = clear_file_domain($params['service_content'] ?? '');
        $privacyContent = clear_file_domain($params['privacy_content'] ?? '');
        $useContent = clear_file_domain($params['use_content'] ?? '');
        $rechargeContent = clear_file_domain($params['recharge_content'] ?? '');
        $unitContent = clear_file_domain($params['currency_content'] ?? '');
        $disclaimerContent = clear_file_domain($params['disclaimer_content'] ?? '');
        // 用户协议
        ConfigService::set('agreement', 'service_title', $params['service_title'] ?? '');
        ConfigService::set('agreement', 'service_content', $serviceContent);
        // 隐私协议
        ConfigService::set('agreement', 'privacy_title', $params['privacy_title'] ?? '');
        ConfigService::set('agreement', 'privacy_content', $privacyContent);
        // 使用协议
        ConfigService::set('agreement', 'use_title', $params['use_title'] ?? '');
        ConfigService::set('agreement', 'use_content', $useContent);
        // 充值协议
        ConfigService::set('agreement', 'recharge_title', $params['recharge_title'] ?? '');
        ConfigService::set('agreement', 'recharge_content', $rechargeContent);
        // 货币说明
        ConfigService::set('agreement', 'currency_title', $params['currency_title'] ?? '');
        ConfigService::set('agreement', 'currency_content', $unitContent);
        // 免责声明
        ConfigService::set('agreement', 'disclaimer_title', $params['disclaimer_title'] ?? '');
        ConfigService::set('agreement', 'disclaimer_content', $disclaimerContent);
    }


    /**
     * @notes 获取政策协议
     * @return array
     * @author
     * @date
     */
    public static function getAgreement(): array
    {
        $config = [
            'service_title'      => ConfigService::get('agreement', 'service_title'),
            'service_content'    => ConfigService::get('agreement', 'service_content'),
            'privacy_title'      => ConfigService::get('agreement', 'privacy_title'),
            'privacy_content'    => ConfigService::get('agreement', 'privacy_content'),
            'use_title'          => ConfigService::get('agreement', 'use_title'),
            'use_content'        => ConfigService::get('agreement', 'use_content'),
            'recharge_title'     => ConfigService::get('agreement', 'recharge_title'),
            'recharge_content'   => ConfigService::get('agreement', 'recharge_content'),
            'currency_title'     => ConfigService::get('agreement', 'currency_title'),
            'currency_content'   => ConfigService::get('agreement', 'currency_content'),
            'disclaimer_title'   => ConfigService::get('agreement', 'disclaimer_title'),
            'disclaimer_content' => ConfigService::get('agreement', 'disclaimer_content'),
        ];

        $config['service_content'] = get_file_domain($config['service_content']);
        $config['privacy_content'] = get_file_domain($config['privacy_content']);
        $config['use_content'] = get_file_domain($config['use_content']);
        $config['recharge_content'] = get_file_domain($config['recharge_content']);
        $config['currency_content'] = get_file_domain($config['currency_content']);
        $config['disclaimer_content'] = get_file_domain($config['disclaimer_content']);

        return $config;
    }


    /**
     * @notes 获取站点统计配置
     * @return array
     * @author yfdong
     * @date 2024/09/20 22:25
     */
    public static function getSiteStatistics()
    {
        return [
            'clarity_code' => ConfigService::get('siteStatistics', 'clarity_code')
        ];
    }

    /**
     * @notes 设置站点统计配置
     * @param array $params
     * @return void
     * @author yfdong
     * @date 2024/09/20 22:31
     */
    public static function setSiteStatistics(array $params)
    {
        ConfigService::set('siteStatistics', 'clarity_code', $params['clarity_code']);
    }
}