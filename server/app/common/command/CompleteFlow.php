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

use app\api\logic\complete\FlowLogic;
use app\api\service\AiAvatarService;
use app\api\service\AvatarImageService;
use app\api\service\CloneVoiceService;
use app\common\enum\user\AccountLogEnum;
use app\common\model\avatar\TenantAiAvatarRecord;
use app\common\model\complete\TenantCompleteFlow;
use app\common\model\file\TenantFile;
use app\common\model\video\TenantVideo;
use app\common\model\voice\TenantVoice;
use app\common\model\voicerecord\TenantVoiceRecord;
use app\common\service\FileService;
use app\tenantapi\logic\user\UserLogic;
use app\tenantapi\validate\user\AdjustUserMoney;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Exception;
use think\facade\Db;
use think\facade\Log;


class CompleteFlow extends Command
{

    protected function configure()
    {
        $this->setName('complete_flow')
            ->setDescription('数字人合成全流程定时任务');
    }


    /**
     * 全流程合成任务处理
     * @param Input $input
     * @param Output $output
     * @return bool
     */
    protected function execute(Input $input, Output $output)
    {
        try {
            Log::info("全流程任务处理开始,时间：" . time());
            // 获取为未完成的全流程任务
            $completeFlows = TenantCompleteFlow::query()
                // 去除成功状态和失败状态
                ->whereNotIn('status', [5, 99])
                ->select()->toArray();

            foreach ($completeFlows as $item) {
                self::flowCase($item);
            }
            Log::info("全流程任务处理结束,时间：" . time());
            return true;
        } catch (\Exception $e) {
            Log::info('全流程任务执行异常，失败原因:' . $e->getMessage());
            return false;
        }
    }

    /**
     * 处理对应任务进入对应操作
     * @return void
     */
    protected function flowCase($record)
    {
        Log::info($record['task_id'] . "全流程任务处理开始");
        // 2=声音处理中，3=模型处理中，4=数字人合成中
        switch ($record['status']) {
            case 0:
                // 为未处理状态 需要进行声音合成操作
                self::voiceRecordCreatFlow($record);
                break;
            case 1:
                // 后期声音克隆预留
                break;
            case 2:
                self::avatarImageFlow($record);
                // 声音处理中 需要检查对应声音合成状态 如果成功 需要创建数字人模型
                break;
            case 3:
                // 数字人模型处理中 需要检查数字人模型状态 如果成功 需要合成数字人视频
                self::avatarFlow($record);
                break;
            case 4:
                // 数字人视频合成中 需要检查数字人视频合成状态
                self::EndFlow($record);
                break;
            default:
                Log::info($record['task_id'] . "任务状态异常");
        }
        Log::info($record['task_id'] . "全流程任务处理完成");
    }

    /**
     * 获取接口接口路径
     */
    private static function receiveUrl($receiveUrl, $host)
    {
        return "https://" . $host . $receiveUrl;
    }

    /**
     * 检查用户算力是否充足
     * @return bool
     */
    private static function checkMoneySufficient($costPower, $uid, $record): bool
    {
        if (!(new AdjustUserMoney)->checkMoney($costPower, null, ['user_id' => $uid, 'action' => AccountLogEnum::DEC])) {
            Log::info("用户算力不足");
            self::completeFlowFail($record, "用户算力不足");
            return false;
        } else {
            return true;
        }
    }

    /**
     * 保存算力记录
     * @param $uid
     * @param $num
     * @param $taskId
     * @return void
     */
    private static function saveAccountDec($uid, $num, $taskId, $tenantId)
    {
        // 保存对应用户算力消耗记录
        $userAccount = [
            'tenant_id'  => $tenantId,
            'user_id'    => $uid,
            // 类型为减少
            'changeType' => AccountLogEnum::UM_DEC_ORDER_VOICE,
            'action'     => AccountLogEnum::DEC,
            'num'        => $num,
            'remark'     => $taskId,
        ];
        UserLogic::adjustUserMoneyHasTenantId($userAccount);
    }

    /**
     * 声音合成操作
     * @return void
     */
    private static function voiceRecordCreatFlow($record)
    {
        Log::info($record['task_id'] . "开始全流程创建声音合成记录");
        $receiveUrl = self::receiveUrl("/api/voice.record/receiveCloneVoice", $record['host']);
        try {
            Db::startTrans();
            // 1.判断用户算力是否充足
            $costPower = (new AiAvatarService())->computeViocePowerCost($record['content'], $record['tenant_id']);
            if (self::checkMoneySufficient((int)$costPower, $record['uid'], $record)) {
                switch ($record['voice_mode']) {
                    case 1:
                        $result = self::V1VoiceContentCreate($record, $receiveUrl);
                        $taskId = $result['data']['taskid'];
                        break;
                    case 2:
                        $result = self::V2VoiceContentCreate($record, $receiveUrl);
                        $taskId = $result['taskid'];
                        break;
                    default:
                        Log::info($record['task_id'] . "声音合成模型通道未开启，全流程任务失败");
                        throw new \Exception("声音合成模型通道未开启，全流程任务失败");
                }
                // 2.保存声音合成记录
                self::saveAccountDec($record['uid'], $costPower, $taskId, $record['tenant_id']);
                // 创建对应克隆声记录
                $voiceRecord = [
                    'tenant_id'   => $record['tenant_id'],
                    'uid'         => $record['uid'],
                    'task_id'     => $taskId,
                    'voice_id'    => $record['voice_id'],
                    'content'     => $record['content'],
                    'cost_power'  => $costPower,
                    'title'       => $record['video_name'],
                    'speed'       => "1",
                    'status'      => '0',
                    'timbre_name' => $record['timbre_name'] ?? null,
                    'terminal'    => $record['terminal'] ?? null,
                ];
                $voiceRecordInfo = TenantVoiceRecord::create($voiceRecord);
                // 3.更新对应全流程任务状态
                (new FlowLogic)->changeFlow($record['id'], ['voice_record_id' => $voiceRecordInfo->id, 'status' => 2]);
                Log::info($record['task_id'] . "完成全流程创建声音合成记录");
            }
            Db::commit();
        } catch (\Exception $e) {
            Log::error("全流程任务" . $record['task_id'] . "声音合成环节异常");
            Log::error($e);
            Db::rollback();
            self::completeFlowFail($record, "声音合成环节异常" . $e->getMessage());
        }
    }

    /**
     * 数字人模型形象创建
     * @param $record
     * @return void
     */
    private static function avatarImageFlow($record)
    {
        try {
            Log::info($record['task_id'] . "开始全流程数字人模型形象创建");
            Db::startTrans();
            // 1. 检查对应声音合成状态
            $voiceRecordInfo = TenantVoiceRecord::query()->where(['id' => $record['voice_record_id']])->findOrEmpty()->toArray();
            if (1 == $voiceRecordInfo['status']) {
                // 2.更新全流程任务中音频合成记录时长
                $duration = $voiceRecordInfo['duration'];
                // 3. 声音合成完成需要创建对应数字人训练模型
                // 优先判断是上传的数字人形象视频还是直接选择历史数字人形象
                if (isset($record['video_id'])) {
                    // 查询对应数字人形象信息
                    $video = TenantVideo::query()->where(['id' => $record['video_id']])->findOrEmpty()->toArray();
                    if (null == $video) {
                        throw new Exception("所选择的数字人形象不存在");
                    }
                    (new FlowLogic)->changeFlow($record['id'], ['video_file_id' => $video['file_id'], 'status' => 3, 'duration' => $duration]);
                } else {
                    // 调用获取数字人模型创建接口
                    $file = TenantFile::query()->where(['id' => $record['video_file_id']])->find()->toArray();
                    $url = (new FileService)->getFileUrlByHost($file['uri'], $record['host']);
                    $video_duration = (new FileService)->getDurationByUrl($url);
                    $image = [
                        'uid'       => $record['uid'],
                        'tenant_id' => $record['tenant_id'],
                        'duration'  => $video_duration,
                        'name'      => $record['video_name'],
                        'file_id'   => $record['video_file_id'],
                        'terminal'  => $record['terminal'],
                    ];
                    // 3.调用接口创建对应模型
                    $result = (new AvatarImageService)->createAvatarImage(null, $record['video_name'], $url, self::receiveUrl("/api/video.avatarVideo/receiveAvatarImage", $record['host']), $record['tenant_id']);
                    $image['image_id'] = $result['data']['mode_id'];
                    // 4. 保存数字人模型记录对应记录
                    $imageInfo = TenantVideo::create($image);
                    (new FlowLogic)->changeFlow($record['id'], ['video_id' => $imageInfo->id, 'status' => 3, 'duration' => $duration]);
                }
                // 5.更新对应全流程任务状态
                Log::info($record['task_id'] . "完成全流程数字人模型形象创建");
            } else if (2 == $voiceRecordInfo['status']) {
                self::completeFlowFail($record, "声音合成创建结果为失败，全流程任务失败");
            }
            Db::commit();
        } catch (\Exception $e) {
            Log::info($e);
            Db::rollback();
            self::completeFlowFail($record, "模型创建环节异常" . $e->getMessage());
        }
    }


    /**
     * @notes 数字人合成视频流程
     * @param $record
     * @return void
     * @author yfdong
     * @date 2024/12/30 22:58
     */
    private static function avatarFlow($record)
    {
        try {
            // 1.检查数字人模型训练是否成功
            Db::startTrans();
            Log::info($record['task_id'] . "开始全流程数字人视频创建");
            $avatarImage = TenantVideo::query()->where(['id' => $record['video_id']])->findOrEmpty()->toArray();
            $voiceRecord = TenantVoiceRecord::query()->with('file')->where(['id' => $record['voice_record_id'], 'uid' => $record['uid']])->findOrEmpty()->toArray();
            // 获取音频地址
            $voiceUrl = (new FileService)->getFileUrlByHost($voiceRecord['file']['uri'], $record['host']);
            if (1 == $avatarImage['status']) {
                // 创建对应生成数字人视频记录 根据当前全流程任务号匹配对应数字人合成视频
                $hasAddAiAvatar = TenantAiAvatarRecord::query()->where(['task_id' => $record['task_id']])->findOrEmpty()->toArray();
                // 2.计算消耗金额
                $costPower = (new AiAvatarService())->computePowerCost($record['duration'], "V" . $record['video_mode'], $record['tenant_id']);
                // 校验用户余额是否充足
                if (self::checkMoneySufficient($costPower, $record['uid'], $record)) {
                    // 3.数字人训练模型完成后，需要创建对应视频
                    switch ($record['video_mode']) {
                        // 极速模式
                        case 1:
                            // 高清模式
                        case 2:
                            // 接收地址
                            $noticeUrl = self::receiveUrl("/api/avatar.aiAvatarRecord/receiveAiAvatar", $record['host']);
                            $video = TenantVideo::query()->with("file")->where(["id" => $record['video_id']])->findOrEmpty()->toArray();
                            $videoUrl = (new FileService)->getFileUrlByHost($video['file']['uri'], $record['host']);
                            $result = (new AiAvatarService)->createAiAvatar($record['voice_record_id'], $record['video_id'], $record['video_mode'], $record['uid'], $noticeUrl, $record['tenant_id'], $voiceUrl, $videoUrl);
                            if (200 == $result['code']) {
                                $taskId = $result['data']['taskid'];
                            } else {
                                throw new \Exception("请求创客接口失败：" . $result['msg']);
                            }
                            break;
                        // 专业模式
                        case 3:
                            $noticeUrl = self::receiveUrl("/api/avatar.aiAvatarRecord/receiveAiAvatarV3", $record['host']);
                            // 创建对应合成任务
                            $result = (new AiAvatarService)->createAvatarVideo($avatarImage['image_id'], $voiceUrl, $noticeUrl);
                            if (200 == $result['code']) {
                                $taskId = $result['data']['taskid'];
                            } else {
                                throw new \Exception($result);
                            }
                            break;
                    }
                    // 保存算力消耗记录
                    self::saveAccountDec($record['uid'], $costPower, $taskId, $record['tenant_id']);
                    // 保存视频记录信息
                    if (!empty($hasAddAiAvatar)) {
                        $avatarRecord = [
                            'title'      => $record['video_name'],
                            'tenant_id'  => $record['tenant_id'],
                            'uid'        => $record['uid'],
                            'task_id'    => $taskId,
                            'voice_id'   => $voiceRecord['file']['id'],
                            'video_id'   => $record['video_id'],
                            'mode'       => $record['video_mode'],
                            'cost_power' => $costPower,
                            'terminal'   => $record['terminal'],
                        ];
                        TenantAiAvatarRecord::update($avatarRecord, ['id' => $hasAddAiAvatar['id']]);
                        (new FlowLogic)->changeFlow($record['id'], ['avatar_record_id' => $hasAddAiAvatar['id'], 'status' => 4]);
                    } else {
                        $avatarRecord = [
                            'title'      => $record['video_name'],
                            'tenant_id'  => $record['tenant_id'],
                            'uid'        => $record['uid'],
                            'task_id'    => $taskId,
                            'voice_id'   => $voiceRecord['file']['id'],
                            'video_id'   => $record['video_id'],
                            'mode'       => $record['video_mode'],
                            'cost_power' => $costPower,
                            'terminal'   => $record['terminal'],
                        ];
                        $aiAvatarRecord = TenantAiAvatarRecord::create($avatarRecord);
                        // 3.更新对应全流程任务状态
                        (new FlowLogic)->changeFlow($record['id'], ['avatar_record_id' => $aiAvatarRecord->id, 'status' => 4]);
                    }
                    Log::info($record['task_id'] . "完成全流程数字人视频合成创建");
                }
            } else if (2 == $avatarImage['status']) {
                self::completeFlowFail($record, "接收数字人模型创建结果为失败");
            }
            Db::commit();
        } catch (\Exception $e) {
            Log::info("全流程任务" . $record['task_id'] . "数字人合成环节异常");
            Log::info($e);
            Db::rollback();
            self::completeFlowFail($record, "数字人合成环节异常" . $e->getMessage());
        }
    }


    /**
     * @notes 全流程结束任务
     * @param $record
     * @return void
     * @author yfdong
     * @date 2024/12/30 23:38
     */
    private static function EndFlow($record)
    {
        try {
            // 1.检查数字人合成结果成功
            $avatarVideo = TenantAiAvatarRecord::query()->where(['id' => $record['avatar_record_id']])->findOrEmpty()->toArray();
            // 2.更新对应全流程任务状态
            if (1 == $avatarVideo['status']) {
                (new FlowLogic)->changeFlow($record['id'], ['status' => 5]);
                Log::info($record['task_id'] . "完成全流程视频合成结果接收创建");
            } else if (2 == $avatarVideo['status']) {
                self::completeFlowFail($record, "数字人视频合成结果接收为失败");
            }
        } catch (\Exception $e) {
            Log::info("全流程任务" . $record['task_id'] . "数字人视频合成结果接收环节异常");
            Log::info($e);
            self::completeFlowFail($record, "数字人视频合成结果接收环节异常");
        }
    }

    /**
     * @notes V2通道声音合成
     * @param $record
     * @param $receiveUrl
     * @return array
     * @throws \think\Exception
     * @author yfdong
     * @date 2025/01/01 22:17
     */
    private static function V2VoiceContentCreate($record, $receiveUrl)
    {
        // 1.创建声音合成记录对应参数
        $params = [
            'speed'   => 1.0,
            'content' => $record['content'],
        ];
        return (new CloneVoiceService)->createCloneVoiceV2(['timbre_name' => $record['timbre']], $receiveUrl, $params, $record['tenant_id']);
    }


    /**
     * @notes V1通道声音合成
     * @param $record
     * @param string $receiveUrl
     * @return array
     * @throws /Exception
     * @author yfdong
     * @date 2025/01/01 22:19
     */
    private static function V1VoiceContentCreate($record, string $receiveUrl)
    {
        // 1.获取音色信息
        $voiceInfo = TenantVoice::query()->with("file")->where(['id' => $record['voice_id']])->findOrEmpty()->toArray();
        $voiceUrl = (new FileService)->getFileUrlByHost($voiceInfo['file']['uri'], $record['host']);
        // 2.请求V1接口合成
        $params = [
            'speed'   => "1",
            'content' => $record['content'],
        ];
        return (new CloneVoiceService)->createCloneVoiceV1(['timbre_name' => $record['timbre'], 'actual_content' => $voiceInfo['actual_content']], $receiveUrl, $params, $voiceUrl, $record['tenant_id']);
    }

    /**
     * @notes 全流程任务失败
     * @param $record
     * @param $reason
     * @param $avatar
     * @return void
     * @author yfdong
     * @date 2025/01/16 22:19
     */
    private static function completeFlowFail($record, $reason)
    {
        Log::info($record['task_id'] . $reason);
        $aiAvatar = TenantAiAvatarRecord::query()->where(['task_id' => $record['task_id']])->findOrEmpty()->toArray();
        if (!empty($aiAvatar) && isset($aiAvatar['id'])) {
            TenantAiAvatarRecord::update(['status' => 2, 'fail_reason' => $reason], ['id' => $aiAvatar['id']]);
        }
        (new FlowLogic)->changeFlow($record['id'], ['status' => 99, 'err_msg' => $reason]);
    }


}