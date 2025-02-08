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


namespace app\api\controller\avatar;


use app\api\controller\BaseApiController;
use app\api\lists\avatar\AiAvatarRecordLists;
use app\api\logic\key\KeyPoolLogic;
use app\api\logic\power\PowerConfigLogic;
use app\api\logic\voice\VoiceRecordLogic;
use app\api\service\AiAvatarService;
use app\api\service\AvatarImageService;
use app\api\service\HttpService;
use app\common\enum\ModuleEnum;
use app\common\enum\user\AccountLogEnum;
use app\common\enum\YesNoEnum;
use app\common\logic\AccountLogLogic;
use app\common\model\file\TenantFile;
use app\common\model\video\TenantVideo;
use app\common\service\FileService;
use app\common\service\UploadService;
use app\tenantapi\logic\avatar\TenantAiAvatarRecordLogic;
use app\tenantapi\logic\user\UserLogic;
use app\tenantapi\validate\avatar\TenantAiAvatarRecordValidate;
use app\tenantapi\validate\user\AdjustUserMoney;
use think\facade\Log;
use think\response\Json;

/**
 * TenantAiAvatarRecord控制器
 * Class TenantAiAvatarRecordController
 * @package app\tenantapi\controller\avatar
 */
class AiAvatarRecordController extends BaseApiController
{

    public array $notNeedLogin = ['receiveAiAvatar', 'receiveAiAvatarV3'];

    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function lists()
    {
        return $this->dataLists(new AiAvatarRecordLists());
    }

    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function edit()
    {
        $params = (new TenantAiAvatarRecordValidate())->post()->goCheck('edit');
        $result = TenantAiAvatarRecordLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(TenantAiAvatarRecordLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function delete()
    {
        $params = (new TenantAiAvatarRecordValidate())->post()->goCheck('delete');
        TenantAiAvatarRecordLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author likeadmin
     * @date 2024/10/09 22:23
     */
    public function detail()
    {
        $params = (new TenantAiAvatarRecordValidate())->goCheck('detail');
        $result = TenantAiAvatarRecordLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 创建数字人视频订单
     * @return Json
     * @throws \Exception
     * @author yfdong
     * @date 2024/10/10 20:40
     */
    public function createAiAvatar(): Json
    {
        // 获取当前请求的域名 生成结果通知接口地址
        $receiveUrl = "/api/avatar.aiAvatarRecord/receiveAiAvatar";
        $receiveUrl = "https://" . $_SERVER['HTTP_HOST'] . $receiveUrl;
        // 获取请求参数
        $params = $this->request->post();
        $mode = "V" . $params['mode'];
        $powerConfig = PowerConfigLogic::getAvatarConfig($mode);
        if ($powerConfig['video_mode_status'] != YesNoEnum::YES) {
            return $this->fail($mode . "通道未启用");
        }
        $params['uid'] = $this->userId;
        $params['terminal'] = $this->terminal;
        // 模式选择
        if (!isset($params['mode']) || empty($params['mode'])) {
            return $this->fail('请先选择对应数字人合成模式');
        }
        // 类型为数字人合成对应的key池配置
        $keyPool = KeyPoolLogic::moduleKey(ModuleEnum::MODULE_VIDEO);
        if (!isset($keyPool) || !isset($keyPool['key'])) {
            return $this->fail('请联系管理员配置key池中' . ModuleEnum::MODULE_VIDEO_NAME . '密钥！');
        }

        // 获取用户选择视频和音频的地址 内容合成结果作为材料
        $voiceInfo = TenantFile::query()->where(['id' => $params['voice_id']])->findOrEmpty()->toArray();
        $resultInfo = VoiceRecordLogic::getInfoByFileId($params['voice_id']);
        $voiceInfo['url'] = $voiceInfo['uri'];

        if ($params['video_id']) {
            $videoInfo = TenantVideo::query()->with('file')->where(['id' => $params['video_id'], 'uid' => $params['uid']])->findOrEmpty()->toArray();
        }
        // 判断数据是否有效
        if (!isset($voiceInfo['id']) || !isset($videoInfo['id'])) {
            return $this->fail('音视频文件受损！');
        }

        $voiceUrl = FileService::getFileUrl(isset($voiceInfo['url']) ? $voiceInfo['url'] : $voiceInfo['file']['uri']);
        $videoUrl = FileService::getFileUrl($videoInfo['file']['uri']);

        // 根据用户选择的数字人音频的时长计算对应消耗
        $costPower = (new AiAvatarService())->computePowerCost($resultInfo['duration'], $mode);

        if ($costPower == 0) {
            return $this->fail('获取对应视频文件时长失败！');
        }

        // 如果算力不足对用户进行提醒
        $moneyEnough = (new AdjustUserMoney)->checkMoney($costPower, null, ['user_id' => $this->userId, 'action' => AccountLogEnum::DEC]);
        if (!$moneyEnough) {
            return $this->fail("用户余额不足，本次需要算力" . $costPower . "点");
        }
        if ($mode == 'V3') {
            Log::INFO("本次合成使用" . $mode . "模型");
            if (empty($videoInfo['image_id'])) {
                $videoInfo['image_id'] = (new AvatarImageService)->createAvatarImage($videoInfo['id'], $videoInfo['name'], $videoUrl);
            }
            try {
                $result = (new AiAvatarService)->createAvatarVideo($videoInfo['image_id'], $voiceUrl);
            } catch (\Exception $e) {
                return $this->fail($e->getMessage());
            }
            if (200 == $result['code']) {
                $taskId = $result['data']['taskid'];
            } else {
                return $this->fail($result['msg']);
            }
        } else {
            // 获取请求参数
            $queryParams = array(
                'audio_url'  => $voiceUrl,
                'video_url'  => $videoUrl,
                'notify_url' => $receiveUrl,
            );
            // 获取数字人生成开启的模式配置
            if ($mode == 'V1') {
                $url = ModuleEnum::AVATAR_API_URL;
            } else if ($mode == 'V2') {
                $url = ModuleEnum::AVATAR_API_URL_V2;
            }
            // 调用接口获取任务号
            try {
                $result = (new HttpService)->ckPost($url, $keyPool['key'], $queryParams);
            } catch (\Exception $e) {
                return $this->fail($e->getMessage());
            }
            Log::INFO("创建生成数字人视频接口，返回结果" . json_encode($result));
            if (200 == $result['code']) {
                $taskId = $result['data']['taskid'];
            } else {
                return $this->fail($result['msg']);
            }
        }

        // 保存对应用户算力消耗记录
        $userAccount = [
            'user_id'    => $params['uid'],
            // 类型为减少
            'action'     => AccountLogEnum::DEC,
            'changeType' => AccountLogEnum::UM_DEC_ORDER_AVATAR,
            'num'        => $costPower,
            'remark'     => $taskId,
        ];
        UserLogic::adjustUserMoney($userAccount);

        // 创建对应生成数字人视频记录
        $record = [
            'title'      => $params['title'] ?? '',
            'uid'        => $params['uid'],
            'cover'      => $params['cover'] ?? '',
            'task_id'    => $taskId,
            'mode'       => $params['mode'],
            'voice_id'   => $params['voice_id'],
            'video_id'   => $params['video_id'],
            'cost_power' => $costPower,
            'terminal'   => $params['terminal'],
        ];
        $result = TenantAiAvatarRecordLogic::add($record);
        // 返回给前端
        if (true === $result) {
            return $this->success('添加成功', [
                'taskId'     => $taskId,
                'receiveUrl' => $receiveUrl,
            ], 1, 0);
        } else {
            return $this->fail(TenantAiAvatarRecordLogic::getError());
        }
    }

    /**
     * @notes 接受创建数字人视频结果
     * @return \think\response\Json
     * @author yfdong
     * @date 2024/10/10 20:41
     */
    public function receiveAiAvatar()
    {
        // 接收创建结果
        $param = $this->request->post() ?? $this->request->get();
        Log::info('接受创建数字人视频结果：' . json_encode($param));
        if (!isset($param['taskid'])) {
            return $this->fail("参数缺失任务id");
        }
        $record = TenantAiAvatarRecordLogic::getInfoByTaskId($param['taskid']);
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
        if (0 != $param['errcode']) {
            // 创建失败
            $record['status'] = '2';
            // 回调对应消耗算力 根据对应任务号找到对应消耗算力记录
            $accountLogRecord = AccountLogLogic::findByTaskId($param['taskid']);
            // 保存对应用户算力消耗记录
            $userAccount = [
                'user_id'    => $accountLogRecord['user_id'],
                // 类型为增加
                'changeType' => AccountLogEnum::UM_INC_ORDER_AVATAR_BACK,
                'action'     => AccountLogEnum::INC,
                'num'        => $accountLogRecord['change_amount'],
                'remark'     => $param['taskid'],
            ];
            UserLogic::adjustUserMoney($userAccount);
        } else {
            $record['status'] = '1';
            if (isset($param['fileurl'])) {
                $fileInfo = UploadService::saveResultVideo($param['fileurl'], $param['taskid']);
                if (isset($fileInfo['id'])) {
                    $record['file_id'] = $fileInfo['id'];
                    $record['size'] = $fileInfo['size'];
                    $record['duration'] = $fileInfo['duration'];
                }
            } else if (isset($_FILES['target_file'])) {
                Log::info('接受创建数字人视频结果文件信息：' . json_encode($_FILES['target_file']));
                $fileInfo = UploadService::saveResultVideoByFile('target_file', $param['taskid']);
                $record['file_id'] = $fileInfo['id'];
                $record['size'] = $fileInfo['size'];
                $record['duration'] = $fileInfo['duration'];
            }
        }
        $result = TenantAiAvatarRecordLogic::edit($record);
        if (true === $result && $record['status'] == '1') {
            Log::info('接受创建数字人视频结果文件信息成功：' . json_encode($fileInfo));
            return $this->success('创建结果接收成功', [$fileInfo], 1, 1);
        } else {
            Log::info('接受创建数字人视频结果为失败：' . $param['taskid']);
        }
        return $this->success('创建结果接收成功', [], 1, 1);
    }


    /**
     * @notes 数字人V3接口通知接口
     * @return Json
     * @throws \Exception
     * @author yfdong
     * @date 2024/12/17 22:59
     */
    public function receiveAiAvatarV3()
    {
        // 接收创建结果
        $param = $this->request->post() ?? $this->request->get();
        Log::info('接受V3创建数字人视频结果：' . json_encode($param));
        if (!isset($param['taskid'])) {
            Log::info('参数错误');
            return $this->fail("参数缺失任务id");
        }
        $record = TenantAiAvatarRecordLogic::getInfoByTaskId($param['taskid']);
        if (!isset($record['id'])) {
            Log::info('对应任务在本系统不存在：' . $param['taskid']);
            return $this->fail("对应任务在本系统不存在");
        }
        header('Content-Type: application/json');
        echo json_encode(['code' => 1, 'show' => 1, 'msg' => '创建结果接收成功', 'data' => []]);
        // 结束当前请求并立即返回响应
        fastcgi_finish_request();
        // 更新对应记录信息
        // 获取当前时间
        $currentDateTime = time();
        // 创建任务时间
        $specifiedDateTime = strtotime($record['create_time']);
        // 计算耗时
        $record['completion_time'] = date('Y-m-d h:m:s');
        $record['cost_time'] = $currentDateTime - $specifiedDateTime;
        if (0 != $param['errcode']) {
            // 创建失败
            $record['status'] = '2';
            // 回调对应消耗算力 根据对应任务号找到对应消耗算力记录
            $accountLogRecord = AccountLogLogic::findByTaskId($param['taskid']);
            // 保存对应用户算力消耗记录
            $userAccount = [
                'user_id'    => $accountLogRecord['user_id'],
                // 类型为增加
                'changeType' => AccountLogEnum::UM_INC_ORDER_AVATAR_BACK,
                'action'     => AccountLogEnum::INC,
                'num'        => $accountLogRecord['change_amount'],
                'remark'     => $param['taskid'],
            ];
            UserLogic::adjustUserMoney($userAccount);
        } else {
            $record['status'] = '1';
            if (isset($param['target_file'])) {
                $fileInfo = UploadService::saveResultVideo($param['target_file'], $param['taskid']);
                if (isset($fileInfo['id'])) {
                    $record['file_id'] = $fileInfo['id'];
                    $record['size'] = $fileInfo['size'];
                    $record['duration'] = $param['video_time'] ?? null;
                }
            }
        }
        $result = TenantAiAvatarRecordLogic::edit($record);
        if (true === $result && $record['status'] == '1') {
            Log::info('接受创建数字人视频结果文件信息成功：' . json_encode($fileInfo));
            return $this->success('创建结果接收成功', [$fileInfo], 1, 1);
        } else {
            Log::info('接受创建数字人V3视频结果为失败：' . $param['taskid']);
        }
        return $this->success('创建结果接收成功', [], 1, 1);
    }
}
