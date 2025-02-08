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

namespace app\api\logic;


use app\common\logic\BaseLogic;
use app\common\model\article\Article;
use app\common\model\decorate\DecoratePage;
use app\common\model\decorate\DecorateTabbar;
use app\common\service\ConfigService;
use app\common\service\FileService;


/**
 * index
 * Class IndexLogic
 * @package app\api\logic
 */
class IndexLogic extends BaseLogic
{

    /**
     * @notes 首页数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:15
     */
    public static function getIndexData()
    {
        // 装修配置
        $decoratePage = DecoratePage::where(['type' => 1])->findOrEmpty();

        // 首页文章
        $field = [
            'id', 'title', 'desc', 'abstract', 'image',
            'author', 'click_actual', 'click_virtual', 'create_time',
        ];

        $article = Article::field($field)
            ->where(['is_show' => 1])
            ->order(['id' => 'desc'])
            ->limit(20)->append(['click'])
            ->hidden(['click_actual', 'click_virtual'])
            ->select()->toArray();

        return [
            'page'    => $decoratePage,
            'article' => $article,
        ];
    }


    /**
     * @notes 获取政策协议
     * @param string $type
     * @return array
     * @author 段誉
     * @date 2022/9/20 20:00
     */
    public static function getPolicyByType(string $type)
    {
        return [
            'title'   => ConfigService::get('agreement', $type . '_title', ''),
            'content' => ConfigService::get('agreement', $type . '_content', ''),
        ];
    }


    /**
     * @notes 装修信息
     * @param $id
     * @return array
     * @author 段誉
     * @date 2022/9/21 18:37
     */
    public static function getDecorate($type)
    {
        return DecoratePage::where(['type' => $type])->field(['type', 'name', 'data', 'meta'])
            ->findOrEmpty()->toArray();
    }


    /**
     * @notes 获取配置
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:38
     */
    public static function getConfigData()
    {
        // 底部导航
        $tabbar = DecorateTabbar::getTabbarLists();
        // 导航颜色
        $style = ConfigService::get('tabbar', 'style', config('project.decorate.tabbar_style'));
        // 登录配置
        $loginConfig = [
            // 登录方式
            'login_way'         => ConfigService::get('login', 'login_way', config('project.login.login_way')),
            // 默认登录方式
            'default_login_way' => ConfigService::get('login', 'default_login_way', config('project.login.default_login_way')),
            // 注册强制绑定手机
            'coerce_mobile'     => ConfigService::get('login', 'coerce_mobile', config('project.login.coerce_mobile')),
            // 政策协议
            'login_agreement'   => ConfigService::get('login', 'login_agreement', config('project.login.login_agreement')),
            // 第三方登录 开关
            'third_auth'        => ConfigService::get('login', 'third_auth', config('project.login.third_auth')),
            // 微信授权登录
            'wechat_auth'       => ConfigService::get('login', 'wechat_auth', config('project.login.wechat_auth')),
            // qq授权登录
            'qq_auth'           => ConfigService::get('login', 'qq_auth', config('project.login.qq_auth')),
        ];
        // 网址信息
        $website = [
            'h5_favicon' => FileService::getFileUrl(ConfigService::get('website', 'h5_favicon')),
            'shop_name'  => ConfigService::get('website', 'shop_name'),
            'shop_logo'  => FileService::getFileUrl(ConfigService::get('website', 'shop_logo')),
        ];
        // H5配置
        $webPage = [
            // 渠道状态 0-关闭 1-开启
            'status'      => ConfigService::get('web_page', 'status', 1),
            // 关闭后渠道后访问页面 0-空页面 1-自定义链接
            'page_status' => ConfigService::get('web_page', 'page_status', 0),
            // 自定义链接
            'page_url'    => ConfigService::get('web_page', 'page_url', ''),
            'url'         => request()->domain() . '/mobile',
        ];

        // 备案信息
        $copyright = ConfigService::get('copyright', 'config', []);

        //协议信息
        $agreement = [
            'service_title'   => ConfigService::get('agreement', 'service_title'),
            'service_content' => ConfigService::get('agreement', 'service_content'),
            'privacy_title'   => ConfigService::get('agreement', 'privacy_title'),
            'privacy_content' => ConfigService::get('agreement', 'privacy_content'),
        ];

        $agreement['service_content'] = get_file_domain($agreement['service_content']);
        $agreement['privacy_content'] = get_file_domain($agreement['privacy_content']);

        return [
            'domain'      => FileService::getFileUrl(),
            'style'       => $style,
            'tabbar'      => $tabbar,
            'login'       => $loginConfig,
            'website'     => $website,
            'webPage'     => $webPage,
            'share_image' => FileService::getFileUrl(ConfigService::get('mp_config', 'share_image')),
            'version'     => config('project.version'),
            'copyright'   => $copyright,
            'agreement'   => $agreement,
        ];
    }


    /**
     * @notes
     * @return array|array[]
     * @author yfdong
     * @date 2024/11/29 23:17
     */
    public static function getCustomerConfig(): array
    {
        $manualKf = ConfigService::getType('manual_kf') ?? [];
        return [
            'manual_kf' => [
                'status'       => intval($manualKf['status'] ?? 0),
                'qr_code'      => isset($manualKf['qr_code']) ? FileService::getFileUrl($manualKf['qr_code']) : '',
                'title'        => $manualKf['title'] ?? ['value' => '', 'status' => 0],
                'phone'        => $manualKf['phone'] ?? ['value' => '', 'status' => 0],
                'service_time' => $manualKf['service_time'] ?? ['value' => '', 'status' => 0],
            ],
        ] ?? [];
    }

    /**
     * @notes 获取充值引导配置
     * @return array
     * @author yfdong
     * @date 2025/01/20 23:34
     */
    public static function getGuideSetting(): array
    {
        return [
            'tips'   => ConfigService::get('guide', 'tips'),
            'link'   => ConfigService::get('guide', 'link'),
            'QRCode' => ConfigService::get('guide', 'QRCode'),
        ];
    }
}