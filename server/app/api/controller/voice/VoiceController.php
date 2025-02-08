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
use app\api\lists\voice\VoiceLists;
use app\api\logic\power\PowerConfigLogic;
use app\api\service\CloneVoiceService;
use app\common\enum\user\AccountLogEnum;
use app\common\logic\AccountLogLogic;
use app\common\model\file\TenantFile;
use app\common\service\ConfigService;
use app\common\service\FileService;
use app\tenantapi\logic\user\UserLogic;
use app\api\logic\voice\VoiceLogic;
use app\tenantapi\validate\voice\TenantVoiceValidate;
use think\facade\Log;
use think\response\Json;


/**
 * 声音克隆控制器
 * Class VoiceController
 * @package app\api\controller\voice
 */
class VoiceController extends BaseApiController
{

    public array $notNeedLogin = ['receiveCheckAudio'];

    /**
     * @notes 获取音色克隆配置
     * @return Json
     * @author yfdong
     * @date 2024/11/18 21:56
     */
    public function getVoiceCloneConfig()
    {
        $result = [
            // 音频要求录制文案
            'voice_copy' => ConfigService::get('power', 'voice_copy', '我的声音将用于平台克隆，并合法使用，为自己的行为负责'),
        ];
        return $this->data($result);
    }

    /**
     * @notes 获取列表
     * @return Json
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public function lists()
    {
        return $this->dataLists(new VoiceLists());
    }


    /**
     * @notes 添加
     * @return Json
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public function add()
    {
        $params = (new TenantVoiceValidate())->post()->goCheck('add');
        $params['uid'] = $this->userId;
        $params['terminal'] = $this->terminal;
        // 获取当前请求的域名 拼接接收结果地址
        $receiveUrl = "/api/voice.voice/receiveCheckAudio";
        $receiveUrl = "https://" . $_SERVER['HTTP_HOST'] . $receiveUrl;
        // 增加克隆音色前置校验对应文案是否符合规则
        // 根据文件id获取对应音频文件url
        $audio = TenantFile::query()->where(['id' => $params['file_id']])->findOrEmpty()->toArray();
        try {
            $result = (new CloneVoiceService)->mediaToText(7, FileService::getFileUrl($audio['uri']), $receiveUrl, $params['expected_content']);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
        // 判断对应返回码
        if (200 == $result['code']) {
            $taskId = $result['data']['taskid'];
        } else {
            return $this->fail($result['msg']);
        }
        $params['task_id'] = $taskId;
        // 声音克隆按照次数收费
        $voiceCloneConfig = PowerConfigLogic::getVoiceCloneConfig();
        // 保存对应用户算力消耗记录
        $userAccount = [
            'user_id'    => $params['uid'],
            // 类型为减少
            'changeType' => AccountLogEnum::UM_DEC_ORDER_CLONE,
            'action'     => AccountLogEnum::DEC,
            'num'        => $voiceCloneConfig['clone_power'],
            'remark'     => $taskId,
        ];
        UserLogic::adjustUserMoney($userAccount);
        $result = VoiceLogic::add($params);
        if (true === $result) {
            return $this->success('创建成功', [], 1, 1);
        }
        return $this->fail(VoiceLogic::getError());
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public function edit()
    {
        $params = (new TenantVoiceValidate())->post()->goCheck('edit');
        $result = VoiceLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(VoiceLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public function delete()
    {
        $params = (new TenantVoiceValidate())->post()->goCheck('delete');
        VoiceLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/09 22:12
     */
    public function detail()
    {
        $params = (new TenantVoiceValidate())->goCheck('detail');
        $result = VoiceLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 接收文案校验结果接口
     * @return Json
     * @author yfdong
     * @date 2024/12/09 22:54
     */
    public function receiveCheckAudio(): Json
    {
        // 接收校验音频文案结果
        $param = $this->request->post() ?? $this->request->get();
        Log::info('接收校验音频文案结果：' . json_encode($param));
        if (!isset($param['taskid'])) {
            return $this->fail("参数缺失任务id");
        }
        // 更新对应记录信息
        $record = VoiceLogic::getInfoByTaskId($param['taskid']);
        if (!isset($record['id'])) {
            return $this->fail("对应任务在本系统不存在");
        }
        // 获取当前时间
        $currentDateTime = time();
        // 创建任务时间
        $specifiedDateTime = strtotime($record['create_time']);
        // 计算耗时
        $costTime = $currentDateTime - $specifiedDateTime;
        Log::info('接收校验音频文案结果:' . $param['taskid'] . '，耗时' . $costTime);

        $success = (
            (isset($param['errcode']) && ($param['errcode'] == 0 || $param['errcode'] == 200))
            ||
            (isset($param['code']) && ($param['code'] == 200 || $param['code'] == 0))
        );

        $state = isset($param['otherdata']) ? json_decode($param['otherdata'])->state : null;

        if (!$success || $state === 'false') {
            // 创建失败
            $record['status'] = '2';
            if ($param['text']) {
                $record['actual_content'] = $param['text'];
            }
            // 回退对应消耗算力 根据对应任务号找到对应消耗算力记录
            $accountLogRecord = AccountLogLogic::findByTaskId($param['taskid']);
            // 回退对应用户算力消耗记录
            $userAccount = [
                'user_id'    => $accountLogRecord['user_id'],
                'changeType' => AccountLogEnum::UM_INC_ORDER_CLONE_BACK,
                'action'     => AccountLogEnum::INC,
                'num'        => $accountLogRecord['change_amount'],
                'remark'     => $param['taskid'],
            ];
            UserLogic::adjustUserMoney($userAccount);
        } elseif ($success && $state === 'success') {
            $record['status'] = '1';
            $record['actual_content'] = $param['text'];
        }

        $result = VoiceLogic::edit($record);
        if (true === $result) {
            Log::info('接收校验音频文案为成功：' . $param['taskid']);
            if ($record['status'] == '1') {
                Log::info('文本校验通过：' . $param['taskid']);
            } else {
                Log::info('文本校验失败：' . $param['taskid']);
            }
        } else {
            Log::info('接收校验音频文案为失败：' . $param['taskid']);
        }
        return $this->success('接收校验音频文案成功', [], 1, 1);
    }
}
