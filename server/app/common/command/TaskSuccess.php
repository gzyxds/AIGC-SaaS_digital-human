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

namespace app\common\command;

use app\common\enum\user\AccountLogEnum;
use app\common\logic\AccountLogLogic;
use app\common\model\avatar\TenantAiAvatarRecord;
use app\common\model\video\TenantVideo;
use app\common\model\voice\TenantVoice;
use app\common\model\voicerecord\TenantVoiceRecord;
use app\tenantapi\logic\user\UserLogic;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\db\exception\DbException;
use think\facade\Log;
use think\helper;


class TaskSuccess extends Command
{
    const TIMEOUT = 3600; // 超时时间 1h

    protected function configure()
    {
        $this->setName('task_success')
            ->setDescription('合成任务自动失败');
    }


    /**
     * @notes 查询状态为合成中的合成任务
     * @param Input $input
     * @param Output $output
     * @return bool
     * @author yfdong
     * @date 2024/11/24 23:54
     */
    protected function execute(Input $input, Output $output)
    {
        try {
            Log::info("合成任务超时处理开始执行，时间：" . time());
            // 查询克隆音色任务
            $cloneRecord = TenantVoice::query()
                ->where(['status' => '0'])
                ->select()->toArray();
            // 查询声音合成任务
            $voiceRecord = TenantVoiceRecord::query()
                ->where(['status' => '0'])
                ->select()->toArray();
            // 查询数字人任务
            $avatarRecord = TenantAiAvatarRecord::query()
                ->where(['status' => '0'])
                ->select()->toArray();
            // 查询克隆音色任务
            $video = TenantVideo::query()
                ->where(['status' => '0'])
                ->select()->toArray();

            if (empty($cloneRecord)) {
                Log::info("声音克隆：无任务");
            } else {
                self::handleCloneHandle($cloneRecord);
            }

            if (empty($voiceRecord)) {
                Log::info("声音合成：无任务");
            } else {
                self::handleVoiceHandle($voiceRecord);
            }

            if (empty($avatarRecord)) {
                Log::info("数字人合成：无任务");
            } else {
                self::handleVideoHandle($avatarRecord);
            }

            if (empty($video)) {
                Log::info("形象训练：无任务");
            } else {
                self::handleImageHandle($video);
            }

            return true;
        } catch (\Exception $e) {
            Log::write('合成任务查询失败，失败原因:' . $e->getMessage());
            return false;
        }
    }


    /**
     * @notes 声音合成任务超时自动失败
     * @param $voiceRecord
     * @return void
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws DbException
     * @author yfdong
     * @date 2024/11/25 00:04
     */
    public function handleVoiceHandle($voiceRecord)
    {
        // 获取当前时间戳
        $currentTime = time();
        foreach ($voiceRecord as $record) {
            // 如果创建时间和现在的时间超过十分钟
            $createTime = strtotime($record['create_time']);
            if ($currentTime - $createTime > self::TIMEOUT) {
                Log::info("声音合成任务" . $record['task_id'] . "超时自动失败");
                // 退还对应算力 根据对应任务号找到对应消耗算力记录
                $accountLogRecord = AccountLogLogic::findByTaskId($record['task_id']);
                if (empty($accountLogRecord)) {
                    Log::info("声音合成任务" . $record['task_id'] . "未找到对应账单记录");
                } else {
                    // 保存对应用户算力消耗记录
                    $userAccount = [
                        'user_id'    => $accountLogRecord['user_id'],
                        // 类型为增加
                        'changeType' => AccountLogEnum::UM_INC_ORDER_VOICE_BACK,
                        'action'     => AccountLogEnum::INC,
                        'num'        => $accountLogRecord['change_amount'],
                        'remark'     => $record['task_id'],
                    ];
                    UserLogic::adjustUserMoney($userAccount);
                    // 订单状态改为失败
                    TenantVoiceRecord::query()
                        ->where('id', $record['id'])->update([
                            'status' => '2',
                        ]);
                }
            }
        }
    }


    /**
     * @notes 数字人合成任务超时自动失败
     * @param $voiceRecord
     * @return void
     * @throws DbException
     * @author yfdong
     * @date 2024/11/25 00:14
     */
    public function handleVideoHandle($avatarRecord)
    {
        // 获取当前时间戳
        $currentTime = time();
        foreach ($avatarRecord as $record) {
            // 如果创建时间和现在的时间超过十分钟
            $createTime = strtotime($record['create_time']);
            if ($currentTime - $createTime > self::TIMEOUT) {
                Log::info("数字人合成任务" . $record['task_id'] . "超时自动失败");
                // 退还对应算力 根据对应任务号找到对应消耗算力记录
                $accountLogRecord = AccountLogLogic::findByTaskId($record['task_id']);
                if (empty($accountLogRecord)) {
                    Log::info("数字人合成任务" . $record['task_id'] . "未找到对应账单记录");
                } else {
                    // 保存对应用户算力消耗记录
                    $userAccount = [
                        'user_id'    => $accountLogRecord['user_id'],
                        // 类型为增加
                        'changeType' => AccountLogEnum::UM_INC_ORDER_AVATAR_BACK,
                        'action'     => AccountLogEnum::INC,
                        'num'        => $accountLogRecord['change_amount'],
                        'remark'     => $record['task_id'],
                    ];
                    UserLogic::adjustUserMoney($userAccount);
                    // 订单状态改为失败
                    TenantAiAvatarRecord::query()
                        ->where('id', $record['id'])->update([
                            'status' => '2',
                        ]);
                }
            }
        }
    }

    /**
     * @notes 音色克隆任务超时自动失败
     * @param $cloneRecord
     * @return void
     * @throws DbException
     * @author yfdong
     * @date 2024/12/10 00:49
     */
    public function handleCloneHandle($cloneRecord)
    {
        // 获取当前时间戳
        $currentTime = time();
        foreach ($cloneRecord as $record) {
            // 如果创建时间和现在的时间超过十分钟
            $createTime = strtotime($record['create_time']);
            if ($currentTime - $createTime > self::TIMEOUT) {
                Log::info("音色克隆任务" . $record['task_id'] . "超时自动失败");
                // 退还对应算力 根据对应任务号找到对应消耗算力记录
                $accountLogRecord = AccountLogLogic::findByTaskId($record['task_id']);
                if (empty($accountLogRecord)) {
                    Log::info("音色克隆任务" . $record['task_id'] . "未找到对应账单记录");
                } else {
                    // 保存对应用户算力消耗记录
                    $userAccount = [
                        'user_id'    => $accountLogRecord['user_id'],
                        'changeType' => AccountLogEnum::UM_INC_ORDER_CLONE_BACK,
                        'action'     => AccountLogEnum::INC,
                        'num'        => $accountLogRecord['change_amount'],
                        'remark'     => $record['taskid'],
                    ];
                    UserLogic::adjustUserMoney($userAccount);
                    // 订单状态改为失败
                    TenantVoice::query()
                        ->where('id', $record['id'])->update([
                            'status' => '2',
                        ]);
                }
            }
        }
    }

    /**
     * @notes 模型训练超时自动失败
     * @param $cloneRecord
     * @return void
     * @throws DbException
     * @author yfdong
     * @date 2024/12/26 22:28
     */
    public function handleImageHandle($imageRecord)
    {
        // 获取当前时间戳
        $currentTime = time();
        foreach ($imageRecord as $record) {
            // 如果创建时间和现在的时间超过十分钟
            $createTime = strtotime($record['create_time']);
            if ($currentTime - $createTime > self::TIMEOUT) {
                Log::info("数字人模型训练任务" . $record['task_id'] . "超时自动失败");
                // 退还对应算力 根据对应任务号找到对应消耗算力记录
                $accountLogRecord = AccountLogLogic::findByTaskId($record['task_id']);
                if (empty($accountLogRecord)) {
                    Log::info("数字人模型训练任务" . $record['task_id'] . "未找到对应账单记录");
                } else {
                    // 保存对应用户算力消耗记录
                    $userAccount = [
                        'user_id'    => $accountLogRecord['user_id'],
                        'changeType' => AccountLogEnum::UM_INC_ORDER_CLONE_BACK,
                        'action'     => AccountLogEnum::INC,
                        'num'        => $accountLogRecord['change_amount'],
                        'remark'     => $record['taskid'],
                    ];
                    UserLogic::adjustUserMoney($userAccount);
                    // 订单状态改为失败
                    TenantVideo::query()
                        ->where('id', $record['id'])->update([
                            'status' => '2',
                        ]);
                }
            }
        }
    }

}