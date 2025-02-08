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

namespace app\tenantapi\logic\marketing;

use app\common\service\ConfigService;

/**
 * 配置类逻辑层
 * Class ConfigLogic
 * @package app\tenantapi\logic
 */
class MarketingConfigLogic
{
    /**
     * @notes 获取营销配置
     * @return array
     * @author yfdong
     * @date 2024/11/16 00:13
     */
    public static function getMarketingConfig(): array
    {
        return [
            // 算力单位
            'unit' => ConfigService::get('marketing', 'unit', '算力'),
        ];
    }

    /**
     * @notes 设置营销配置
     * @param mixed $params
     * @return true
     * @author yfdong
     * @date 2024/11/16 00:15
     */
    public static function setMarketingConfig(mixed $params)
    {
        // 算力单位
        ConfigService::set('marketing', 'unit', $params['unit']);
        return true;
    }

    /**
     * @notes 获取注册赠送算力配置
     * @return array
     * @author yfdong
     * @date 2024/11/16 00:23
     */
    public static function getGiftConfig()
    {
        return [
            // 注册赠送算力开关
            'gift_switch' => ConfigService::get('marketing', 'gift_switch', 0),
            // 注册赠送算力数量
            'gift_amount' => ConfigService::get('marketing', 'gift_amount', 0),
        ];
    }


    /**
     * @notes 获取注册赠送算力配置
     * @return true
     * @author yfdong
     * @date 2024/11/16 00:23
     */
    public static function setGiftConfig($params)
    {
        // 注册赠送算力开关
        ConfigService::set('marketing', 'gift_switch', $params['gift_switch']);
        // 注册赠送算力数量
        if(isset($params['gift_amount'])){
            ConfigService::set('marketing', 'gift_amount', $params['gift_amount']);
        }
        return true;
    }


}