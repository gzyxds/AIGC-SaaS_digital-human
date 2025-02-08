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

namespace app\api\controller;

use app\api\logic\WorkbenchLogic;
use app\common\model\avatar\TenantAiAvatarRecord;
use app\common\model\user\User;
use app\common\model\user\UserAccountLog;
use app\common\model\video\TenantVideo;
use app\common\model\voice\TenantVoice;
use app\common\model\voicerecord\TenantVoiceRecord;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;


/**
 * 首页控制台统计
 */
class WorkbenchController extends BaseApiController
{

    /**
     * @notes 用户筛选
     * @return array
     * @author yfdong
     * @date 2024/12/01 00:03
     */
    public function userScreen(): array
    {
        // 指定用户
        $where[] = ['uid', '=', $this->userId];
        return $where;
    }


    /**
     * @notes 今日筛选
     * @return array
     * @author yfdong
     * @date 2024/12/01 00:03
     */
    public function todayScreen(): array
    {
        // 今日筛选
        $where[] = ['create_time', '>', strtotime(date('Y-m-d') . '00:00:00')];
        $where[] = ['create_time', '<', strtotime(date('Y-m-d') . '23:59:59')];
        return $where;
    }

    /**
     * @notes 昨日筛选
     * @return array
     * @author yfdong
     * @date 2024/12/01 00:03
     */
    public function yesterdayScreen(): array
    {
        // 今日筛选
        $where[] = ['create_time', '>', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'))];
        $where[] = ['create_time', '<', mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1];
        return $where;
    }


    /**
     * @notes 统计信息
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author yfdong
     * @date 2024/10/28 21:49
     */
    public function static(): Json
    {
        $static = [];
        // 声音克隆
        $static [] = [
            'label' => "声音克隆",
            'all'   => TenantVoice::query()->where($this->userScreen())->count(),
            'today' => TenantVoice::query()->where($this->userScreen())->where($this->todayScreen())->count(),
        ];
        // 声音合成
        $static [] = [
            'label' => "声音合成",
            'all'   => TenantVoiceRecord::query()->where($this->userScreen())->count(),
            'today' => TenantVoiceRecord::query()->where($this->userScreen())->where($this->todayScreen())->count(),
        ];
        // 数字形象
        $static [] = [
            'label' => "数字形象",
            'all'   => TenantVideo::query()->where($this->userScreen())->count(),
            'today' => TenantVideo::query()->where($this->userScreen())->where($this->todayScreen())->count(),
        ];
        // 数字分身
        $static [] = [
            'label' => "数字分身",
            'all'   => TenantAiAvatarRecord::query()->where($this->userScreen())->count(),
            'today' => TenantAiAvatarRecord::query()->where($this->userScreen())->where($this->todayScreen())->count(),
        ];
        return $this->data($static);
    }


    /**
     * @notes 用户算力统计
     * @return Json
     * @author yfdong
     * @date 2024/12/01 00:56
     */
    public function userPowerStatic(): Json
    {
        $static = [];
        $user = User::query()->where(['id' => $this->userId])
            ->field('id,user_money')
            ->findOrEmpty();
        // 今日变化数量 增加 减少
        $addPower = UserAccountLog::query()->where(['user_id' => $this->userId])->where($this->todayScreen())->where('action', 1)->sum('change_amount');
        $decPower = UserAccountLog::query()->where(['user_id' => $this->userId])->where($this->todayScreen())->where('action', 2)->sum('change_amount');
        // 算力余额
        $static [] = [
            'label'        => "算力余额",
            // 总数
            'amount'       => $user['user_money'],
            // 类型 1 增加 2 减少 3 相等
            'type'         => $addPower < $decPower ? 2 : ($addPower > $decPower ? 1 : 3),
            // 变动数量
            'changeAmount' => (string)Abs($addPower - $decPower),
        ];

        // 昨日变化数量
        $yesterdayDecPower = UserAccountLog::query()->where(['user_id' => $this->userId])->where($this->yesterdayScreen())->where('action', 2)->sum('change_amount');
        // 今日用量
        $static [] = [
            'label'        => "今日用量",
            // 今日消耗数量
            'amount'       => $decPower,
            // 类型 1 增加 2 减少 3 相等
            'type'         => $decPower < $yesterdayDecPower ? 2 : ($decPower > $yesterdayDecPower ? 1 : 3),
            // 变动数量
            'changeAmount' => $yesterdayDecPower == 0 ? "100.00%" : bcdiv(Abs($yesterdayDecPower - $decPower) * 100, $yesterdayDecPower, 2) . "%",
        ];
        return $this->data($static);
    }


    /**
     * @notes 首页作品趋势数据
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author yfdong
     * @date 2024/11/26 23:13
     */
    public function workChart()
    {
        $result [] = WorkbenchLogic::workAvatarChart();
        $result [] = WorkbenchLogic::workVocieChart();
        return $this->data($result);
    }
}