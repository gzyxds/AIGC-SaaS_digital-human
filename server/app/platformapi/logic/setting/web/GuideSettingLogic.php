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

namespace app\platformapi\logic\setting\web;


use app\common\logic\BaseLogic;
use app\common\service\ConfigService;
use app\common\service\FileService;


/**
 * 网站设置
 * Class WebSettingLogic
 * @package app\platformapi\logic\setting
 */
class GuideSettingLogic extends BaseLogic
{
    /**
     * @notes 设置充值引导
     * @param array $params
     * @return void
     * @author yfdong
     * @date 2024/11/11 23:50
     */
    public static function setGuide(array $params)
    {
        if (isset($params['tips'])) {
            ConfigService::set('guide', 'tips', $params['tips']);
        }
        if (isset($params['link'])) {
            ConfigService::set('guide', 'link', $params['link']);
        }
        if (isset($params['QRCode'])) {
            ConfigService::set('guide', 'QRCode', FileService::getFileUrl($params['QRCode']));
        }
    }


    /**
     * @notes 获取充值引导
     * @return array
     * @author yfdong
     * @date 2024/11/11 23:50
     */
    public static function getGuide(): array
    {
        return [
            'tips'   => ConfigService::get('guide', 'tips'),
            'link'   => ConfigService::get('guide', 'link'),
            'QRCode' => ConfigService::get('guide', 'QRCode'),
        ];
    }
}