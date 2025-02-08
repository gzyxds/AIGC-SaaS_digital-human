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


namespace app\common\enum;


/**
 * 管理后台登录终端
 * Class terminalEnum
 * @package app\common\enum
 */
class AdminTerminalEnum
{
    const PC = 1;
    const MOBILE = 2;

    const PLATFORM = '__saas__platform__';
    const TENANT = '__saas__tenant__';
    const USER = '__saas__user__';

    /**
     * @notes 是否为租户端
     * @return bool
     * @author JXDN
     * @date 2024/09/04 16:44
     */
    public static function isTenant()
    {
        return request()->source === self::TENANT;
    }

    /**
     * @notes 是否为平台端
     * @return bool
     * @author JXDN
     * @date 2024/09/04 16:44
     */
    public static function isPlatform()
    {
        return request()->source === self::PLATFORM;
    }

    /**
     * @notes 是否为用户端
     * @return bool
     * @author JXDN
     * @date 2024/09/04 16:44
     */
    public static function isUser()
    {
        return request()->source === self::USER;
    }
}