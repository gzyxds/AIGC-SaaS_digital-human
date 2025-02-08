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
use app\common\model\avatar\TenantAiAvatarRecord;
use app\common\model\decorate\DecoratePage;
use app\common\model\decorate\DecorateTabbar;
use app\common\model\voicerecord\TenantVoiceRecord;
use app\common\service\ConfigService;
use app\common\service\FileService;


/**
 * 工作台
 * Class IndexLogic
 * @package app\api\logic
 */
class WorkbenchLogic extends BaseLogic
{


    /**
     * @notes 数字人作品数量趋势
     * @return array
     * @author yfdong
     * @date 2024/11/26 23:13
     */
    public static function workAvatarChart()
    {
        $num = [];
        $date = [];
        for ($i = 0; $i < 15; $i++) {
            $where_start = strtotime("- " . $i . "day");
            $date[] = date('m/d', $where_start);
            $startTime = strtotime(date('Y-m-d', $where_start) . '00:00:00');
            $endTime = strtotime(date('Y-m-d', $where_start) . '23:59:59');
            // 数字人数量
            $avatar = TenantAiAvatarRecord::query()->where(['uid' => request()->userId])->whereBetween('create_time', [$startTime, $endTime])->count();
            $num[] = $avatar;
        }
        return [
            'name' => '数字人合成作品',
            'type' => 'line',
            'date' => $date,
            'list' => $num
        ];
    }

    /**
     * @notes 声音合成作品数量趋势
     * @return array
     * @throws \think\db\exception\DbException
     * @author yfdong
     * @date 2024/11/26 23:23
     */
    public static function workVocieChart()
    {
        $num = [];
        $date = [];
        for ($i = 0; $i < 15; $i++) {
            $where_start = strtotime("- " . $i . "day");
            $date[] = date('m/d', $where_start);
            $startTime = strtotime(date('Y-m-d', $where_start) . '00:00:00');
            $endTime = strtotime(date('Y-m-d', $where_start) . '23:59:59');
            // 声音合成数量
            $voice = TenantVoiceRecord::query()->where(['uid' => request()->userId])->whereBetween('create_time', [$startTime, $endTime])->count();
            $num[] = $voice;
        }
        return [
            'name' => '声音合成作品',
            'type' => 'line',
            'date' => $date,
            'list' => $num
        ];

    }
}