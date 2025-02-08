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


namespace app\api\logic\sensitive;

use app\common\logic\BaseLogic;
use app\common\service\ConfigService;

/**
 *  敏感词配置逻辑
 */
class SensitiveSettingLogic extends BaseLogic
{

    /**
     * @notes 获取配置
     * @return array
     * @author yfdong
     * @date 2024/11/20 00:15
     */
    public static function getSensitiveSetting()
    {
        return [
            // 敏感词模式
            'sensitive_mode' => ConfigService::get('sensitive', 'sensitive_mode', 1),
            'sensitive_switch' => ConfigService::get('tenant', 'sensitive_switch', 0),
        ];
    }

}