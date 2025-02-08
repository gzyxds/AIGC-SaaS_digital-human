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


namespace app\api\controller\voice;


use app\api\controller\BaseApiController;
use app\api\lists\voicerecord\VoiceRecordLists;
use app\api\logic\key\KeyPoolLogic;
use app\api\logic\voice\VoiceLogic;
use app\api\service\AiAvatarService;
use app\api\service\CloneVoiceService;
use app\api\service\HttpService;
use app\api\service\SensitiveService;
use app\api\validate\voicerecord\VoiceRecordValidate;
use app\common\enum\ModuleEnum;
use app\common\enum\user\AccountLogEnum;
use app\common\logic\AccountLogLogic;
use app\common\service\FileService;
use app\common\service\UploadService;
use app\tenantapi\logic\user\UserLogic;
use app\api\logic\voice\VoiceRecordLogic;
use app\tenantapi\validate\user\AdjustUserMoney;
use app\tenantapi\validate\voicerecord\TenantVoiceRecordValidate;
use think\facade\Log;


/**
 * TenantVoiceRecord控制器
 * Class TenantVoiceRecordController
 * @package app\tenantapi\controller\voicerecord
 */
class RecordController extends BaseApiController
{

    public array $notNeedLogin = ['receiveCloneVoice'];

    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function lists()
    {
        return $this->dataLists(new VoiceRecordLists());
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function edit()
    {
        $params = (new TenantVoiceRecordValidate())->post()->goCheck('edit');
        $result = VoiceRecordLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(VoiceRecordLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function delete()
    {
        $params = (new TenantVoiceRecordValidate())->post()->goCheck('delete');
        VoiceRecordLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:15
     */
    public function detail()
    {
        $params = (new TenantVoiceRecordValidate())->goCheck('detail');
        $result = VoiceRecordLogic::detail($params);
        return $this->data($result);
    }


    /**
     * @notes 创建声音克隆合成订单
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/10 20:40
     */
    public function createCloneVoice()
    {
        // 获取当前请求的域名 拼接接收结果地址
        $receiveUrl = "/api/voice.record/receiveCloneVoice";
        $receiveUrl = "https://" . $_SERVER['HTTP_HOST'] . $receiveUrl;

        $params = (new VoiceRecordValidate())->post()->goCheck('add');

        $params['uid'] = $this->userId;
        $params['terminal'] = $this->terminal;

        // 获取用户选择视频和音频的地址  允许用户使用网络音频地址
        if (isset($params['voice_id']) && !empty($params['voice_id'])) {
            $voiceInfo = VoiceLogic::detail(['id' => $params['voice_id']]);
            $voiceUrl = FileService::getFileUrl($voiceInfo['fileUrl']);
            $voiceInfo['url'] = $voiceUrl;
            // 判断数据是否有效
            if (!isset($voiceInfo['id'])) {
                return $this->fail('音频文件受损');
            }
            if ($voiceInfo['status'] != 1) {
                return $this->fail('对应使用克隆音色暂未生效');
            }
        } else if (!empty($params['timbre_name'])) {
            $voiceInfo['timbre_name'] = $params['timbre_name'];
        }

        // 敏感词检测
        // 类型为敏感词检测密钥的key池配置
        $keyPool = KeyPoolLogic::moduleKey(ModuleEnum::MODULE_SENSITIVE);
        if (!isset($keyPool) || !isset($keyPool['key'])) {
            return $this->fail('请联系管理员配置' . ModuleEnum::MODULE_SENSITIVE_NAME . '密钥');
        }
        try {
            $checkResult = (new SensitiveService)->sensitiveCheck($keyPool['key'], $params['content']);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
        if (!$checkResult['pass']) {
            return $this->fail('合成内容包含敏感词：' . $checkResult['msg']);
        }

        // 根据文本内容消耗内容
        $costPower = (new AiAvatarService())->computeViocePowerCost($params['content']);
        if ($costPower == 0) {
            return $this->fail('计算对应音频消耗算力失败');
        }

        // 如果算力不足对用户进行提醒
        $moneyEnough = (new AdjustUserMoney)->checkMoney($costPower, null, ['user_id' => $this->userId, 'action' => AccountLogEnum::DEC]);
        if (!$moneyEnough) {
            return $this->fail("用户余额不足，本次需要算力" . $costPower . "点");
        }

        if (isset($params['model']) && $params['model'] == 'V1') {
            // 获取key池中V1声音合成key
            $keyPool = KeyPoolLogic::moduleKey(ModuleEnum::MODULE_VOICE);
            if (!isset($keyPool) || !isset($keyPool['key'])) {
                return $this->fail('请联系管理员配置' . ModuleEnum::MODULE_VOICE_NAME . '密钥');
            }

            $params['content'] = preg_replace_callback(
                "/([^\r\n]*)([\r\n]+)/",  // 匹配非换行符的内容和换行符
                function ($matches) {
                    // $matches[1] 是非换行符内容部分，$matches[2] 是换行符部分
                    $content = $matches[1];
                    $lineBreak = $matches[2];

                    // 检查内容末尾是否已包含标点符号
                    if (!preg_match('/[。！？,.]$/u', $content)) {
                        // 如果末尾没有标点符号，添加句号
                        $content .= '。';
                    }

                    return $content . $lineBreak;
                },
                $params['content']
            );
            // 调用接口获取任务号
            $params_v1 = array(
                'source_audio_url' => $voiceUrl,
                'target_text'      => $params['content'],
                'notify_url'       => $receiveUrl,
                'speed'            => $params['speed'],
                'ref_text'         => $voiceInfo['actual_content'],
            );
            try {
                $result = (new HttpService)->ckPost(ModuleEnum::VOICE_API_URL, $keyPool['key'], $params_v1);
            } catch (\Exception $e) {
                return $this->fail($e->getMessage());
            }
            Log::INFO("创建克隆声接口，返回结果" . json_encode($result));
            if (200 == $result['code']) {
                $taskId = $result['data']['taskid'];
            } else {
                return $this->fail($result['msg']);
            }
        } else {
            // 调用V2接口
            try {
                $result = (new CloneVoiceService)->createCloneVoiceV2($voiceInfo, $receiveUrl, $params);
                $taskId = $result['taskid'];
            } catch (\Exception $e) {
                return $this->fail($e->getMessage());
            }
        }
        // 保存对应用户算力消耗记录
        $userAccount = [
            'user_id'    => $params['uid'],
            // 类型为减少
            'changeType' => AccountLogEnum::UM_DEC_ORDER_VOICE,
            'action'     => AccountLogEnum::DEC,
            'num'        => $costPower,
            'remark'     => $taskId,
        ];
        UserLogic::adjustUserMoney($userAccount);

        // 创建对应克隆声记录
        $record = [
            'tenant_id'            => request()->tenantId,
            'uid'                  => $params['uid'],
            'task_id'              => $taskId,
            'voice_id'             => $params['voice_id'],
            'content'              => $params['content'],
            'cost_power'           => $costPower,
            'title'                => $params['title'] ?? '',
            'speed'                => $params['speed'],
            'status'               => '0',
            'cover'                => $params['cover'] ?? '',
            'timbre_name'          => !empty($params['timbre_name']) ? $params['timbre_name'] : null,
            'once_use_video'       => $params['once_use_video'] ?? null,
            'once_use_video_model' => $params['once_use_video_model'] ?? null,
            'terminal'             => $params['terminal'] ?? null,
        ];
        $result = VoiceRecordLogic::add($record);
        // 返回给前端
        if (true === $result) {
            return $this->success('创建成功', [
                'taskId'     => $taskId,
                'receiveUrl' => $receiveUrl,
            ], 1, 1);
        } else {
            return $this->fail(VoiceRecordLogic::getError());
        }
    }


    /**
     * @notes 接收克隆声创建结果
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/10 20:41
     */
    public function receiveCloneVoice()
    {
        // 接收创建结果
        $param = $this->request->post() ?? $this->request->get();
        Log::info('接收克隆声结果：' . json_encode($param));

        if (!isset($param['taskid'])) {
            return $this->fail("参数缺失任务id");
        }
        $record = VoiceRecordLogic::getInfoByTaskId($param['taskid']);
        if (!isset($record['id'])) {
            return $this->fail("对应任务在本系统不存在");
        }
        // 更新对应记录信息
        // 获取当前时间
        $currentDateTime = time();
        // 创建任务时间
        $specifiedDateTime = strtotime($record['create_time']);
        // 计算耗时
        $record['completion_time'] = date('Y-m-d h:m:s');
        $record['cost_time'] = $currentDateTime - $specifiedDateTime;
        $success = (
            (isset($param['errcode']) && ($param['errcode'] == 0 || $param['errcode'] == 200))
            ||
            (isset($param['code']) && ($param['code'] == 200 || $param['code'] == 0))
        );
        if (!$success) {
            // 创建失败
            $record['status'] = '2';
            // 回调对应消耗算力 根据对应任务号找到对应消耗算力记录
            $accountLogRecord = AccountLogLogic::findByTaskId($param['taskid']);
            // 保存对应用户算力消耗记录
            $userAccount = [
                'user_id'    => $accountLogRecord['user_id'],
                // 类型为增加
                'changeType' => AccountLogEnum::UM_INC_ORDER_VOICE_BACK,
                'action'     => AccountLogEnum::INC,
                'num'        => $accountLogRecord['change_amount'],
                'remark'     => $param['taskid'],
            ];
            UserLogic::adjustUserMoney($userAccount);
        } else {
            $record['status'] = '1';
            if (isset($_FILES['target_file'])) {
                Log::info('接受创建克隆声结果文件信息：' . json_encode($_FILES['target_file']));
                $fileInfo = UploadService::saveResultVoiceByFile('target_file', $param['taskid']);
                $record['file_id'] = $fileInfo['id'];
                $record['size'] = $fileInfo['size'];
                $record['duration'] = $fileInfo['duration'];
            }
            // 新增逻辑 如果创建声音时选择了对应数字人形象，需要创建对应的
            //            if (isset($record['once_use_video']) && isset($record['once_use_video_model'])) {
            //                (new AiAvatarService)->createAiAvatar($record['id'], $record['once_use_video'], $fileInfo['duration'], $record['once_use_video_model'], $record['uid'], $fileInfo['uri']);
            //            }
        }
        $result = VoiceRecordLogic::edit($record);
        if (true === $result && $record['status'] == '1') {
            Log::info('接收克隆声结果成功：' . json_encode($fileInfo));
            return $this->success('创建结果接收成功', [$fileInfo], 1, 1);
        } else {
            Log::info('接收克隆声结果为失败：' . $param['taskid']);
        }
        return $this->success('接收克隆声结果成功', [], 1, 1);
    }


    /**
     * @notes 上传文件作为结果文件
     * @return \think\response\Json
     * @author yfdong
     * @date 2025/01/08 21:25
     */
    public function uploadLocalVoice()
    {
        $params = (new VoiceRecordValidate())->post()->goCheck('upload');
        $params['uid'] = $this->userId;
        $params['terminal'] = $this->terminal;
        $params['status'] = 1;
        $result = VoiceRecordLogic::uploadLocalVoice($params);
        if (true === $result) {
            return $this->success('创建成功', [], 1, 1);
        }
        return $this->fail(VoiceRecordLogic::getError());
    }

}